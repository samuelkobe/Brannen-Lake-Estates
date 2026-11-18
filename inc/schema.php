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
 *   - OfferCatalog (pages with a Pricing block): one Offer per package card
 *   - ItemList (pages with a Vendor List block): published preferred vendors
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
		'description' => 'A private 21-acre lakefront wedding venue in Nanaimo, BC, with four ceremony settings for elopements, micro weddings and full celebrations of up to 120 guests. Visits by appointment only.',
		'image'       => \get_theme_file_uri( 'assets/images/Brannen-Lake-Estates-Logo.png' ),
		'priceRange'  => '$1,500 to $7,500 + GST',
		'maximumAttendeeCapacity' => 120,
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
	if ( \is_page( [ 'contact', 'contact-us' ] ) ) {
		$graph[] = [
			'@type'       => 'ContactPage',
			'@id'         => \get_permalink() . '#webpage',
			'name'        => \get_the_title(),
			'url'         => \get_permalink(),
			'mainEntity'  => [ '@id' => $home_url . '#business' ],
			'isPartOf'    => [ '@id' => $home_url . '#website' ],
		];
	}

	// ── OfferCatalog + vendor ItemList (block-driven) ────────────────────
	if ( \is_singular() ) {
		foreach ( \parse_blocks( (string) \get_post_field( 'post_content', \get_queried_object_id() ) ) as $block ) {
			if ( 'acf/pricing' === ( $block['blockName'] ?? '' ) && ( $catalog = build_offer_catalog( $block['attrs']['data'] ?? [] ) ) ) {
				$graph[] = $catalog;
			}
			if ( 'acf/vendor-list' === ( $block['blockName'] ?? '' ) && ( $list = build_vendor_item_list() ) ) {
				$graph[] = $list;
			}
		}
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

/**
 * OfferCatalog from a Pricing block's flat ACF data: one Offer per card item
 * whose price parses to a number. List-layout add-ons are included too.
 *
 * @param array $d Block attrs data (flat ACF keys).
 * @return array|null
 */
function build_offer_catalog( array $d ): ?array {
	$home   = \home_url( '/' );
	$offers = [];
	$n_sec  = (int) ( $d['sections'] ?? 0 );
	for ( $s = 0; $s < $n_sec; $s++ ) {
		$n_items = (int) ( $d[ "sections_{$s}_items" ] ?? 0 );
		for ( $i = 0; $i < $n_items; $i++ ) {
			$k     = "sections_{$s}_items_{$i}_";
			$name  = \trim( (string) ( $d[ $k . 'name' ] ?? '' ) );
			$price = \preg_replace( '/[^\d.]/', '', (string) ( $d[ $k . 'price' ] ?? '' ) );
			if ( '' === $name || '' === $price ) {
				continue;
			}
			$from  = \stripos( (string) $d[ $k . 'price' ], 'from' ) !== false;
			$offer = [
				'@type'         => 'Offer',
				'name'          => $name,
				'priceCurrency' => 'CAD',
				'itemOffered'   => [ '@type' => 'Service', 'name' => $name, 'provider' => [ '@id' => $home . '#business' ] ],
				'priceSpecification' => [
					'@type'                 => 'PriceSpecification',
					( $from ? 'minPrice' : 'price' ) => (float) $price,
					'priceCurrency'         => 'CAD',
					'valueAddedTaxIncluded' => false,
				],
			];
			if ( ! $from ) {
				$offer['price'] = (float) $price;
			}
			if ( ! empty( $d[ $k . 'description' ] ) ) {
				$offer['description'] = \wp_strip_all_tags( (string) $d[ $k . 'description' ] );
			}
			$link = $d[ $k . 'link' ] ?? null;
			if ( \is_array( $link ) && ! empty( $link['url'] ) ) {
				$offer['url'] = $link['url'];
			}
			$offers[] = $offer;
		}
	}
	if ( ! $offers ) {
		return null;
	}
	return [
		'@type'           => 'OfferCatalog',
		'@id'             => \get_permalink() . '#packages',
		'name'            => 'Wedding packages at Brannen Lake Estates',
		'provider'        => [ '@id' => $home . '#business' ],
		'itemListElement' => $offers,
	];
}

/**
 * ItemList of published preferred vendors, alphabetical.
 *
 * @return array|null
 */
function build_vendor_item_list(): ?array {
	$vendors = \get_posts( [ 'post_type' => 'ble_vendor', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ] );
	$items   = [];
	$seen    = [];
	foreach ( $vendors as $v ) {
		$name = \html_entity_decode( \get_the_title( $v ), ENT_QUOTES );
		if ( isset( $seen[ $name ] ) ) {
			continue; // Same business listed under two types.
		}
		$seen[ $name ] = true;
		$biz = [ '@type' => 'LocalBusiness', 'name' => $name ];
		$url = \get_field( 'website', $v->ID ) ?: \get_field( 'instagram', $v->ID ) ?: \get_field( 'facebook', $v->ID );
		if ( $url ) {
			$biz['url'] = $url;
		}
		if ( $tel = \get_field( 'phone', $v->ID ) ) {
			$biz['telephone'] = $tel;
		}
		$type = \wp_get_post_terms( $v->ID, 'ble_vendor_type', [ 'fields' => 'names' ] );
		if ( $type && ! \is_wp_error( $type ) ) {
			$biz['description'] = \html_entity_decode( $type[0], ENT_QUOTES );
		}
		$items[] = [ '@type' => 'ListItem', 'position' => \count( $items ) + 1, 'item' => $biz ];
	}
	if ( ! $items ) {
		return null;
	}
	return [
		'@type'           => 'ItemList',
		'@id'             => \get_permalink() . '#vendors',
		'name'            => 'Brannen Lake Estates preferred wedding vendors',
		'numberOfItems'   => \count( $items ),
		'itemListElement' => $items,
	];
}
