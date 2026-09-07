<?php
/**
 * Brannen Lake Estates — TT5 Child Theme
 * Functions and definitions.
 *
 * Namespace pattern: WebOk\{ProjectName}
 * When using this theme as a starter template, replace "BrannenLakeEstates"
 * in the namespace declaration of each inc/ file and this file. That's it.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load theme components.
 */
require_once get_stylesheet_directory() . '/inc/setup.php';
require_once get_stylesheet_directory() . '/inc/blocks.php';
require_once get_stylesheet_directory() . '/inc/schema.php';
require_once get_stylesheet_directory() . '/inc/editor-resizable-sidebar.php';

/**
 * Enqueue compiled theme assets and block view scripts.
 *
 * Block stylesheets (style.css) are loaded conditionally by WordPress via
 * acf_register_block_type() — only on pages where the block is present.
 *
 * Block view scripts (view.js) handle frontend motion. They are loaded here
 * via has_block() so they are:
 *   - only loaded on pages that actually contain the block
 *   - never loaded in the block editor
 *
 * View scripts are ES modules — WordPress loads them with type="module"
 * via wp_enqueue_script_module(), which defers them automatically.
 */
function enqueue_assets(): void {

	// Global compiled stylesheet (Tailwind + theme-level CSS).
	\wp_enqueue_style(
		'webok-theme-styles',
		\get_stylesheet_directory_uri() . '/dist/assets/css/theme.css',
		[],
		\wp_get_theme()->get( 'Version' )
	);

	$theme_uri = \get_stylesheet_directory_uri();
	$version   = \wp_get_theme()->get( 'Version' );

	// Scroll reveal — shared utility, loaded on every page.
	\wp_enqueue_script_module(
		'ble-scroll-reveal',
		$theme_uri . '/dist/assets/js/scroll-reveal.js',
		[],
		$version
	);

	// Hero view script — parallax + load animation.
	if ( \has_block( 'acf/hero' ) ) {
		\wp_enqueue_script_module(
			'ble-hero-view',
			$theme_uri . '/dist/blocks/hero/view.js',
			[],
			$version
		);
	}

	// CTA Banner view script — scroll-reveal.
	if ( \has_block( 'acf/cta-banner' ) ) {
		\wp_enqueue_script_module(
			'ble-cta-banner-view',
			$theme_uri . '/dist/blocks/cta-banner/view.js',
			[],
			$version
		);
	}
}
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
