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
		'@context'   => 'https://schema.org',
		'@type'      => [ 'EventVenue', 'LocalBusiness' ],
		'name'       => 'Brannen Lake Estates',
		'legalName'  => 'Brannen Lake Estates Ltd.',
		'url'        => \home_url( '/' ),
		'email'      => 'info@brannenlakeestates.ca',
		'telephone'  => '+12506671407',
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
		'sameAs' => [
			'https://www.instagram.com/brannenlakeestates/',
			'https://www.facebook.com/Brannenlakeestates/',
		],
		// TODO: add openingHoursSpecification and image once confirmed with client.
	];

	\printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		\wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
	);
}
\add_action( 'wp_head', __NAMESPACE__ . '\\output_schema' );
