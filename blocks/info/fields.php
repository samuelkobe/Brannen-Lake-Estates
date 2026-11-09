<?php
/**
 * Info — ACF field group registration.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! \function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

\acf_add_local_field_group( [
	'key'    => 'group_ble_info',
	'title'  => 'Info',
	'fields' => [
		[
			'key'   => 'field_ble_info_eyebrow',
			'label' => 'Eyebrow',
			'name'  => 'eyebrow',
			'type'  => 'text',
		],
		[
			'key'   => 'field_ble_info_heading',
			'label' => 'Heading',
			'name'  => 'heading',
			'type'  => 'text',
		],
		[
			'key'          => 'field_ble_info_body',
			'label'        => 'Body',
			'name'         => 'body',
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'basic',
			'media_upload' => 0,
			'instructions' => 'Supports paragraphs, bold, italic, links, and lists.',
		],
	],
	'location' => [
		[
			[
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/info',
			],
		],
	],
] );
