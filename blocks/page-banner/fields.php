<?php
/**
 * Page Banner — ACF field group registration.
 *
 * Half-height banner with background image, overlay controls,
 * heading (H1), and optional tagline. No buttons, no glass box.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_page_banner',
	'title'  => 'Page Banner',
	'fields' => [
		[
			'key'          => 'field_ble_page_banner_heading',
			'label'        => 'Heading (H1)',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'The page H1. One per page.',
		],
		[
			'key'          => 'field_ble_page_banner_tagline',
			'label'        => 'Tagline',
			'name'         => 'tagline',
			'type'         => 'text',
			'instructions' => 'Optional short line beneath the heading.',
		],
		[
			'key'           => 'field_ble_page_banner_bg_image',
			'label'         => 'Background Image',
			'name'          => 'background_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'large',
			'instructions'  => 'Full-bleed banner image. Minimum 1920px wide. Write descriptive alt text.',
		],
		[
			'key'           => 'field_ble_page_banner_overlay_color',
			'label'         => 'Overlay Colour',
			'name'          => 'overlay_color',
			'type'          => 'color_picker',
			'default_value' => '#2C2A27',
			'instructions'  => 'Colour of the darkening overlay. Default: charcoal.',
		],
		[
			'key'           => 'field_ble_page_banner_overlay_opacity',
			'label'         => 'Overlay Opacity',
			'name'          => 'overlay_opacity',
			'type'          => 'number',
			'default_value' => 50,
			'min'           => 0,
			'max'           => 90,
			'step'          => 5,
			'append'        => '%',
			'instructions'  => 'How dark the overlay is. 0 = none, 90 = very dark. Default 50.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/page-banner',
			],
		],
	],
] );
