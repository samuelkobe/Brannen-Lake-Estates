<?php
/**
 * Pricing — ACF field group registration.
 *
 * Sections repeater → each section picks a layout (cards or list) and holds
 * its own items repeater. Cards suit the venue packages; the list suits
 * add-ons and rentals.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_pricing',
	'title'  => 'Pricing',
	'fields' => [
		[
			'key'     => 'field_ble_pricing_eyebrow',
			'label'   => 'Eyebrow',
			'name'    => 'eyebrow',
			'type'    => 'text',
			'wrapper' => [ 'width' => '50' ],
		],
		[
			'key'           => 'field_ble_pricing_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => 'Pricing',
			'wrapper'       => [ 'width' => '50' ],
		],
		[
			'key'       => 'field_ble_pricing_intro',
			'label'     => 'Intro',
			'name'      => 'intro',
			'type'      => 'textarea',
			'rows'      => 3,
			'new_lines' => '',
		],
		[
			'key'          => 'field_ble_pricing_sections',
			'label'        => 'Sections',
			'name'         => 'sections',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add Section',
			'collapsed'    => 'field_ble_pricing_section_heading',
			'instructions' => 'e.g. "Venue Packages" as cards, then "Add-Ons & Rentals" as a list.',
			'sub_fields'   => [
				[
					'key'     => 'field_ble_pricing_section_heading',
					'label'   => 'Section heading',
					'name'    => 'section_heading',
					'type'    => 'text',
					'wrapper' => [ 'width' => '60' ],
				],
				[
					'key'           => 'field_ble_pricing_section_layout',
					'label'         => 'Layout',
					'name'          => 'layout',
					'type'          => 'select',
					'choices'       => [
						'cards' => 'Cards (packages)',
						'list'  => 'List (add-ons & extras)',
					],
					'default_value' => 'cards',
					'return_format' => 'value',
					'wrapper'       => [ 'width' => '40' ],
				],
				[
					'key'          => 'field_ble_pricing_items',
					'label'        => 'Items',
					'name'         => 'items',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add Item',
					'collapsed'    => 'field_ble_pricing_item_name',
					'sub_fields'   => [
						[
							'key'      => 'field_ble_pricing_item_name',
							'label'    => 'Name',
							'name'     => 'name',
							'type'     => 'text',
							'required' => 1,
							'wrapper'  => [ 'width' => '50' ],
						],
						[
							'key'          => 'field_ble_pricing_item_venue',
							'label'        => 'Venue / subtitle',
							'name'         => 'venue',
							'type'         => 'text',
							'instructions' => 'Cards only. e.g. "Lakeside" or "Elopement".',
							'wrapper'      => [ 'width' => '25' ],
						],
						[
							'key'          => 'field_ble_pricing_item_capacity',
							'label'        => 'Capacity',
							'name'         => 'capacity',
							'type'         => 'text',
							'placeholder'  => 'Up to 20 guests',
							'instructions' => 'Cards only.',
							'wrapper'      => [ 'width' => '25' ],
						],
						[
							'key'          => 'field_ble_pricing_item_price',
							'label'        => 'Price',
							'name'         => 'price',
							'type'         => 'text',
							'placeholder'  => '$1,500',
							'instructions' => 'Or "From $250", "Custom Quote".',
							'wrapper'      => [ 'width' => '50' ],
						],
						[
							'key'           => 'field_ble_pricing_item_price_note',
							'label'         => 'Price note',
							'name'          => 'price_note',
							'type'          => 'text',
							'default_value' => '+ GST',
							'wrapper'       => [ 'width' => '50' ],
						],
						[
							'key'       => 'field_ble_pricing_item_description',
							'label'     => 'Description',
							'name'      => 'description',
							'type'      => 'textarea',
							'rows'      => 2,
							'new_lines' => '',
						],
						[
							'key'          => 'field_ble_pricing_item_includes',
							'label'        => 'Includes',
							'name'         => 'includes',
							'type'         => 'textarea',
							'rows'         => 5,
							'new_lines'    => '',
							'instructions' => 'Cards only. One item per line, 3 to 5 lines works best.',
						],
						[
							'key'           => 'field_ble_pricing_item_link',
							'label'         => 'Link',
							'name'          => 'link',
							'type'          => 'link',
							'return_format' => 'array',
							'instructions'  => 'Cards only. Usually the venue page, e.g. "View Lakeside".',
						],
						[
							'key'           => 'field_ble_pricing_item_pdf',
							'label'         => 'Package PDF',
							'name'          => 'pdf',
							'type'          => 'file',
							'return_format' => 'array',
							'mime_types'    => 'pdf',
							'library'       => 'all',
							'instructions'  => 'Cards only. Optional. Shows a "Download package details" link under the button. To update prices later, use Media > Replace so the link stays the same.',
						],
					],
				],
			],
		],
		[
			'key'          => 'field_ble_pricing_footnote',
			'label'        => 'Footnote',
			'name'         => 'footnote',
			'type'         => 'textarea',
			'rows'         => 2,
			'new_lines'    => 'br',
			'instructions' => 'e.g. "Pricing valid for the 2026/27 season. Liability insurance and liquor permit not included."',
		],
		[
			'key'           => 'field_ble_pricing_cta',
			'label'         => 'Button',
			'name'          => 'cta',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'e.g. "Book a Viewing" linking to the contact page.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/pricing',
			],
		],
	],
] );
