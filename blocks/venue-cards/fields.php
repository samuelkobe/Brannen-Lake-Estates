<?php
/**
 * Venue Cards — ACF field group registration.
 *
 * Uses an ACF PRO repeater so the client can add/reorder ceremony
 * settings without touching code. Default is 4 (Lakeside, Garden,
 * Forest, Intimate) but the repeater keeps it flexible.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_venue_cards',
	'title'  => 'Venue Cards',
	'fields' => [
		[
			'key'          => 'field_ble_venue_cards_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. e.g. "Four Distinct Settings".',
		],
		[
			'key'          => 'field_ble_venue_cards_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'required'     => 1,
			'instructions' => 'Section heading. e.g. "Your Ceremony, Your Way".',
		],
		[
			'key'          => 'field_ble_venue_cards_items',
			'label'        => 'Cards',
			'name'         => 'items',
			'type'         => 'repeater',
			'min'          => 1,
			'max'          => 6,
			'layout'       => 'block',
			'button_label' => 'Add Card',
			'sub_fields'   => [
				[
					'key'           => 'field_ble_venue_cards_item_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'instructions'  => 'Portrait or square works best. Write descriptive alt text.',
				],
				[
					'key'      => 'field_ble_venue_cards_item_title',
					'label'    => 'Title',
					'name'     => 'title',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'  => 'field_ble_venue_cards_item_description',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'rows' => 2,
					'instructions' => 'One or two sentences about this ceremony setting.',
				],
				[
					'key'           => 'field_ble_venue_cards_item_link',
					'label'         => 'Link',
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
					'instructions'  => 'Optional. Links to a detail page or gallery for this setting.',
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/venue-cards',
			],
		],
	],
] );
