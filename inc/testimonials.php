<?php
/**
 * Testimonials — CPT, taxonomy, and ACF fields.
 *
 * Testimonials are managed in one place (WP admin › Testimonials) and pulled
 * into pages by the Testimonial block, filtered by Testimonial Type:
 * "Venue" for the estate as a whole, plus one type per venue package.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Testimonial custom post type.
 */
function register_testimonial_cpt(): void {
	\register_post_type( 'ble_testimonial', [
		'labels' => [
			'name'               => \__( 'Testimonials', 'brannen-lake-estates' ),
			'singular_name'      => \__( 'Testimonial', 'brannen-lake-estates' ),
			'add_new'            => \__( 'Add Testimonial', 'brannen-lake-estates' ),
			'add_new_item'       => \__( 'Add New Testimonial', 'brannen-lake-estates' ),
			'edit_item'          => \__( 'Edit Testimonial', 'brannen-lake-estates' ),
			'new_item'           => \__( 'New Testimonial', 'brannen-lake-estates' ),
			'search_items'       => \__( 'Search Testimonials', 'brannen-lake-estates' ),
			'not_found'          => \__( 'No testimonials found.', 'brannen-lake-estates' ),
			'not_found_in_trash' => \__( 'No testimonials found in Trash.', 'brannen-lake-estates' ),
			'all_items'          => \__( 'All Testimonials', 'brannen-lake-estates' ),
			'menu_name'          => \__( 'Testimonials', 'brannen-lake-estates' ),
		],
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-format-quote',
		'menu_position' => 26,
		'supports'      => [ 'title', 'page-attributes' ], // Title = who it's from. Order box = Custom order.
		'has_archive'   => false,
		'rewrite'       => false,
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_testimonial_cpt' );

/**
 * Register the Testimonial Type taxonomy.
 */
function register_testimonial_type_taxonomy(): void {
	\register_taxonomy( 'ble_testimonial_type', 'ble_testimonial', [
		'labels' => [
			'name'          => \__( 'Testimonial Types', 'brannen-lake-estates' ),
			'singular_name' => \__( 'Testimonial Type', 'brannen-lake-estates' ),
			'search_items'  => \__( 'Search Testimonial Types', 'brannen-lake-estates' ),
			'all_items'     => \__( 'All Testimonial Types', 'brannen-lake-estates' ),
			'edit_item'     => \__( 'Edit Testimonial Type', 'brannen-lake-estates' ),
			'update_item'   => \__( 'Update Testimonial Type', 'brannen-lake-estates' ),
			'add_new_item'  => \__( 'Add New Testimonial Type', 'brannen-lake-estates' ),
			'new_item_name' => \__( 'New Testimonial Type Name', 'brannen-lake-estates' ),
			'menu_name'     => \__( 'Testimonial Types', 'brannen-lake-estates' ),
		],
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => false,
		'public'            => false,
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_testimonial_type_taxonomy' );

/**
 * Register ACF fields for the Testimonial CPT.
 */
function register_testimonial_fields(): void {
	if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	\acf_add_local_field_group( [
		'key'      => 'group_ble_testimonial_post',
		'title'    => 'Testimonial Details',
		'fields'   => [
			[
				'key'          => 'field_ble_testimonial_post_quote',
				'label'        => 'Quote',
				'name'         => 'quote',
				'type'         => 'textarea',
				'rows'         => 6,
				'required'     => 1,
				'new_lines'    => '',
				'instructions' => 'The testimonial, as the couple wrote it. The title above is the name shown under the quote, e.g. "Sarah & James M."',
			],
			[
				'key'          => 'field_ble_testimonial_post_date',
				'label'        => 'Date',
				'name'         => 'date',
				'type'         => 'text',
				'instructions' => 'Optional. Shown after the name, e.g. "September 2026" or "Summer 2026".',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'ble_testimonial',
				],
			],
		],
		'position' => 'acf_after_title',
	] );
}
\add_action( 'acf/init', __NAMESPACE__ . '\\register_testimonial_fields' );

/**
 * Admin list: show a short quote preview and the Order number.
 */
\add_filter( 'manage_ble_testimonial_posts_columns', function ( array $cols ): array {
	$out = [];
	foreach ( $cols as $k => $v ) {
		$out[ $k ] = $v;
		if ( 'title' === $k ) {
			$out['ble_quote'] = \__( 'Quote', 'brannen-lake-estates' );
		}
	}
	$out['ble_order'] = \__( 'Order', 'brannen-lake-estates' );
	return $out;
} );
\add_action( 'manage_ble_testimonial_posts_custom_column', function ( string $col, int $post_id ): void {
	if ( 'ble_quote' === $col ) {
		echo \esc_html( \wp_trim_words( (string) \get_post_meta( $post_id, 'quote', true ), 18 ) );
	} elseif ( 'ble_order' === $col ) {
		echo \esc_html( (string) \get_post_field( 'menu_order', $post_id ) );
	}
}, 10, 2 );
\add_filter( 'manage_edit-ble_testimonial_sortable_columns', function ( array $cols ): array {
	$cols['ble_order'] = 'menu_order';
	return $cols;
} );

/**
 * Get testimonials for the Testimonial block.
 *
 * @param string $source 'all' or 'types'.
 * @param int[]  $types  Testimonial Type term IDs (when $source is 'types').
 * @param string $order  'random', 'newest' or 'custom'.
 * @return array<int, array{quote:string,name:string,date:string}>
 */
function get_testimonials( string $source, array $types, string $order ): array {
	$args = [
		'post_type'      => 'ble_testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'no_found_rows'  => true,
		'orderby'        => 'custom' === $order ? [ 'menu_order' => 'ASC', 'date' => 'DESC' ] : 'date',
		'order'          => 'DESC',
	];
	if ( 'types' === $source ) {
		$types = array_filter( array_map( 'intval', $types ) );
		if ( ! $types ) {
			return [];
		}
		$args['tax_query'] = [
			[
				'taxonomy' => 'ble_testimonial_type',
				'field'    => 'term_id',
				'terms'    => $types,
			],
		];
	}

	$out = [];
	foreach ( \get_posts( $args ) as $post ) {
		$quote = trim( (string) \get_post_meta( $post->ID, 'quote', true ) );
		if ( '' === $quote ) {
			continue;
		}
		$out[] = [
			'quote' => $quote,
			'name'  => \get_the_title( $post ),
			'date'  => (string) \get_post_meta( $post->ID, 'date', true ),
		];
	}
	return $out;
}
