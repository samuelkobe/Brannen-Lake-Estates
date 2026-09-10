<?php
/**
 * Testimonial — ACF field group registration.
 *
 * Displays a single randomly selected testimonial from a repeater.
 * Each testimonial has a quote, attribution name, and optional date.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_testimonial',
	'title'  => 'Testimonial',
	'fields' => [
		[
			'key'          => 'field_ble_testimonial_eyebrow',
			'label'        => 'Eyebrow',
			'name'         => 'eyebrow',
			'type'         => 'text',
			'instructions' => 'Small uppercase label above the heading. Optional.',
		],
		[
			'key'          => 'field_ble_testimonial_heading',
			'label'        => 'Heading',
			'name'         => 'heading',
			'type'         => 'text',
			'instructions' => 'Section heading. Optional.',
		],
		[
			'key'          => 'field_ble_testimonial_items',
			'label'        => 'Testimonials',
			'name'         => 'items',
			'type'         => 'repeater',
			'min'          => 1,
			'max'          => 20,
			'layout'       => 'block',
			'button_label' => 'Add Testimonial',
			'sub_fields'   => [
				[
					'key'          => 'field_ble_testimonial_item_quote',
					'label'        => 'Quote',
					'name'         => 'quote',
					'type'         => 'textarea',
					'rows'         => 3,
					'required'     => 1,
					'instructions' => 'The testimonial text. Keep it to 1-3 sentences for best display.',
				],
				[
					'key'      => 'field_ble_testimonial_item_name',
					'label'    => 'Name',
					'name'     => 'name',
					'type'     => 'text',
					'required' => 1,
					'instructions' => 'Attribution. e.g. "Sarah & James M."',
				],
				[
					'key'          => 'field_ble_testimonial_item_date',
					'label'        => 'Date',
					'name'         => 'date',
					'type'         => 'text',
					'instructions' => 'Optional. e.g. "September 2024" or "Fall 2024".',
				],
			],
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/testimonial',
			],
		],
	],
] );
