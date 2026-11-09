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
			'instructions' => 'Paste the public Google Calendar embed URL. Go to Google Calendar Settings > your calendar > Integrate calendar > Customize > tick both the Booked and Unavailable calendars, then copy the URL from the src attribute of the embed code.',
		],
		[
			'key'           => 'field_ble_calendar_show_legend',
			'label'         => 'Show status key',
			'name'          => 'show_legend',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 1,
			'instructions'  => 'Shows Available / Unavailable / Booked swatches under the calendar.',
		],
		[
			'key'               => 'field_ble_calendar_booked_color',
			'label'             => 'Booked colour',
			'name'              => 'booked_color',
			'type'              => 'color_picker',
			'default_value'     => '#E67C73',
			'instructions'      => 'Match the colour of the "Booked" calendar in Google (default: Flamingo).',
			'wrapper'           => [ 'width' => '50' ],
			'conditional_logic' => [ [ [ 'field' => 'field_ble_calendar_show_legend', 'operator' => '==', 'value' => '1' ] ] ],
		],
		[
			'key'               => 'field_ble_calendar_unavailable_color',
			'label'             => 'Unavailable colour',
			'name'              => 'unavailable_color',
			'type'              => 'color_picker',
			'default_value'     => '#616161',
			'instructions'      => 'Match the colour of the "Unavailable" calendar in Google (default: Graphite).',
			'wrapper'           => [ 'width' => '50' ],
			'conditional_logic' => [ [ [ 'field' => 'field_ble_calendar_show_legend', 'operator' => '==', 'value' => '1' ] ] ],
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
