<?php
/**
 * Nearby Accommodations — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_nearby_accommodations',
	'title'  => 'Nearby Accommodations',
	'fields' => [
		[
			'key'   => 'field_ble_accommodations_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'eyebrow',
			'type'  => 'text',
		],
		[
			'key'           => 'field_ble_accommodations_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Nearby Accommodations',
		],
		[
			'key'        => 'field_ble_accommodations_items',
			'label'      => 'Accommodations',
			'name'       => 'accommodations',
			'type'       => 'repeater',
			'layout'     => 'block',
			'button_label' => 'Add Accommodation',
			'sub_fields' => [
				[
					'key'      => 'field_ble_accommodation_name',
					'label'    => 'Name',
					'name'     => 'name',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'           => 'field_ble_accommodation_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Photo of the property.',
				],
				[
					'key'          => 'field_ble_accommodation_description',
					'label'        => 'Description',
					'name'         => 'description',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => 'br',
					'instructions' => 'One or two sentences about this lodging.',
				],
				[
					'key'          => 'field_ble_accommodation_distance',
					'label'        => 'Distance',
					'name'         => 'distance',
					'type'         => 'text',
					'instructions' => 'e.g. "10 min drive" or "4.2 km"',
				],
				[
					'key'          => 'field_ble_accommodation_website_url',
					'label'        => 'Website URL',
					'name'         => 'website_url',
					'type'         => 'url',
					'instructions' => 'Link to the lodging\'s website.',
				],
				[
					'key'          => 'field_ble_accommodation_map_url',
					'label'        => 'Map URL',
					'name'         => 'map_url',
					'type'         => 'url',
					'instructions' => 'Optional Google Maps link to the lodging.',
				],
				[
					'key'          => 'field_ble_accommodation_directions_url',
					'label'        => 'Directions URL',
					'name'         => 'directions_url',
					'type'         => 'url',
					'instructions' => 'Optional pre-filled Google Maps directions link (lodging to venue).',
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/nearby-accommodations',
			],
		],
	],
] );
