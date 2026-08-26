<?php
/**
 * Brannen Lake Estates — TT5 Child Theme
 * Functions and definitions.
 *
 * Namespace pattern: WebOk\{BrannenLakeEstates}
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

/**
 * Enqueue compiled theme assets.
 *
 * Block assets (edit.js, view.js, style.css) are declared in each block's
 * block.json and enqueued automatically by WordPress — only on pages where
 * that block is present. Only global/theme-level assets are enqueued here.
 */
function enqueue_assets(): void {
	\wp_enqueue_style(
		'webok-theme-styles',
		\get_stylesheet_directory_uri() . '/assets/css/theme.css',
		[],
		\wp_get_theme()->get( 'Version' )
	);
}
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
