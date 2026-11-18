<?php
/**
 * Venue Details — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_venue_details',
	'title'  => 'Venue Details',
	'fields' => [
		[
			'key'          => 'field_ble_venue_details_venue_name',
			'label'        => 'Venue Name',
			'name'         => 'venue_name',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Used in structured data (schema). e.g. "Lakeside Love Story".',
		],
		[
			'key'          => 'field_ble_venue_details_setting_type',
			'label'        => 'Setting Type',
			'name'         => 'setting_type',
			'type'         => 'select',
			'required'     => 1,
			'choices'      => [
				'Outdoor Lakeside' => 'Outdoor Lakeside',
				'Open Meadow'      => 'Open Meadow',
				'Forest Canopy'    => 'Forest Canopy',
				'Covered Barn'     => 'Covered Barn',
			],
			'instructions' => 'Displayed as a badge on the card and used in schema.',
		],
		[
			'key'          => 'field_ble_venue_details_capacity',
			'label'        => 'Guest Capacity',
			'name'         => 'capacity',
			'type'         => 'number',
			'required'     => 1,
			'instructions' => 'Maximum number of guests this setting accommodates.',
		],
		[
			'key'          => 'field_ble_venue_details_ceremony_types',
			'label'        => 'Event Types',
			'name'         => 'ceremony_types',
			'type'         => 'checkbox',
			'choices'      => [
				'Ceremony'  => 'Ceremony',
				'Reception' => 'Reception',
				'Elopement' => 'Elopement',
			],
			'instructions' => 'Select all event types this setting supports.',
		],
		[
			'key'          => 'field_ble_venue_details_features',
			'label'        => 'Setting Highlights',
			'name'         => 'features',
			'type'         => 'repeater',
			'required'     => 1,
			'min'          => 1,
			'max'          => 12,
			'layout'       => 'table',
			'button_label' => 'Add Feature',
			'sub_fields'   => [
				[
					'key'      => 'field_ble_venue_details_feature',
					'label'    => 'Feature',
					'name'     => 'feature',
					'type'     => 'text',
					'required' => 1,
				],
			],
		],
		[
			'key'          => 'field_ble_venue_details_description',
			'label'        => 'Schema Description',
			'name'         => 'description',
			'type'         => 'textarea',
			'rows'         => 3,
			'instructions' => 'Short description for search engines (not displayed on page). 1-2 sentences about this venue setting.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/venue-details',
			],
		],
	],
] );
