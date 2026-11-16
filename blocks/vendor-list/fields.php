<?php
/**
 * Vendor List — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_vendor_list',
	'title'  => 'Vendor List',
	'fields' => [
		[
			'key'   => 'field_ble_vendor_list_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'eyebrow',
			'type'  => 'text',
		],
		[
			'key'           => 'field_ble_vendor_list_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Preferred Vendors',
		],
		[
			'key'          => 'field_ble_vendor_list_body',
			'label'        => 'Body',
			'name'         => 'body',
			'type'         => 'textarea',
			'rows'         => 3,
			'new_lines'    => 'br',
			'instructions' => 'Optional introductory paragraph.',
		],
		[
			'key'           => 'field_ble_vendor_list_category_order',
			'label'         => 'Category order',
			'name'          => 'category_order',
			'type'          => 'select',
			'choices'       => [
				'custom' => 'Custom (Vendors > Vendor Types > Display order)',
				'alpha'  => 'Alphabetical',
			],
			'default_value' => 'custom',
			'wrapper'       => [ 'width' => '50' ],
		],
		[
			'key'           => 'field_ble_vendor_list_vendor_order',
			'label'         => 'Vendor order within each category',
			'name'          => 'vendor_order',
			'type'          => 'select',
			'choices'       => [
				'alpha'  => 'Alphabetical',
				'custom' => 'Custom (each vendor\'s Order number)',
			],
			'default_value' => 'alpha',
			'wrapper'       => [ 'width' => '50' ],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/vendor-list',
			],
		],
	],
] );
