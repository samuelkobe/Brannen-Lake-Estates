<?php
/**
 * Permanent (301) redirects for old URLs.
 *
 * Kept in the theme so they travel with the code and don't depend on a
 * redirects plugin. Add old paths (no domain, no trailing slash) to the map.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const REDIRECTS = [
	'book-online' => 'availability', // Old Wix booking page, renamed 2026-11-19.
];

\add_action(
	'template_redirect',
	static function (): void {
		$path = \trim( (string) \wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
		if ( isset( REDIRECTS[ $path ] ) ) {
			\wp_safe_redirect( \home_url( '/' . REDIRECTS[ $path ] . '/' ), 301 );
			exit;
		}
	},
	1
);
