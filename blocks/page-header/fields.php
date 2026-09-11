<?php
/**
 * Page Header — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_page_header',
	'title'  => 'Page Header',
	'fields' => [
		[
			'key'      => 'field_ble_page_header_heading',
			'label'    => 'Heading',
			'name'     => 'heading',
			'type'     => 'text',
			'required' => 1,
		],
		[
			'key'           => 'field_ble_page_header_alignment',
			'label'         => 'Alignment',
			'name'          => 'alignment',
			'type'          => 'radio',
			'choices'       => [
				'center' => 'Center',
				'left'   => 'Left',
			],
			'default_value' => 'center',
			'layout'        => 'horizontal',
		],
		[
			'key'          => 'field_ble_page_header_tagline',
			'label'        => 'Tagline',
			'name'         => 'tagline',
			'type'         => 'textarea',
			'rows'         => 2,
			'new_lines'    => 'br',
			'instructions' => 'Optional subtitle or short description. Helps search engines and AI understand the page purpose.',
		],
		[
			'key'           => 'field_ble_page_header_show_breadcrumbs',
			'label'         => 'Show Breadcrumbs',
			'name'          => 'show_breadcrumbs',
			'type'          => 'true_false',
			'default_value' => 1,
			'ui'            => 1,
			'instructions'  => 'Display breadcrumb navigation above the heading. Reinforces site hierarchy for search engines.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/page-header',
			],
		],
	],
] );
