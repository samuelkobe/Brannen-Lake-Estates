<?php
/**
 * ACF Local JSON - save and load paths.
 *
 * Tells ACF PRO to save field group JSON exports into the child theme's
 * acf-json/ directory and to load them back on init. This keeps any
 * GUI-edited field changes in version control as a safety net alongside
 * the PHP field registrations in each block's fields.php.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set the save path for ACF JSON exports.
 *
 * @param string $path Default save path.
 * @return string
 */
function acf_json_save_point( string $path ): string {
	return \get_stylesheet_directory() . '/acf-json';
}
\add_filter( 'acf/settings/save_json', __NAMESPACE__ . '\\acf_json_save_point' );

/**
 * Add the child theme's acf-json/ directory as a load point.
 *
 * @param array $paths Existing load paths.
 * @return array
 */
function acf_json_load_point( array $paths ): array {
	$paths[] = \get_stylesheet_directory() . '/acf-json';
	return $paths;
}
\add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\\acf_json_load_point' );
