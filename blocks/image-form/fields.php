<?php
/**
 * Image + Form — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_image_form',
	'title'  => 'Image + Form',
	'fields' => [
		[
			'key'          => 'field_ble_image_form_anchor_id',
			'label'        => 'Anchor ID',
			'name'         => 'anchor_id',
			'type'         => 'text',
			'instructions' => 'Optional. Used for anchor links (e.g. "photography-inquiry" lets you link to this block with #photography-inquiry). Lowercase, no spaces.',
		],
		[
			'key'           => 'field_ble_image_form_bg_color',
			'label'         => 'Background Colour',
			'name'          => 'bg_color',
			'type'          => 'radio',
			'choices'       => [
				'ivory'    => 'Ivory',
				'cream'    => 'Cream',
				'sage'     => 'Sage',
				'charcoal' => 'Charcoal',
			],
			'default_value' => 'ivory',
			'layout'        => 'horizontal',
			'instructions'  => 'Section background colour. Sage and charcoal automatically switch to light text.',
		],
		[
			'key'           => 'field_ble_image_form_image',
			'label'         => 'Image',
			'name'          => 'image',
			'type'          => 'image',
			'required'      => 1,
			'return_format' => 'array',
			'preview_size'  => 'large',
			'instructions'  => 'Recommended minimum 800 x 600 px.',
		],
		[
			'key'           => 'field_ble_image_form_image_position',
			'label'         => 'Image Position',
			'name'          => 'image_position',
			'type'          => 'button_group',
			'choices'       => [
				'left'  => 'Left Side',
				'right' => 'Right Side',
			],
			'default_value' => 'left',
			'layout'        => 'horizontal',
			'instructions'  => 'Which side the image appears on.',
		],
		[
			'key'          => 'field_ble_image_form_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Optional. Small uppercase label above the heading.',
		],
		[
			'key'          => 'field_ble_image_form_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading.',
		],
		[
			'key'          => 'field_ble_image_form_description',
			'label'        => 'Description',
			'name'         => 'description',
			'type'         => 'textarea',
			'rows'         => 2,
			'instructions' => 'Optional. A short line of text above the form.',
		],
		[
			'key'          => 'field_ble_image_form_shortcode',
			'label'        => 'Form Shortcode',
			'name'         => 'shortcode',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Paste the Contact Form 7 shortcode. e.g. [contact-form-7 id="123" title="Contact"]',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/image-form',
			],
		],
	],
] );
