<?php
/**
 * FAQ — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_faq',
	'title'  => 'FAQ',
	'fields' => [
		[
			'key'          => 'field_ble_faq_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. e.g. "Common Questions".',
		],
		[
			'key'          => 'field_ble_faq_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading. e.g. "Frequently Asked Questions".',
		],
		[
			'key'          => 'field_ble_faq_items',
			'label'        => 'Questions',
			'name'         => 'items',
			'type'         => 'repeater',
			'required'     => 1,
			'min'          => 1,
			'layout'       => 'block',
			'button_label' => 'Add Question',
			'sub_fields'   => [
				[
					'key'      => 'field_ble_faq_question',
					'label'    => 'Question',
					'name'     => 'question',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'          => 'field_ble_faq_answer',
					'label'        => 'Answer',
					'name'         => 'answer',
					'type'         => 'wysiwyg',
					'required'     => 1,
					'tabs'         => 'all',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/faq',
			],
		],
	],
] );
