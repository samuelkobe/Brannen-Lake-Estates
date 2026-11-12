<?php
/**
 * Image + Content — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_image_content',
	'title'  => 'Image + Content',
	'fields' => [
		[
			'key'          => 'field_ble_image_content_image',
			'label'        => 'Image',
			'name'         => 'image',
			'type'         => 'image',
			'required'     => 1,
			'return_format' => 'array',
			'preview_size'  => 'large',
			'instructions' => 'Recommended minimum 800 x 600 px.',
		],
		[
			'key'          => 'field_ble_image_content_image_position',
			'label'        => 'Image Position',
			'name'         => 'image_position',
			'type'         => 'button_group',
			'choices'      => [
				'left'  => 'Left Side',
				'right' => 'Right Side',
			],
			'default_value' => 'left',
			'layout'        => 'horizontal',
			'instructions'  => 'Which side the image appears on. Alternate between instances for visual rhythm.',
		],
		[
			'key'          => 'field_ble_image_content_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Optional. Small uppercase label above the heading.',
		],
		[
			'key'          => 'field_ble_image_content_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading.',
		],
		[
			'key'          => 'field_ble_image_content_body',
			'label'        => 'Content',
			'name'         => 'body',
			'type'         => 'wysiwyg',
			'tabs'         => 'visual',
			'toolbar'      => 'basic',
			'media_upload' => 0,
			'delay'        => 1,
			'instructions' => 'Body text. Supports paragraphs, bold, italic, lists and links (use links for internal pages like the venue pages).',
		],
		[
			'key'           => 'field_ble_image_content_cta',
			'label'         => 'Button Link',
			'name'          => 'cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Optional call-to-action button.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/image-content',
			],
		],
	],
] );
