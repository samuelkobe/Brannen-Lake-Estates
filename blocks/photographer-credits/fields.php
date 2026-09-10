<?php
/**
 * Photographer Credits — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_photographer_credits',
	'title'  => 'Photographer Credits',
	'fields' => [
		[
			'key'   => 'field_ble_photographer_credits_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'eyebrow',
			'type'  => 'text',
			'instructions' => 'Small uppercase label above the heading. e.g. "The Photographers".',
		],
		[
			'key'      => 'field_ble_photographer_credits_heading',
			'label'    => 'Heading',
			'name'     => 'heading',
			'type'     => 'text',
			'required' => 1,
		],
		[
			'key'          => 'field_ble_photographer_credits_body',
			'label'        => 'Body',
			'name'         => 'body',
			'type'         => 'textarea',
			'rows'         => 3,
			'instructions' => 'Optional intro paragraph below the heading.',
		],
		[
			'key'          => 'field_ble_photographer_credits_photographers',
			'label'        => 'Photographers',
			'name'         => 'photographers',
			'type'         => 'repeater',
			'required'     => 1,
			'min'          => 1,
			'max'          => 20,
			'layout'       => 'block',
			'button_label' => 'Add Photographer',
			'sub_fields'   => [
				[
					'key'      => 'field_ble_photographer_credits_name',
					'label'    => 'Name',
					'name'     => 'name',
					'type'     => 'text',
					'required' => 1,
					'instructions' => 'Photographer or studio name.',
				],
				[
					'key'      => 'field_ble_photographer_credits_portfolio_url',
					'label'    => 'Portfolio URL',
					'name'     => 'portfolio_url',
					'type'     => 'url',
					'required' => 1,
					'instructions' => 'Link to their website or portfolio.',
				],
				[
					'key'          => 'field_ble_photographer_credits_photo',
					'label'        => 'Photo',
					'name'         => 'photo',
					'type'         => 'image',
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'instructions' => 'Optional headshot or logo. Displays as a circle.',
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/photographer-credits',
			],
		],
	],
] );
