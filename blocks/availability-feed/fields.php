<?php
/**
 * Availability Calendar (Private Feed) — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_availability_feed',
	'title'  => 'Availability Calendar (Private Feed)',
	'fields' => [
		[
			'key'           => 'field_ble_avfeed_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Open Dates',
			'wrapper'       => [ 'width' => '40' ],
		],
		[
			'key'           => 'field_ble_avfeed_tagline',
			'label'         => 'Tagline',
			'name'          => 'tagline',
			'type'          => 'text',
			'default_value' => 'Dates marked Booked or Unavailable are taken. Everything else is open.',
			'wrapper'       => [ 'width' => '60' ],
		],
		[
			'key'          => 'field_ble_avfeed_booked',
			'label'        => 'Booked calendar: secret iCal address',
			'name'         => 'booked_feed',
			'type'         => 'password',
			'instructions' => 'Google Calendar > Settings > the Booked calendar > Integrate calendar > "Secret address in iCal format". The calendar can stay private. Only dates are read; titles and details are never shown.',
		],
		[
			'key'          => 'field_ble_avfeed_unavailable',
			'label'        => 'Unavailable calendar: secret iCal address',
			'name'         => 'unavailable_feed',
			'type'         => 'password',
			'instructions' => 'Same as above, from the Unavailable calendar. Optional.',
		],
		[
			'key'           => 'field_ble_avfeed_months',
			'label'         => 'Months to show',
			'name'          => 'months',
			'type'          => 'number',
			'default_value' => 18,
			'min'           => 3,
			'max'           => 24,
			'instructions'  => 'How far ahead visitors can browse, starting from this month.',
			'wrapper'       => [ 'width' => '50' ],
		],
		[
			'key'           => 'field_ble_avfeed_note',
			'label'         => 'Note under the calendar',
			'name'          => 'note',
			'type'          => 'text',
			'default_value' => 'Calendar is updated regularly. Contact us to confirm availability for your date.',
			'wrapper'       => [ 'width' => '50' ],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/availability-feed',
			],
		],
	],
] );
