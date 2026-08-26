<?php
/**
 * Register custom ACF PRO blocks.
 *
 * Each block gets one acf_register_block_type() call here.
 * Assets (edit.js, view.js, style.css) are declared in the block's block.json
 * and enqueued by WordPress only on pages where the block is present.
 *
 * Phase 2: uncomment and duplicate the example below for each block.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom blocks.
 */
function register_blocks(): void {
	if ( ! \function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	// ── Example block registration ────────────────────────────────────────────
	// Duplicate this block for each custom ACF block. The render_template path
	// points to the block's render.php inside its directory.
	//
	// \acf_register_block_type( [
	// 	'name'            => 'webok-hero',
	// 	'title'           => \__( 'Hero', 'brannen-lake-estates' ),
	// 	'description'     => \__( 'Full-width hero with headline, tagline, and CTA.', 'brannen-lake-estates' ),
	// 	'category'        => 'webok-blocks',
	// 	'icon'            => 'cover-image',
	// 	'keywords'        => [ 'hero', 'banner', 'header' ],
	// 	'render_template' => \get_stylesheet_directory() . '/blocks/hero/render.php',
	// 	'supports'        => [ 'align' => [ 'wide', 'full' ] ],
	// ] );
}
\add_action( 'acf/init', __NAMESPACE__ . '\\register_blocks' );

/**
 * Register a custom block category so Web Ok blocks are grouped in the inserter.
 * The "webok-blocks" slug is intentionally shared across Web Ok projects —
 * all custom blocks for any client will appear under one "Web Ok Blocks" group.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function block_categories( array $categories ): array {
	return \array_merge(
		[
			[
				'slug'  => 'webok-blocks',
				'title' => \__( 'Web Ok Blocks', 'brannen-lake-estates' ),
				'icon'  => null,
			],
		],
		$categories
	);
}
\add_filter( 'block_categories_all', __NAMESPACE__ . '\\block_categories' );
