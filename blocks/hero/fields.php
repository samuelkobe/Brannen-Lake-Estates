<?php
/**
 * Hero — ACF field group registration.
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
	'key'    => 'group_ble_hero',
	'title'  => 'Hero',
	'fields' => [
		[
			'key'          => 'field_ble_hero_heading',
			'label'        => 'Heading (H1)',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'The page H1. One per page. Target the primary keyword here (e.g. "Nanaimo Wedding Venue").',
		],
		[
			'key'           => 'field_ble_hero_heading_size',
			'label'         => 'Heading Max Size',
			'name'          => 'heading_size',
			'type'          => 'number',
			'default_value' => 6,
			'min'           => 3,
			'max'           => 10,
			'step'          => 0.25,
			'append'        => 'rem',
			'instructions'  => 'Maximum font size for the H1. Scales fluidly down from this value. Default 6rem (~96px). Reduce for longer headings.',
		],
		[
			'key'          => 'field_ble_hero_tagline',
			'label'        => 'Tagline',
			'name'         => 'tagline',
			'type'         => 'text',
			'instructions' => 'One short line beneath the heading. Keep it evocative, not keyword-heavy.',
		],
		[
			'key'           => 'field_ble_hero_bg_image',
			'label'         => 'Background Image',
			'name'          => 'background_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'large',
			'required'      => 1,
			'instructions'  => 'Required. Full-bleed hero image, minimum 1920×1080. Always used on phones and tablets, and on desktop while a video loads. Write descriptive alt text, it\'s indexed.',
		],
		[
			'key'           => 'field_ble_hero_bg_video',
			'label'         => 'Background Video (desktop only)',
			'name'          => 'background_video',
			'type'          => 'file',
			'return_format' => 'array',
			'library'       => 'all',
			'mime_types'    => 'mp4,webm',
			'instructions'  => 'Optional. Plays muted on a loop behind the hero on screens 1024px and wider; phones and tablets always show the image. Use a short MP4 (10 to 20 seconds, ideally under 8 MB, 1920×1080, no audio needed).',
		],
		[
			'key'           => 'field_ble_hero_video_plays',
			'label'         => 'Video Plays',
			'name'          => 'video_plays',
			'type'          => 'number',
			'default_value' => 2,
			'min'           => 0,
			'max'           => 20,
			'step'          => 1,
			'instructions'  => 'How many times the video plays before it fades to the background image. 0 = loop forever. Visitors can replay it with the button in the corner.',
			'conditional_logic' => [ [ [ 'field' => 'field_ble_hero_bg_video', 'operator' => '!=empty' ] ] ],
		],
		[
			'key'           => 'field_ble_hero_overlay_color',
			'label'         => 'Overlay Colour',
			'name'          => 'overlay_color',
			'type'          => 'color_picker',
			'default_value' => '#2C2A27',
			'instructions'  => 'Colour of the darkening overlay on the background image. Default: charcoal.',
		],
		[
			'key'           => 'field_ble_hero_overlay_opacity',
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
		[
			'key'           => 'field_ble_hero_primary_cta',
			'label'         => 'Primary Button',
			'name'          => 'primary_cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Main call to action. e.g. "Inquire Now" linking to the contact page.',
		],
		[
			'key'           => 'field_ble_hero_secondary_cta',
			'label'         => 'Secondary Button',
			'name'          => 'secondary_cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Optional. Displayed as a quieter button beside the primary.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/hero',
			],
		],
	],
] );
