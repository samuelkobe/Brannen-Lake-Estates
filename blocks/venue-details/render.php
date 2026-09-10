<?php
/**
 * Venue Details — render template.
 *
 * Structured data card showing capacity, setting type, event types,
 * and feature highlights. Outputs EventVenue JSON-LD linked to the
 * parent Brannen Lake Estates schema via containedInPlace.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$venue_name     = \get_field( 'venue_name' );
$setting_type   = \get_field( 'setting_type' );
$capacity       = \get_field( 'capacity' );
$ceremony_types = \get_field( 'ceremony_types' );
$features       = \get_field( 'features' );
$description    = \get_field( 'description' );

if ( ! $venue_name ) {
	return;
}

// Event type icons (inline SVG).
$type_icons = [
	'Ceremony'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
	'Reception' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
	'Elopement' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
];
?>
<section class="ble-venue-details px-6">
	<div class="max-w-4xl mx-auto">
		<div class="ble-venue-details__card" data-ble-reveal>

			<?php /* ── Setting type badge ─────────────────────────────────────── */ ?>
			<p class="ble-venue-details__type">
				<?= \esc_html( $setting_type ); ?>
			</p>

			<?php /* ── Stats row ──────────────────────────────────────────────── */ ?>
			<div class="ble-venue-details__stats">

				<?php if ( $capacity ) : ?>
				<div class="ble-venue-details__stat">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
						<circle cx="9" cy="7" r="4"/>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"/>
					</svg>
					<span>Up to <?= \esc_html( $capacity ); ?> Guests</span>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $ceremony_types ) ) : ?>
					<?php foreach ( (array) $ceremony_types as $type ) : ?>
					<div class="ble-venue-details__stat">
						<?php if ( isset( $type_icons[ $type ] ) ) : ?>
							<span aria-hidden="true"><?= $type_icons[ $type ]; ?></span>
						<?php endif; ?>
						<span><?= \esc_html( $type ); ?></span>
					</div>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>

			<?php /* ── Divider ────────────────────────────────────────────────── */ ?>
			<hr class="ble-venue-details__divider">

			<?php /* ── Features grid ──────────────────────────────────────────── */ ?>
			<?php if ( ! empty( $features ) ) : ?>
			<h3 class="ble-venue-details__features-heading">Setting Highlights</h3>
			<ul class="ble-venue-details__features">
				<?php foreach ( $features as $item ) : ?>
				<li class="ble-venue-details__feature">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12"/>
					</svg>
					<span><?= \esc_html( $item['feature'] ); ?></span>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		</div>
	</div>
</section>

<?php
// EventVenue structured data for this specific venue setting.
$venue_schema = [
	'@context'   => 'https://schema.org',
	'@type'      => 'EventVenue',
	'@id'        => \home_url( '/' . sanitize_title( $venue_name ) . '/#venue' ),
	'name'       => $venue_name . ' at Brannen Lake Estates',
	'url'        => \get_permalink(),
	'maximumAttendeeCapacity' => (int) $capacity,
	'address'    => [
		'@type'           => 'PostalAddress',
		'streetAddress'   => '6650 Doumont Rd',
		'addressLocality' => 'Nanaimo',
		'addressRegion'   => 'BC',
		'postalCode'      => 'V9T 6H1',
		'addressCountry'  => 'CA',
	],
	'geo' => [
		'@type'     => 'GeoCoordinates',
		'latitude'  => '49.2163968322082',
		'longitude' => '-124.06534862458074',
	],
	'containedInPlace' => [
		'@type' => 'EventVenue',
		'@id'   => \home_url( '/#venue' ),
		'name'  => 'Brannen Lake Estates',
	],
];

if ( $description ) {
	$venue_schema['description'] = $description;
}

if ( ! empty( $features ) ) {
	$venue_schema['amenityFeature'] = \array_map(
		fn( $f ) => [
			'@type' => 'LocationFeatureSpecification',
			'name'  => $f['feature'],
		],
		$features
	);
}

if ( $setting_type ) {
	$venue_schema['additionalProperty'] = [
		[
			'@type' => 'PropertyValue',
			'name'  => 'Setting Type',
			'value' => $setting_type,
		],
	];
}
?>
<script type="application/ld+json"><?= \wp_json_encode( $venue_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
