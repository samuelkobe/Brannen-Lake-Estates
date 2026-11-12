<?php
/**
 * Availability Calendar (Private Feed) — render template.
 *
 * Draws its own month calendar from two private Google Calendar iCal feeds
 * (Booked, Unavailable). Only dates leave the server: no event titles,
 * descriptions or locations are ever output. Booked wins when a date is in
 * both calendars.
 *
 * Without JavaScript every month is listed in order. view.js turns that into
 * a one-month (mobile) / two-month (desktop) view with previous/next buttons.
 *
 * Runs alongside the original Availability Calendar (Google embed) block,
 * which stays available as a fallback.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block      ACF block metadata.
 * @var bool  $is_preview True inside the block editor.
 */

namespace WebOk\BrannenLakeEstates;

$heading     = \get_field( 'heading' );
$tagline     = \get_field( 'tagline' );
$booked_url  = (string) \get_field( 'booked_feed' );
$unavail_url = (string) \get_field( 'unavailable_feed' );
$months      = (int) ( \get_field( 'months' ) ?: 18 );
$note        = \get_field( 'note' );
$block_id    = 'ble-avfeed-' . $block['id'];

if ( ! $booked_url && ! $unavail_url ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p class="ble-avfeed__placeholder">' . \esc_html__( 'Availability Calendar: paste the secret iCal address of the Booked calendar (and optionally Unavailable) in the block settings.', 'brannen-lake-estates' ) . '</p>';
	}
	return;
}

$booked  = $booked_url ? ics_busy_dates( $booked_url ) : [ 'dates' => [], 'ok' => true, 'stale' => false ];
$unavail = $unavail_url ? ics_busy_dates( $unavail_url ) : [ 'dates' => [], 'ok' => true, 'stale' => false ];
$feed_ok = $booked['ok'] && $unavail['ok'];

$tz    = \wp_timezone();
$today = ( new \DateTimeImmutable( 'now', $tz ) )->setTime( 0, 0 );
$first = $today->modify( 'first day of this month' );

// The editor has no view.js, so keep the preview short.
$months = \max( 3, \min( 24, $months ) );
if ( ! empty( $is_preview ) ) {
	$months = 2;
}

$status_for = static function ( string $ymd ) use ( $booked, $unavail ): string {
	if ( isset( $booked['dates'][ $ymd ] ) ) {
		return 'booked';
	}
	if ( isset( $unavail['dates'][ $ymd ] ) ) {
		return 'unavailable';
	}
	return 'available';
};

$labels = [
	'available'   => \__( 'Available', 'brannen-lake-estates' ),
	'booked'      => \__( 'Booked', 'brannen-lake-estates' ),
	'unavailable' => \__( 'Unavailable', 'brannen-lake-estates' ),
	'past'        => \__( 'Past date', 'brannen-lake-estates' ),
];

// Week starts on Sunday (North American convention).
$weekdays = [];
$sunday   = new \DateTimeImmutable( 'last sunday', $tz );
for ( $i = 0; $i < 7; $i++ ) {
	$d          = $sunday->modify( "+{$i} day" );
	$weekdays[] = [ 'short' => \wp_date( 'D', $d->getTimestamp(), $tz ), 'long' => \wp_date( 'l', $d->getTimestamp(), $tz ) ];
}
?>
<section
	id="<?= \esc_attr( $block_id ); ?>"
	class="ble-avfeed px-6"
	<?php if ( $heading ) : ?>aria-labelledby="<?= \esc_attr( $block_id ); ?>-heading"<?php endif; ?>
	data-ble-avfeed
>
	<div class="max-w-6xl mx-auto">

		<?php if ( $heading || $tagline ) : ?>
		<div class="ble-avfeed__header text-center" data-ble-reveal>
			<?php if ( $heading ) : ?>
			<h2 id="<?= \esc_attr( $block_id ); ?>-heading" class="ble-avfeed__heading font-serif font-light italic text-charcoal leading-tight">
				<?= \esc_html( $heading ); ?>
			</h2>
			<?php endif; ?>
			<?php if ( $tagline ) : ?>
			<p class="ble-avfeed__tagline font-sans font-light"><?= \esc_html( $tagline ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( ! $feed_ok ) : ?>
		<p class="ble-avfeed__error" role="status">
			<?= \esc_html__( 'The availability calendar is temporarily unavailable. Please contact us to check your date.', 'brannen-lake-estates' ); ?>
		</p>
		<?php else : ?>

		<div class="ble-avfeed__calendar">
			<div class="ble-avfeed__nav" hidden data-ble-avfeed-nav>
				<button type="button" class="ble-avfeed__nav-btn" data-ble-avfeed-prev aria-label="<?= \esc_attr__( 'Previous month', 'brannen-lake-estates' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 18l-6-6 6-6"/></svg>
				</button>
				<p class="ble-avfeed__range font-serif" aria-live="polite" data-ble-avfeed-range></p>
				<button type="button" class="ble-avfeed__nav-btn" data-ble-avfeed-next aria-label="<?= \esc_attr__( 'Next month', 'brannen-lake-estates' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 18l6-6-6-6"/></svg>
				</button>
			</div>

			<div class="ble-avfeed__months">
				<?php for ( $m = 0; $m < $months; $m++ ) :
					$month_start = $first->modify( "+{$m} month" );
					$month_label = \wp_date( 'F Y', $month_start->getTimestamp(), $tz );
					$days_in     = (int) $month_start->format( 't' );
					$lead        = (int) $month_start->format( 'w' ); // 0 = Sunday.
				?>
				<div class="ble-avfeed__month" data-ble-avfeed-month data-label="<?= \esc_attr( $month_label ); ?>">
					<h3 class="ble-avfeed__month-title font-serif"><?= \esc_html( $month_label ); ?></h3>
					<table class="ble-avfeed__grid">
						<caption class="screen-reader-text"><?= \esc_html( \sprintf( __( 'Availability for %s', 'brannen-lake-estates' ), $month_label ) ); ?></caption>
						<thead>
							<tr>
								<?php foreach ( $weekdays as $wd ) : ?>
								<th scope="col"><abbr title="<?= \esc_attr( $wd['long'] ); ?>"><?= \esc_html( \mb_substr( $wd['short'], 0, 2 ) ); ?></abbr></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
							<?php
							$cell = 0;
							for ( $b = 0; $b < $lead; $b++, $cell++ ) {
								echo '<td class="is-empty" aria-hidden="true"></td>';
							}
							for ( $day = 1; $day <= $days_in; $day++, $cell++ ) {
								if ( $cell > 0 && 0 === $cell % 7 ) {
									echo '</tr><tr>';
								}
								$date   = $month_start->setDate( (int) $month_start->format( 'Y' ), (int) $month_start->format( 'n' ), $day );
								$ymd    = $date->format( 'Y-m-d' );
								$past   = $date < $today;
								$status = $past ? 'past' : $status_for( $ymd );
								$label  = \wp_date( 'l, F j, Y', $date->getTimestamp(), $tz ) . ': ' . $labels[ $status ];
								\printf(
									'<td class="is-%1$s%2$s"><span class="ble-avfeed__day" aria-label="%3$s">%4$d</span></td>',
									\esc_attr( $status ),
									$date == $today ? ' is-today' : '', // phpcs:ignore WordPress.PHP.StrictComparisons
									\esc_attr( $label ),
									$day
								);
							}
							while ( 0 !== $cell % 7 ) {
								echo '<td class="is-empty" aria-hidden="true"></td>';
								$cell++;
							}
							?>
							</tr>
						</tbody>
					</table>
				</div>
				<?php endfor; ?>
			</div>
		</div>

		<ul class="ble-avfeed__legend font-sans" role="list" aria-label="<?= \esc_attr__( 'Calendar key', 'brannen-lake-estates' ); ?>">
			<li><span class="ble-avfeed__swatch is-available" aria-hidden="true"></span><?= \esc_html( $labels['available'] ); ?></li>
			<?php if ( $unavail_url ) : ?>
			<li><span class="ble-avfeed__swatch is-unavailable" aria-hidden="true"></span><?= \esc_html( $labels['unavailable'] ); ?></li>
			<?php endif; ?>
			<li><span class="ble-avfeed__swatch is-booked" aria-hidden="true"></span><?= \esc_html( $labels['booked'] ); ?></li>
		</ul>

		<?php endif; ?>

		<?php if ( $note ) : ?>
		<p class="ble-avfeed__note font-sans"><?= \esc_html( $note ); ?></p>
		<?php endif; ?>

	</div>
</section>
