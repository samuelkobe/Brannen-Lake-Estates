<?php
/**
 * Preferred Vendors — CPT, taxonomy, and ACF fields.
 *
 * Registers a private CPT for managing preferred vendors
 * and a hierarchical taxonomy for vendor types (Caterer, DJ, etc.).
 * ACF fields are attached to the CPT for additional vendor details.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Vendor custom post type.
 */
function register_vendor_cpt(): void {
	\register_post_type( 'ble_vendor', [
		'labels' => [
			'name'               => \__( 'Vendors', 'brannen-lake-estates' ),
			'singular_name'      => \__( 'Vendor', 'brannen-lake-estates' ),
			'add_new'            => \__( 'Add Vendor', 'brannen-lake-estates' ),
			'add_new_item'       => \__( 'Add New Vendor', 'brannen-lake-estates' ),
			'edit_item'          => \__( 'Edit Vendor', 'brannen-lake-estates' ),
			'new_item'           => \__( 'New Vendor', 'brannen-lake-estates' ),
			'view_item'          => \__( 'View Vendor', 'brannen-lake-estates' ),
			'search_items'       => \__( 'Search Vendors', 'brannen-lake-estates' ),
			'not_found'          => \__( 'No vendors found.', 'brannen-lake-estates' ),
			'not_found_in_trash' => \__( 'No vendors found in Trash.', 'brannen-lake-estates' ),
			'all_items'          => \__( 'All Vendors', 'brannen-lake-estates' ),
			'menu_name'          => \__( 'Vendors', 'brannen-lake-estates' ),
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-businessperson',
		'menu_position' => 25,
		'supports'     => [ 'title', 'page-attributes' ], // page-attributes = the Order box, used when the Vendor List block is set to Custom order.
		'has_archive'  => false,
		'rewrite'      => false,
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_vendor_cpt' );

/**
 * Register the Vendor Type taxonomy.
 */
function register_vendor_type_taxonomy(): void {
	\register_taxonomy( 'ble_vendor_type', 'ble_vendor', [
		'labels' => [
			'name'          => \__( 'Vendor Types', 'brannen-lake-estates' ),
			'singular_name' => \__( 'Vendor Type', 'brannen-lake-estates' ),
			'search_items'  => \__( 'Search Vendor Types', 'brannen-lake-estates' ),
			'all_items'     => \__( 'All Vendor Types', 'brannen-lake-estates' ),
			'edit_item'     => \__( 'Edit Vendor Type', 'brannen-lake-estates' ),
			'update_item'   => \__( 'Update Vendor Type', 'brannen-lake-estates' ),
			'add_new_item'  => \__( 'Add New Vendor Type', 'brannen-lake-estates' ),
			'new_item_name' => \__( 'New Vendor Type Name', 'brannen-lake-estates' ),
			'menu_name'     => \__( 'Vendor Types', 'brannen-lake-estates' ),
		],
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => false,
		'public'            => false,
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_vendor_type_taxonomy' );

/**
 * Register ACF fields for the Vendor CPT.
 */
function register_vendor_fields(): void {
	if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	\acf_add_local_field_group( [
		'key'    => 'group_ble_vendor',
		'title'  => 'Vendor Details',
		'fields' => [
			[
				'key'          => 'field_ble_vendor_website',
				'label'        => 'Website',
				'name'         => 'website',
				'type'         => 'url',
				'instructions' => 'Vendor\'s website. Shown as the "Visit website" button (dofollow).',
			],
			[
				'key'          => 'field_ble_vendor_booking_url',
				'label'        => 'Booking link',
				'name'         => 'booking_url',
				'type'         => 'url',
				'instructions' => 'Optional. A separate booking page (e.g. a third-party booking site). Shown as a "Book now" button (nofollow).',
			],
			[
				'key'          => 'field_ble_vendor_instagram',
				'label'        => 'Instagram',
				'name'         => 'instagram',
				'type'         => 'url',
				'placeholder'  => 'https://www.instagram.com/handle/',
				'instructions' => 'Optional. Full Instagram profile link. Shown as an "Instagram" button.',
			],
			[
				'key'          => 'field_ble_vendor_facebook',
				'label'        => 'Facebook',
				'name'         => 'facebook',
				'type'         => 'url',
				'placeholder'  => 'https://www.facebook.com/page/',
				'instructions' => 'Optional. Full Facebook page link. Shown as a "Facebook" button.',
			],
			[
				'key'          => 'field_ble_vendor_email',
				'label'        => 'Email',
				'name'         => 'email',
				'type'         => 'email',
				'instructions' => 'Optional contact email address.',
			],
			[
				'key'          => 'field_ble_vendor_phone',
				'label'        => 'Phone',
				'name'         => 'phone',
				'type'         => 'text',
				'instructions' => 'Optional display phone number.',
			],
			[
				'key'          => 'field_ble_vendor_description',
				'label'        => 'Description',
				'name'         => 'description',
				'type'         => 'wysiwyg',
				'tabs'         => 'visual',
				'toolbar'      => 'ble_text_only',
				'media_upload' => 0,
				'delay'        => 1,
				'instructions' => 'One or two sentences about this vendor. Bold, italic and lists only: put links in the Website or Booking link fields.',
			],
			[
				'key'           => 'field_ble_vendor_logo',
				'label'         => 'Logo / Photo',
				'name'          => 'logo',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'thumbnail',
				'instructions'  => 'Optional vendor logo or photo. Displayed as a circle.',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'ble_vendor',
				],
			],
		],
	] );
}
\add_action( 'acf/init', __NAMESPACE__ . '\\register_vendor_fields' );

/**
 * Text-only WYSIWYG toolbar for vendor descriptions: no link button, so every
 * card keeps its links in one consistent action row.
 *
 * @param array $toolbars Toolbars keyed by name.
 * @return array
 */
function vendor_wysiwyg_toolbar( array $toolbars ): array {
	$toolbars['ble_text_only'] = [
		1 => [ 'bold', 'italic', 'bullist', 'numlist', 'undo', 'redo' ],
	];
	return $toolbars;
}
\add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\\vendor_wysiwyg_toolbar' );

/**
 * Vendor Type ordering: a "Display order" number on each type, used by the
 * Vendor List block when its category order is set to Custom.
 */
function vendor_type_order_field(): void {
	if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	\acf_add_local_field_group( [
		'key'      => 'group_ble_vendor_type_order',
		'title'    => 'Vendor Type Order',
		'fields'   => [
			[
				'key'          => 'field_ble_vendor_type_order',
				'label'        => 'Display order',
				'name'         => 'display_order',
				'type'         => 'number',
				'min'          => 0,
				'step'         => 1,
				'instructions' => 'Lower numbers show first on the Preferred Vendors page (when the Vendor List block is set to Custom order). Types without a number go last, A–Z.',
			],
		],
		'location' => [
			[
				[
					'param'    => 'taxonomy',
					'operator' => '==',
					'value'    => 'ble_vendor_type',
				],
			],
		],
	] );
}
\add_action( 'acf/init', __NAMESPACE__ . '\\vendor_type_order_field' );

/**
 * Show the order numbers in the admin lists so they're easy to scan.
 */
\add_filter( 'manage_edit-ble_vendor_type_columns', function ( array $cols ): array {
	$cols['ble_order'] = \__( 'Order', 'brannen-lake-estates' );
	return $cols;
} );
\add_filter( 'manage_ble_vendor_type_custom_column', function ( $out, string $col, int $term_id ) {
	if ( 'ble_order' === $col ) {
		$v = \get_term_meta( $term_id, 'display_order', true );
		return '' === $v ? '—' : \esc_html( $v );
	}
	return $out;
}, 10, 3 );
\add_filter( 'manage_ble_vendor_posts_columns', function ( array $cols ): array {
	$cols['ble_order'] = \__( 'Order', 'brannen-lake-estates' );
	return $cols;
} );
\add_action( 'manage_ble_vendor_posts_custom_column', function ( string $col, int $post_id ): void {
	if ( 'ble_order' === $col ) {
		echo \esc_html( (string) \get_post_field( 'menu_order', $post_id ) );
	}
}, 10, 2 );
\add_filter( 'manage_edit-ble_vendor_sortable_columns', function ( array $cols ): array {
	$cols['ble_order'] = 'menu_order';
	return $cols;
} );
