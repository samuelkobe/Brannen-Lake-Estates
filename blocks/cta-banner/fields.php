<?php
/**
 * CTA Banner — ACF field group registration.
 *
 * Full-width call-to-action band. Eyebrow, heading, body text,
 * and up to two buttons using ACF link fields for full
 * accessibility control (url, title, target).
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_cta_banner',
	'title'  => 'CTA Banner',
	'fields' => [
		[
			'key'          => 'field_ble_cta_banner_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. Optional.',
		],
		[
			'key'          => 'field_ble_cta_banner_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'The main headline.',
		],
		[
			'key'          => 'field_ble_cta_banner_subheading',
			'label'        => 'Body Text',
			'name'         => 'subheading',
			'type'         => 'textarea',
			'rows'         => 3,
			'instructions' => 'Optional supporting text beneath the heading.',
		],
		[
			'key'           => 'field_ble_cta_banner_primary_cta',
			'label'         => 'Primary Button',
			'name'          => 'primary_cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Main call to action. Set the Title field for the aria-label.',
		],
		[
			'key'           => 'field_ble_cta_banner_secondary_cta',
			'label'         => 'Secondary Button',
			'name'          => 'secondary_cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Optional. Displayed as a quieter text link. Set the Title field for the aria-label.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/cta-banner',
			],
		],
	],
] );
