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
