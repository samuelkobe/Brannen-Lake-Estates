<?php
/**
 * Permanent (301) redirects for old URLs.
 *
 * Kept in the theme so they travel with the code and don't depend on a
 * redirects plugin. Add old paths (no domain, no trailing slash) to the map.
 * If a published page is later created at an old path, that page is shown
 * instead and the redirect is skipped.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const REDIRECTS = [
	'book-online' => 'availability',           // Old Wix booking page, renamed 2026-11-19.
	'elopements'  => 'venue/the-runaway-barn', // Old Wix elopements page.
	'contact-2'   => 'contact-us',             // Old Wix contact page slug.
	// Old Wix package ZIP downloads -> the new package PDFs.
	'_files/archives/2c201d_e3f2dafd70b64dfbb395be618ef494de.zip' => 'wp-content/uploads/2026/11/brannen-lake-estates-find-us-in-the-forest.pdf',
	'_files/archives/2c201d_099cd212b9574a0cb0d6c1c5d3b1e179.zip' => 'wp-content/uploads/2026/11/brannen-lake-estates-lakeside-love-story.pdf',
	'_files/archives/2c201d_9363245d0ff640848bdc1e88de1aef5a.zip' => 'wp-content/uploads/2026/11/brannen-lake-estates-fields-of-forever.pdf',
	'_files/archives/2c201d_ee2823eee35c49a990937ce8a2ecb816.zip' => 'wp-content/uploads/2026/11/brannen-lake-estates-the-runaway-barn.pdf',
];

\add_action(
	'template_redirect',
	static function (): void {
		$path = \trim( (string) \wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
		// A real published page at the old path always wins, so re-using a slug
		// later (e.g. a new /book-online page) just works without editing this file.
		if ( isset( REDIRECTS[ $path ] ) && ! \get_page_by_path( $path ) ) {
			$to = REDIRECTS[ $path ];
			\wp_safe_redirect( \home_url( '/' . $to . ( \pathinfo( $to, PATHINFO_EXTENSION ) ? '' : '/' ) ), 301 );
			exit;
		}
	},
	1
);
