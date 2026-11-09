<?php
/**
 * Availability Calendar — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_availability_calendar',
	'title'  => 'Availability Calendar',
	'fields' => [
		[
			'key'           => 'field_ble_calendar_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Availability',
		],
		[
			'key'          => 'field_ble_calendar_tagline',
			'label'        => 'Tagline',
			'name'         => 'tagline',
			'type'         => 'textarea',
			'rows'         => 2,
			'new_lines'    => 'br',
			'instructions' => 'Optional description below the heading.',
		],
		[
			'key'          => 'field_ble_calendar_embed_url',
			'label'        => 'Google Calendar Embed URL',
			'name'         => 'calendar_embed_url',
			'type'         => 'url',
			'required'     => 1,
			'instructions' => 'Paste the public Google Calendar embed URL. Go to Google Calendar Settings > your calendar > Integrate calendar > copy the "Embed code" URL from the src attribute.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/availability-calendar',
			],
		],
	],
] );
