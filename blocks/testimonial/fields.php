<?php
/**
 * Testimonial — ACF field group registration.
 *
 * Pulls quotes from the Testimonials post type (WP admin › Testimonials),
 * either all of them or only selected Testimonial Types.
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
			'key'           => 'field_ble_testimonial_source',
			'label'         => 'Show',
			'name'          => 'source',
			'type'          => 'radio',
			'choices'       => [
				'all'   => 'All testimonials',
				'types' => 'Only selected types',
			],
			'default_value' => 'all',
			'layout'        => 'horizontal',
			'instructions'  => 'Add and edit testimonials under Testimonials in the admin menu.',
		],
		[
			'key'               => 'field_ble_testimonial_types',
			'label'             => 'Testimonial Types',
			'name'              => 'types',
			'type'              => 'taxonomy',
			'taxonomy'          => 'ble_testimonial_type',
			'field_type'        => 'checkbox',
			'add_term'          => 0,
			'save_terms'        => 0,
			'load_terms'        => 0,
			'return_format'     => 'id',
			'multiple'          => 1,
			'instructions'      => 'Testimonials with any of these types are included.',
			'conditional_logic' => [
				[
					[
						'field'    => 'field_ble_testimonial_source',
						'operator' => '==',
						'value'    => 'types',
					],
				],
			],
		],
		[
			'key'           => 'field_ble_testimonial_order',
			'label'         => 'Which quote shows',
			'name'          => 'order',
			'type'          => 'radio',
			'choices'       => [
				'random' => 'A random one on each visit',
				'newest' => 'The newest',
				'custom' => 'The first by Order number',
			],
			'default_value' => 'random',
			'layout'        => 'vertical',
			'instructions'  => 'The block shows one quote at a time. "Order number" is the Order box on each testimonial (lowest first).',
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
