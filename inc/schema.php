<?php
/**
 * Hand-coded JSON-LD schema output.
 *
 * SEOPress free does not include a schema editor, so structured data is
 * output here via wp_head. If SEOPress is ever upgraded to Pro and its schema
 * editor is used, disable this file's output to avoid duplicating markup.
 *
 * Outputs:
 *   - @graph with EventVenue/LocalBusiness + WebSite (every page)
 *   - BreadcrumbList (all pages except homepage)
 *   - ContactPage (contact page only)
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
	$home_url = \home_url( '/' );

	// ── EventVenue / LocalBusiness ───────────────────────────────────────
	$business = [
		'@type'      => [ 'EventVenue', 'LocalBusiness' ],
		'@id'        => $home_url . '#business',
		'name'       => 'Brannen Lake Estates',
		'legalName'  => 'Brannen Lake Estates Ltd.',
		'url'        => $home_url,
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
	];

	// ── WebSite ─────────────────────────────────────────────────────────
	$website = [
		'@type'     => 'WebSite',
		'@id'       => $home_url . '#website',
		'name'      => 'Brannen Lake Estates',
		'url'       => $home_url,
		'publisher' => [ '@id' => $home_url . '#business' ],
	];

	$graph = [ $business, $website ];

	// ── BreadcrumbList (non-homepage) ────────────────────────────────────
	if ( ! \is_front_page() && \is_singular() ) {
		$breadcrumbs = build_breadcrumb_list();
		if ( $breadcrumbs ) {
			$graph[] = $breadcrumbs;
		}
	}

	// ── ContactPage ─────────────────────────────────────────────────────
	if ( \is_page( 'contact' ) ) {
		$graph[] = [
			'@type'       => 'ContactPage',
			'@id'         => \get_permalink() . '#webpage',
			'name'        => \get_the_title(),
			'url'         => \get_permalink(),
			'mainEntity'  => [ '@id' => $home_url . '#business' ],
			'isPartOf'    => [ '@id' => $home_url . '#website' ],
		];
	}

	$schema = [
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	];

	\printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		\wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
	);
}
\add_action( 'wp_head', __NAMESPACE__ . '\\output_schema' );

/**
 * Build a BreadcrumbList schema from the current page's ancestors.
 *
 * @return array|null BreadcrumbList schema or null if not applicable.
 */
function build_breadcrumb_list(): ?array {
	$post = \get_post();
	if ( ! $post ) {
		return null;
	}

	$items = [];

	// Home is always the first crumb.
	$items[] = [
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => 'Home',
		'item'     => \home_url( '/' ),
	];

	// Ancestor pages (ordered from top-level parent down).
	$ancestors = \array_reverse( \get_ancestors( $post->ID, 'page', 'post_type' ) );

	$position = 2;
	foreach ( $ancestors as $ancestor_id ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => \get_the_title( $ancestor_id ),
			'item'     => \get_permalink( $ancestor_id ),
		];
		$position++;
	}

	// Current page (no item URL per Google guidelines for the last crumb).
	$items[] = [
		'@type'    => 'ListItem',
		'position' => $position,
		'name'     => \get_the_title(),
	];

	return [
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	];
}
