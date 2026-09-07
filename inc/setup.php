<?php
/**
 * Theme setup: supports, navigation menus, and image sizes.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function setup(): void {
	\add_theme_support( 'custom-logo' );
	\add_theme_support( 'post-thumbnails' );
	\add_theme_support( 'responsive-embeds' );

	\register_nav_menus(
		[
			'primary' => \__( 'Primary Navigation', 'brannen-lake-estates' ),
			'footer'  => \__( 'Footer Navigation', 'brannen-lake-estates' ),
		]
	);

	// Inject stylesheets into the block editor iframe.
	// Without this, Tailwind utilities and block-specific CSS have no effect
	// in the editor preview (WP 6.x renders blocks inside an iframe).
	\add_editor_style( 'dist/assets/css/theme.css' );
	\add_editor_style( 'blocks/hero/style.css' );
	\add_editor_style( 'blocks/welcome-intro/style.css' );
	\add_editor_style( 'blocks/venue-cards/style.css' );
	\add_editor_style( 'blocks/cta-banner/style.css' );
}
\add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Register custom image sizes.
 * Phase 2: uncomment and add sizes based on block designs.
 */
function image_sizes(): void {
	// \add_image_size( 'webok-hero',         1920, 900, true );
	// \add_image_size( 'webok-venue-card',    800,  600, true );
	// \add_image_size( 'webok-gallery-thumb', 600,  600, true );
}
\add_action( 'after_setup_theme', __NAMESPACE__ . '\\image_sizes' );
