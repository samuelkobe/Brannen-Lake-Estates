<?php
/**
 * Welcome Intro — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_welcome_intro',
	'title'  => 'Welcome Intro',
	'fields' => [
		[
			'key'          => 'field_ble_welcome_intro_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. e.g. "Welcome" or "Est. 1970s".',
		],
		[
			'key'          => 'field_ble_welcome_intro_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading. e.g. "A Lakeside Legacy in the Heart of Nanaimo".',
		],
		[
			'key'          => 'field_ble_welcome_intro_body',
			'label'        => 'Body',
			'name'         => 'body',
			'type'         => 'textarea',
			'rows'         => 4,
			'instructions' => '2-3 sentences. Focus on what makes Brannen Lake Estates unique: family heritage, the lake, the setting.',
		],
		[
			'key'           => 'field_ble_welcome_intro_cta',
			'label'         => 'Button Link',
			'name'          => 'cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Optional. Select an internal page or paste a URL. e.g. "Our Story" linking to the About page.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/welcome-intro',
			],
		],
	],
] );
