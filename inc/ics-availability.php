<?php
/**
 * Private iCal availability feed.
 *
 * Reads a Google Calendar "secret address in iCal format" on the server and
 * reduces it to a list of dates. Event titles, descriptions, locations and
 * attendees are never stored or sent to the browser: only "this date has an
 * event". Used by the Availability Calendar (Private Feed) block.
 *
 * Caching: each feed is cached for 5 minutes in a transient. The last good
 * result is also kept in an option, so if Google is unreachable the calendar
 * keeps showing the most recent known dates instead of going blank.
 *
 * Limits: recurring events (RRULE) are expanded only for the simple DAILY,
 * WEEKLY and YEARLY cases with COUNT or UNTIL. Venue bookings are one-off
 * events, so this covers real use; anything more complex counts its first
 * occurrence only.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ICS_CACHE_TTL = 5 * MINUTE_IN_SECONDS;

/**
 * Get the set of dates (Y-m-d => true) covered by events in an iCal feed.
 *
 * @param string $url Secret iCal URL.
 * @return array{dates: array<string,bool>, ok: bool, stale: bool}
 */
function ics_busy_dates( string $url ): array {
	$url = \trim( $url );
	if ( '' === $url || ! \wp_http_validate_url( $url ) ) {
		return [ 'dates' => [], 'ok' => false, 'stale' => false ];
	}

	$key    = 'ble_ics_' . \md5( $url );
	$cached = \get_transient( $key );
	if ( \is_array( $cached ) ) {
		return [ 'dates' => $cached, 'ok' => true, 'stale' => false ];
	}

	$response = \wp_remote_get( $url, [
		'timeout'    => 8,
		'user-agent' => 'WordPress; ' . \home_url(),
	] );

	$body = \is_wp_error( $response ) ? '' : \wp_remote_retrieve_body( $response );
	$code = \is_wp_error( $response ) ? 0 : (int) \wp_remote_retrieve_response_code( $response );

	if ( 200 === $code && \str_contains( $body, 'BEGIN:VCALENDAR' ) ) {
		$dates = ics_parse_dates( $body );
		\set_transient( $key, $dates, ICS_CACHE_TTL );
		\update_option( $key . '_last', $dates, false );
		return [ 'dates' => $dates, 'ok' => true, 'stale' => false ];
	}

	// Fetch failed: fall back to the last good copy, and retry in 5 minutes.
	$last = \get_option( $key . '_last' );
	if ( \is_array( $last ) ) {
		\set_transient( $key, $last, 5 * MINUTE_IN_SECONDS );
		return [ 'dates' => $last, 'ok' => true, 'stale' => true ];
	}

	return [ 'dates' => [], 'ok' => false, 'stale' => false ];
}

/**
 * Parse iCal text into a set of covered dates in the site's timezone.
 *
 * @param string $ics Raw iCal text.
 * @return array<string,bool>
 */
function ics_parse_dates( string $ics ): array {
	// Unfold continuation lines (RFC 5545 §3.1).
	$ics   = \preg_replace( "/\r?\n[ \t]/", '', $ics );
	$lines = \preg_split( "/\r?\n/", $ics );

	$site_tz = \wp_timezone();
	$dates   = [];
	$event   = null;

	// Only keep dates from last month to two years out.
	$min = ( new \DateTimeImmutable( 'first day of last month', $site_tz ) )->setTime( 0, 0 );
	$max = $min->modify( '+26 months' );

	foreach ( $lines as $line ) {
		if ( 'BEGIN:VEVENT' === $line ) {
			$event = [];
			continue;
		}
		if ( 'END:VEVENT' === $line ) {
			if ( \is_array( $event ) ) {
				foreach ( ics_event_dates( $event, $site_tz, $min, $max ) as $d ) {
					$dates[ $d ] = true;
				}
			}
			$event = null;
			continue;
		}
		if ( ! \is_array( $event ) || ! \str_contains( $line, ':' ) ) {
			continue;
		}

		[ $name_params, $value ] = \explode( ':', $line, 2 );
		$parts  = \explode( ';', $name_params );
		$name   = \strtoupper( \array_shift( $parts ) );
		$params = [];
		foreach ( $parts as $p ) {
			if ( \str_contains( $p, '=' ) ) {
				[ $k, $v ]                     = \explode( '=', $p, 2 );
				$params[ \strtoupper( $k ) ] = \trim( $v, '"' );
			}
		}

		// Keep only what's needed to place the event on dates. No titles etc.
		if ( \in_array( $name, [ 'DTSTART', 'DTEND', 'STATUS', 'RRULE' ], true ) ) {
			$event[ $name ] = [ 'value' => \trim( $value ), 'params' => $params ];
		}
	}

	\ksort( $dates );
	return $dates;
}

/**
 * Dates (Y-m-d) an event covers, in the site timezone.
 *
 * @param array               $event   Parsed DTSTART/DTEND/STATUS/RRULE.
 * @param \DateTimeZone       $site_tz Site timezone.
 * @param \DateTimeImmutable  $min     Earliest date to keep.
 * @param \DateTimeImmutable  $max     Latest date to keep.
 * @return string[]
 */
function ics_event_dates( array $event, \DateTimeZone $site_tz, \DateTimeImmutable $min, \DateTimeImmutable $max ): array {
	if ( empty( $event['DTSTART'] ) ) {
		return [];
	}
	if ( ! empty( $event['STATUS'] ) && 'CANCELLED' === \strtoupper( $event['STATUS']['value'] ) ) {
		return [];
	}

	$start = ics_to_datetime( $event['DTSTART'], $site_tz, $all_day );
	if ( ! $start ) {
		return [];
	}

	if ( ! empty( $event['DTEND'] ) ) {
		$end = ics_to_datetime( $event['DTEND'], $site_tz, $end_all_day );
	} else {
		$end = $all_day ? $start->modify( '+1 day' ) : $start;
	}
	if ( ! $end || $end < $start ) {
		$end = $start;
	}

	$occurrences = [ [ $start, $end ] ];
	if ( ! empty( $event['RRULE'] ) ) {
		$occurrences = ics_expand_rrule( $event['RRULE']['value'], $start, $end, $max );
	}

	$out = [];
	foreach ( $occurrences as [ $s, $e ] ) {
		// All-day DTEND is exclusive; timed events cover through their end moment.
		$last = $all_day ? $e->modify( '-1 day' ) : ( $e > $s ? $e->modify( '-1 second' ) : $s );
		$day  = $s->setTime( 0, 0 );
		$last = $last->setTime( 0, 0 );
		for ( $i = 0; $day <= $last && $i < 400; $i++ ) {
			if ( $day >= $min && $day < $max ) {
				$out[] = $day->format( 'Y-m-d' );
			}
			$day = $day->modify( '+1 day' );
		}
	}
	return $out;
}

/**
 * Convert an iCal date/date-time property to a DateTimeImmutable in site tz.
 *
 * @param array         $prop    ['value' => string, 'params' => array].
 * @param \DateTimeZone $site_tz Site timezone.
 * @param bool|null     $all_day Set to true when the value is a DATE.
 * @return \DateTimeImmutable|null
 */
function ics_to_datetime( array $prop, \DateTimeZone $site_tz, ?bool &$all_day = null ): ?\DateTimeImmutable {
	$value   = $prop['value'];
	$params  = $prop['params'];
	$all_day = ( isset( $params['VALUE'] ) && 'DATE' === \strtoupper( $params['VALUE'] ) ) || \preg_match( '/^\d{8}$/', $value );

	try {
		if ( $all_day ) {
			// Floating date: the same calendar date everywhere.
			$dt = \DateTimeImmutable::createFromFormat( '!Ymd', \substr( $value, 0, 8 ), $site_tz );
			return $dt ?: null;
		}

		if ( \str_ends_with( $value, 'Z' ) ) {
			$dt = \DateTimeImmutable::createFromFormat( 'Ymd\THis\Z', $value, new \DateTimeZone( 'UTC' ) );
		} elseif ( ! empty( $params['TZID'] ) ) {
			$dt = \DateTimeImmutable::createFromFormat( 'Ymd\THis', $value, new \DateTimeZone( $params['TZID'] ) );
		} else {
			$dt = \DateTimeImmutable::createFromFormat( 'Ymd\THis', $value, $site_tz );
		}
		return $dt ? $dt->setTimezone( $site_tz ) : null;
	} catch ( \Exception $e ) {
		return null;
	}
}

/**
 * Expand simple recurrence rules into [start, end] pairs.
 *
 * @param string              $rrule RRULE value.
 * @param \DateTimeImmutable  $start First start.
 * @param \DateTimeImmutable  $end   First end.
 * @param \DateTimeImmutable  $max   Stop after this date.
 * @return array<int, array{0:\DateTimeImmutable,1:\DateTimeImmutable}>
 */
function ics_expand_rrule( string $rrule, \DateTimeImmutable $start, \DateTimeImmutable $end, \DateTimeImmutable $max ): array {
	$rule = [];
	foreach ( \explode( ';', $rrule ) as $pair ) {
		if ( \str_contains( $pair, '=' ) ) {
			[ $k, $v ]                  = \explode( '=', $pair, 2 );
			$rule[ \strtoupper( $k ) ] = $v;
		}
	}

	$step = [ 'DAILY' => 'day', 'WEEKLY' => 'week', 'YEARLY' => 'year' ][ $rule['FREQ'] ?? '' ] ?? null;
	if ( ! $step || isset( $rule['BYDAY'] ) || isset( $rule['BYMONTHDAY'] ) ) {
		return [ [ $start, $end ] ];
	}

	$interval = \max( 1, (int) ( $rule['INTERVAL'] ?? 1 ) );
	$count    = isset( $rule['COUNT'] ) ? (int) $rule['COUNT'] : null;
	$until    = isset( $rule['UNTIL'] ) ? \DateTimeImmutable::createFromFormat( '!Ymd', \substr( $rule['UNTIL'], 0, 8 ), $start->getTimezone() ) : null;
	$length   = $end->getTimestamp() - $start->getTimestamp();

	$out = [];
	$s   = $start;
	for ( $i = 0; $i < 500; $i++ ) {
		if ( ( null !== $count && $i >= $count ) || ( $until && $s > $until->modify( '+1 day' ) ) || $s > $max ) {
			break;
		}
		$out[] = [ $s, $s->modify( "+{$length} seconds" ) ];
		$s     = $s->modify( "+{$interval} {$step}" );
	}
	return $out;
}
