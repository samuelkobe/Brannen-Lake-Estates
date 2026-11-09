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
		'title'           => \__( 'Title + Content', 'brannen-lake-estates' ),
		'description'     => \__( 'Centered text section with optional eyebrow, heading, and body copy. Reusable across any page.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'editor-aligncenter',
		'keywords'        => [ 'content', 'text', 'intro', 'section' ],
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

	// ── Gallery Grid ────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-gallery-grid',
		$theme_uri . '/blocks/gallery-grid/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'gallery-grid',
		'title'           => \__( 'Gallery Grid', 'brannen-lake-estates' ),
		'description'     => \__( 'Masonry-style image gallery with staggered scroll reveal. On mobile, a random subset is displayed to keep the page scroll manageable.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'format-gallery',
		'keywords'        => [ 'gallery', 'masonry', 'images', 'photos' ],
		'render_template' => $theme_dir . '/blocks/gallery-grid/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-gallery-grid' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Testimonial ─────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-testimonial',
		$theme_uri . '/blocks/testimonial/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'testimonial',
		'title'           => \__( 'Testimonial', 'brannen-lake-estates' ),
		'description'     => \__( 'Displays a single randomly selected testimonial. Add multiple quotes and one is chosen on each page load.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'format-quote',
		'keywords'        => [ 'testimonial', 'quote', 'review', 'feedback' ],
		'render_template' => $theme_dir . '/blocks/testimonial/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-testimonial' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Page Banner ─────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-page-banner',
		$theme_uri . '/blocks/page-banner/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'page-banner',
		'title'           => \__( 'Page Banner', 'brannen-lake-estates' ),
		'description'     => \__( 'Half-height banner with background image, overlay, and centered heading. Use as the page header on interior pages.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'format-image',
		'keywords'        => [ 'banner', 'header', 'page', 'hero' ],
		'render_template' => $theme_dir . '/blocks/page-banner/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-page-banner' );
		},
		'supports'        => [ 'align' => [ 'full' ] ],
		'align'           => 'full',
		'mode'            => 'preview',
	] );

	// ── Page Header ─────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-page-header',
		$theme_uri . '/blocks/page-header/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'page-header',
		'title'           => \__( 'Page Header', 'brannen-lake-estates' ),
		'description'     => \__( 'Lightweight ivory-background page header with H1, optional tagline, and breadcrumbs. Use instead of Page Banner when no background image is needed.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'heading',
		'keywords'        => [ 'header', 'page', 'h1', 'title', 'breadcrumb' ],
		'render_template' => $theme_dir . '/blocks/page-header/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-page-header' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Image + Content ─────────────────────────────────────────────────────

	\wp_register_style(
		'ble-image-content',
		$theme_uri . '/blocks/image-content/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'image-content',
		'title'           => \__( 'Image + Content', 'brannen-lake-estates' ),
		'description'     => \__( 'Two-column layout with an image on one side and text on the other. Toggle image position left or right.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'align-pull-left',
		'keywords'        => [ 'image', 'content', 'about', 'story', 'split' ],
		'render_template' => $theme_dir . '/blocks/image-content/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-image-content' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Image + Form ────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-image-form',
		$theme_uri . '/blocks/image-form/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'image-form',
		'title'           => \__( 'Image + Form', 'brannen-lake-estates' ),
		'description'     => \__( 'Two-column layout with an image on one side and a shortcode form on the other.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'feedback',
		'keywords'        => [ 'form', 'contact', 'image', 'shortcode' ],
		'render_template' => $theme_dir . '/blocks/image-form/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-image-form' );
		},
		'supports'        => [ 'align' => [ 'full', 'wide' ] ],
		'align'           => 'full',
		'mode'            => 'preview',
	] );

	// ── FAQ ─────────────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-faq',
		$theme_uri . '/blocks/faq/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'faq',
		'title'           => \__( 'FAQ', 'brannen-lake-estates' ),
		'description'     => \__( 'Accordion-style frequently asked questions with FAQPage structured data for SEO.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'editor-help',
		'keywords'        => [ 'faq', 'questions', 'accordion', 'help' ],
		'render_template' => $theme_dir . '/blocks/faq/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-faq' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Venue Details ───────────────────────────────────────────────────────

	\wp_register_style(
		'ble-venue-details',
		$theme_uri . '/blocks/venue-details/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'venue-details',
		'title'           => \__( 'Venue Details', 'brannen-lake-estates' ),
		'description'     => \__( 'Structured venue data card with capacity, features, and EventVenue schema. Use once per venue page.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'location',
		'keywords'        => [ 'venue', 'details', 'capacity', 'features' ],
		'render_template' => $theme_dir . '/blocks/venue-details/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-venue-details' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Photographer Credits ────────────────────────────────────────────────

	\wp_register_style(
		'ble-photographer-credits',
		$theme_uri . '/blocks/photographer-credits/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'photographer-credits',
		'title'           => \__( 'Photographer Credits', 'brannen-lake-estates' ),
		'description'     => \__( 'Grid of photographer cards with external portfolio links and Person schema.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'camera',
		'keywords'        => [ 'photographer', 'credits', 'portfolio', 'links' ],
		'render_template' => $theme_dir . '/blocks/photographer-credits/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-photographer-credits' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Contact Details ─────────────────────────────────────────────────────

	\wp_register_style(
		'ble-contact-details',
		$theme_uri . '/blocks/contact-details/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'contact-details',
		'title'           => \__( 'Contact Details + Map', 'brannen-lake-estates' ),
		'description'     => \__( 'Google Maps embed with address, phone, and email details.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'location-alt',
		'keywords'        => [ 'contact', 'map', 'address', 'phone', 'email', 'nap' ],
		'render_template' => $theme_dir . '/blocks/contact-details/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-contact-details' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Vendor List ─────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-vendor-list',
		$theme_uri . '/blocks/vendor-list/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'vendor-list',
		'title'           => \__( 'Vendor List', 'brannen-lake-estates' ),
		'description'     => \__( 'Preferred vendor directory grouped by type. Pulls from the Vendors CPT.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'businessperson',
		'keywords'        => [ 'vendor', 'preferred', 'supplier', 'partner' ],
		'render_template' => $theme_dir . '/blocks/vendor-list/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-vendor-list' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Nearby Accommodations ───────────────────────────────────────────────

	\wp_register_style(
		'ble-nearby-accommodations',
		$theme_uri . '/blocks/nearby-accommodations/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'nearby-accommodations',
		'title'           => \__( 'Nearby Accommodations', 'brannen-lake-estates' ),
		'description'     => \__( 'Card list of nearby lodging options with images, distances, and map links.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'building',
		'keywords'        => [ 'accommodations', 'hotel', 'lodging', 'nearby', 'stay' ],
		'render_template' => $theme_dir . '/blocks/nearby-accommodations/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-nearby-accommodations' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Availability Calendar ───────────────────────────────────────────────

	\wp_register_style(
		'ble-availability-calendar',
		$theme_uri . '/blocks/availability-calendar/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'availability-calendar',
		'title'           => \__( 'Availability Calendar', 'brannen-lake-estates' ),
		'description'     => \__( 'Read-only Google Calendar embed showing venue availability.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'calendar-alt',
		'keywords'        => [ 'calendar', 'availability', 'dates', 'booking', 'schedule' ],
		'render_template' => $theme_dir . '/blocks/availability-calendar/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-availability-calendar' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Info ────────────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-info',
		$theme_uri . '/blocks/info/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'info',
		'title'           => \__( 'Info', 'brannen-lake-estates' ),
		'description'     => \__( 'Simple text section with optional eyebrow, heading, and rich-text body. For notes, tips, or supplementary content.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'info-outline',
		'keywords'        => [ 'info', 'text', 'note', 'content', 'section' ],
		'render_template' => $theme_dir . '/blocks/info/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-info' );
		},
		'supports'        => [ 'align' => false ],
		'mode'            => 'preview',
	] );

	// ── Alert ───────────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-alert',
		$theme_uri . '/blocks/alert/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'alert',
		'title'           => \__( 'Alert', 'brannen-lake-estates' ),
		'description'     => \__( 'Notice bar for short messages such as appointment-only visits. Optional button, dismiss, and sticky options.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'warning',
		'keywords'        => [ 'alert', 'notice', 'banner', 'appointment', 'message' ],
		'render_template' => $theme_dir . '/blocks/alert/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-alert' );
		},
		'supports'        => [ 'align' => [ 'full', 'wide' ] ],
		'align'           => 'full',
		'mode'            => 'preview',
	] );

	// ── Pricing ─────────────────────────────────────────────────────────────

	\wp_register_style(
		'ble-pricing',
		$theme_uri . '/blocks/pricing/style.css',
		[ 'webok-theme-styles' ],
		$version
	);

	\acf_register_block_type( [
		'name'            => 'pricing',
		'title'           => \__( 'Pricing', 'brannen-lake-estates' ),
		'description'     => \__( 'Grouped pricing: package cards (venue, capacity, price, includes) and a list layout for add-ons and rentals.', 'brannen-lake-estates' ),
		'category'        => 'webok-blocks',
		'icon'            => 'money-alt',
		'keywords'        => [ 'pricing', 'packages', 'price', 'rates', 'add-ons' ],
		'render_template' => $theme_dir . '/blocks/pricing/render.php',
		'enqueue_assets'  => function () {
			\wp_enqueue_style( 'ble-pricing' );
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
	require_once \get_stylesheet_directory() . '/blocks/gallery-grid/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/testimonial/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/page-banner/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/page-header/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/cta-banner/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/image-content/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/image-form/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/faq/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/venue-details/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/photographer-credits/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/contact-details/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/vendor-list/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/nearby-accommodations/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/availability-calendar/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/info/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/alert/fields.php';
	require_once \get_stylesheet_directory() . '/blocks/pricing/fields.php';
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
