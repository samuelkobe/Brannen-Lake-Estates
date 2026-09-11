<?php
/**
 * Contact Details — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_contact_details',
	'title'  => 'Contact Details',
	'fields' => [
		[
			'key'           => 'field_ble_contact_details_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Find Us',
		],
		[
			'key'          => 'field_ble_contact_details_map_embed_url',
			'label'        => 'Map Embed URL',
			'name'         => 'map_embed_url',
			'type'         => 'url',
			'required'     => 1,
			'instructions' => 'Go to Google Maps, click "Share", then "Embed a map". Copy the src URL from the iframe code (starts with https://www.google.com/maps/embed).',
		],
		[
			'key'          => 'field_ble_contact_details_address',
			'label'        => 'Address',
			'name'         => 'address',
			'type'         => 'textarea',
			'rows'         => 2,
			'new_lines'    => 'br',
			'instructions' => 'Displayed address. e.g. "6650 Doumont Rd, Nanaimo, BC V9T 6H1".',
		],
		[
			'key'          => 'field_ble_contact_details_phone',
			'label'        => 'Phone Number',
			'name'         => 'phone',
			'type'         => 'text',
			'instructions' => 'Used for the tel: link. e.g. "+12506671407".',
		],
		[
			'key'          => 'field_ble_contact_details_phone_display',
			'label'        => 'Phone Display',
			'name'         => 'phone_display',
			'type'         => 'text',
			'instructions' => 'Formatted phone number shown to visitors. e.g. "250-667-1407".',
		],
		[
			'key'          => 'field_ble_contact_details_email',
			'label'        => 'Email',
			'name'         => 'email',
			'type'         => 'email',
			'instructions' => 'Displayed email and used for the mailto: link.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/contact-details',
			],
		],
	],
] );
