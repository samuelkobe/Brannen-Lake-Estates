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
