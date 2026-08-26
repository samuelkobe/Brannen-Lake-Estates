<?php
/**
 * Hand-coded JSON-LD schema output.
 *
 * SEOPress free does not include a schema editor, so structured data is
 * output here via wp_head. If SEOPress is ever upgraded to Pro and its schema
 * editor is used, disable this file's output to avoid duplicating markup.
 *
 * Phase 2: populate with real NAP, coordinates, and opening hours.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output JSON-LD structured data in <head>.
 */
function output_schema(): void {
	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => [ 'EventVenue', 'LocalBusiness' ],
		'name'     => \get_bloginfo( 'name' ),
		'url'      => \home_url( '/' ),

		// Phase 2: add real NAP and venue details.
		// 'address' => [
		// 	'@type'           => 'PostalAddress',
		// 	'streetAddress'   => '',
		// 	'addressLocality' => 'Nanaimo',
		// 	'addressRegion'   => 'BC',
		// 	'postalCode'      => '',
		// 	'addressCountry'  => 'CA',
		// ],
		// 'telephone'    => '',
		// 'geo' => [
		// 	'@type'     => 'GeoCoordinates',
		// 	'latitude'  => '',
		// 	'longitude' => '',
		// ],
		// 'openingHoursSpecification' => [],
		// 'image' => [],
		// 'sameAs' => [],
	];

	\printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		\wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
	);
}
\add_action( 'wp_head', __NAMESPACE__ . '\\output_schema' );
