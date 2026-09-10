<?php
/**
 * Gallery Grid — ACF field group registration.
 *
 * Masonry-style image gallery with staggered scroll reveal.
 * On mobile, a random subset of images is displayed to keep
 * the scroll length manageable.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_gallery_grid',
	'title'  => 'Gallery Grid',
	'fields' => [
		[
			'key'          => 'field_ble_gallery_grid_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. Optional.',
		],
		[
			'key'          => 'field_ble_gallery_grid_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading above the gallery.',
		],
		[
			'key'           => 'field_ble_gallery_grid_images',
			'label'         => 'Images',
			'name'          => 'images',
			'type'          => 'gallery',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'min'           => 1,
			'max'           => 20,
			'insert'        => 'append',
			'instructions'  => 'Add 8–12 images for best results. On mobile, 5 are randomly selected to keep the page scroll manageable. Write descriptive alt text for each image.',
		],
		[
			'key'           => 'field_ble_gallery_grid_mobile_count',
			'label'         => 'Mobile Image Count',
			'name'          => 'mobile_count',
			'type'          => 'number',
			'default_value' => 5,
			'min'           => 2,
			'max'           => 10,
			'instructions'  => 'Number of images to randomly display on mobile (single column). Default: 5.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/gallery-grid',
			],
		],
	],
] );
