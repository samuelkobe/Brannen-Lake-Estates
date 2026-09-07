<?php
/**
 * Register custom ACF PRO blocks and their ACF field groups.
 *
 * Each block lives in blocks/<name>/ and has:
 *   - render.php    server-side HTML (ACF fields + Tailwind utility classes)
 *   - style.css     block-specific CSS, loaded conditionally by WordPress
 *   - view.ts → .js frontend motion script, enqueued in functions.php
 *   - fields.php    ACF field group registration (keeps fields in version control)
 *
 * View scripts are NOT enqueued here — they are loaded conditionally in
 * functions.php via has_block() so they never load in the block editor.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom ACF blocks and their stylesheets.
 */
function register_blocks(): void {
	if ( ! \function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	$theme_uri = \get_stylesheet_directory_uri();
	$theme_dir = \get_stylesheet_directory();
	$version   = \wp_get_theme()->get( 'Version' );

	// Register theme.css so block styles can declare it as a dependency.
	// The handle is shared with functions.php — WordPress deduplicates.
	\wp_register_style(
		'webok-theme-styles',
		$theme_uri . '/dist/assets/css/theme.css',
		[],
		$version
	);

	// ── Hero ─────────────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-hero',
		$theme_uri . '/blocks/hero/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'hero',
		'title'           => \__( 'Hero', 'brannen-lake-estates' ),
		'description'     => \__( 'Full-bleed cinematic hero with frosted glass text container. Contains the page H1 — use once per page.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'cover-image',
		'keywords'        => [ 'hero', 'banner', 'header', 'h1' ],
		'render_template' => $theme_dir . '/blocks/hero/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-hero' );
		},
		'supports'        => [ 'align' => [ 'full' ] ],
		'align'           => 'full',
		'mode'            => 'preview',
	] );

	// ── Welcome Intro ────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-welcome-intro',
		$theme_uri . '/blocks/welcome-intro/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'welcome-intro',
		'title'           => \__( 'Welcome Intro', 'brannen-lake-estates' ),
		'description'     => \__( 'Centered text section introducing the venue\'s story and heritage.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'editor-aligncenter',
		'keywords'        => [ 'welcome', 'intro', 'about', 'story' ],
		'render_template' => $theme_dir . '/blocks/welcome-intro/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-welcome-intro' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Venue Cards ─────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-venue-cards',
		$theme_uri . '/blocks/venue-cards/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'venue-cards',
		'title'           => \__( 'Venue Cards', 'brannen-lake-estates' ),
		'description'     => \__( 'Card grid showcasing each ceremony setting (Lakeside, Garden, Forest, Intimate, etc.).', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'grid-view',
		'keywords'        => [ 'venue', 'cards', 'ceremony', 'settings' ],
		'render_template' => $theme_dir . '/blocks/venue-cards/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-venue-cards' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── CTA Banner ───────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-cta-banner',
		$theme_uri . '/blocks/cta-banner/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'cta-banner',
		'title'           => \__( 'CTA Banner', 'brannen-lake-estates' ),
		'description'     => \__( 'Full-width call-to-action band on a sage background. Reused on every page.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'megaphone',
		'keywords'        => [ 'cta', 'banner', 'call to action', 'contact' ],
		'render_template' => $theme_dir . '/blocks/cta-banner/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-cta-banner' );
		},
		'supports'        => [ 'align' => [ 'full', 'wide' ] ],
		'align'           => 'full',
		'mode'            => 'preview',
	] );
}
\add_action( 'acf/init', __NAMESPACE__ . '\\register_blocks' );

// Editor styles are loaded via add_editor_style() in inc/setup.php.
// This injects CSS directly into the block editor iframe, which is the
// only reliable method in WordPress 6.x (enqueue_block_editor_assets
// loads styles in the outer editor page, NOT inside the iframe).

/**
 * Load ACF field group definitions for each block.
 * Fields are registered in PHP to keep them in version control.
 */
function load_block_fields(): void {
	require_once \get_stylesheet_directory() . '/blocks/hero/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/welcome-intro/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/venue-cards/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/cta-banner/fields.php';
}
\add_action( 'acf/init', __NAMESPACE__ . '\\load_block_fields' );

/**
 * Register a custom block category so Web Ok blocks are grouped in the inserter.
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
