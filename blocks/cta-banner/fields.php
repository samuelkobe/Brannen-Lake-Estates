<?php
/**
 * CTA Banner — ACF field group registration.
 *
 * Registering fields in PHP keeps them in version control.
 * Do not duplicate these in the WP Admin field group UI.
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
			'key'          => 'field_ble_cta_banner_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'The main headline. Displayed in Cormorant Garamond.',
		],
		[
			'key'          => 'field_ble_cta_banner_subheading',
			'label'        => 'Subheading',
			'name'         => 'subheading',
			'type'         => 'textarea',
			'rows'         => 3,
			'instructions' => 'Optional supporting text beneath the heading.',
		],
		[
			'key'          => 'field_ble_cta_banner_primary_label',
			'label'        => 'Primary Button Label',
			'name'         => 'primary_cta_label',
			'type'         => 'text',
			'instructions' => 'e.g. "Inquire Now" or "Check Availability".',
		],
		[
			'key'  => 'field_ble_cta_banner_primary_url',
			'label' => 'Primary Button URL',
			'name' => 'primary_cta_url',
			'type' => 'url',
		],
		[
			'key'          => 'field_ble_cta_banner_secondary_label',
			'label'        => 'Secondary Button Label',
			'name'         => 'secondary_cta_label',
			'type'         => 'text',
			'instructions' => 'Optional. Displayed as a quieter text link.',
		],
		[
			'key'  => 'field_ble_cta_banner_secondary_url',
			'label' => 'Secondary Button URL',
			'name' => 'secondary_cta_url',
			'type' => 'url',
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
