<?php
/**
 * Alert — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_alert',
	'title'  => 'Alert',
	'fields' => [
		[
			'key'          => 'field_ble_alert_message',
			'label'        => 'Message',
			'name'         => 'message',
			'type'         => 'textarea',
			'rows'         => 2,
			'new_lines'    => '',
			'required'     => 1,
			'instructions' => 'Keep it to one or two sentences. Example: "Brannen Lake Estates is available by appointment only. Please contact us to schedule a visit."',
		],
		[
			'key'           => 'field_ble_alert_style',
			'label'         => 'Style',
			'name'          => 'style',
			'type'          => 'select',
			'choices'       => [
				'info'    => 'Info (champagne)',
				'warning' => 'Warning (charcoal)',
				'subtle'  => 'Subtle (sage)',
			],
			'default_value' => 'info',
			'return_format' => 'value',
			'wrapper'       => [ 'width' => '34' ],
		],
		[
			'key'           => 'field_ble_alert_dismissible',
			'label'         => 'Dismissible',
			'name'          => 'dismissible',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 0,
			'instructions'  => 'Visitors can close it. It stays closed until the message text changes.',
			'wrapper'       => [ 'width' => '33' ],
		],
		[
			'key'           => 'field_ble_alert_sticky',
			'label'         => 'Sticky',
			'name'          => 'sticky',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 0,
			'instructions'  => 'Stays pinned below the header while scrolling.',
			'wrapper'       => [ 'width' => '33' ],
		],
		[
			'key'           => 'field_ble_alert_show_cta',
			'label'         => 'Show button',
			'name'          => 'show_cta',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 0,
		],
		[
			'key'               => 'field_ble_alert_cta_text',
			'label'             => 'Button text',
			'name'              => 'cta_text',
			'type'              => 'text',
			'placeholder'       => 'Book a Tour',
			'wrapper'           => [ 'width' => '50' ],
			'conditional_logic' => [
				[
					[
						'field'    => 'field_ble_alert_show_cta',
						'operator' => '==',
						'value'    => '1',
					],
				],
			],
		],
		[
			'key'               => 'field_ble_alert_cta_url',
			'label'             => 'Button URL',
			'name'              => 'cta_url',
			'type'              => 'url',
			'placeholder'       => '/contact/',
			'wrapper'           => [ 'width' => '50' ],
			'conditional_logic' => [
				[
					[
						'field'    => 'field_ble_alert_show_cta',
						'operator' => '==',
						'value'    => '1',
					],
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/alert',
			],
		],
	],
] );
