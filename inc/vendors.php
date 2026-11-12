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
		'supports'     => [ 'title' ],
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
				'instructions' => 'Vendor\'s website URL.',
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
				'toolbar'      => 'basic',
				'media_upload' => 0,
				'delay'        => 1,
				'instructions' => 'One or two sentences about this vendor. Bold, italic and links are supported.',
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
