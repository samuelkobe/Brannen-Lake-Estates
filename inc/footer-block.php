<?php
/**
 * Footer block - server-side rendered.
 *
 * Registers a lightweight block (webok/footer) with no editor UI.
 * The render callback outputs the full footer HTML, using wp_nav_menu()
 * for dynamic navigation so the client can manage links from
 * Appearance > Menus.
 *
 * Three footer menu locations (footer-1, footer-2, footer-3) each render
 * as a column. The column heading is pulled from the menu name itself,
 * so the client controls both the links and the title.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the footer block.
 */
function register_footer_block(): void {
	\register_block_type( 'webok/footer', [
		'render_callback' => __NAMESPACE__ . '\\render_footer_block',
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_footer_block' );

/**
 * Render a single footer nav column.
 *
 * Outputs the menu name as a heading and the links below it.
 * Returns empty string if no menu is assigned to the location.
 *
 * @param string $location Theme menu location slug.
 * @return string
 */
function render_footer_column( string $location ): string {
	$locations = \get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return '';
	}

	$menu = \wp_get_nav_menu_object( $locations[ $location ] );

	if ( ! $menu ) {
		return '';
	}

	ob_start();
	?>
	<div class="ble-footer__col">
		<h4 class="ble-footer__col-heading"><?= \esc_html( $menu->name ); ?></h4>
		<?php
		\wp_nav_menu( [
			'menu'        => $menu,
			'container'   => false,
			'menu_class'  => 'ble-footer__menu',
			'depth'       => 1,
			'fallback_cb' => false,
		] );
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render the footer HTML.
 *
 * @return string
 */
function render_footer_block(): string {
	$col_1 = render_footer_column( 'footer-1' );
	$col_2 = render_footer_column( 'footer-2' );
	$col_3 = render_footer_column( 'footer-3' );
	$has_nav = $col_1 || $col_2 || $col_3;

	ob_start();
	?>
	<footer class="ble-footer" role="contentinfo">
		<div class="ble-footer__inner">

			<?php /* ── Row 1: Brand + Nav columns ──────────────────────────── */ ?>
			<div class="ble-footer__top">
				<div class="ble-footer__brand">
					<h3 class="ble-footer__title">
						<a href="<?= \esc_url( \home_url( '/' ) ); ?>">
							<?= \esc_html( \get_bloginfo( 'name' ) ); ?>
						</a>
					</h3>
					<p class="ble-footer__tagline">Every love story deserves its own corner of the lake.</p>
				</div>

				<?php if ( $has_nav ) : ?>
				<nav class="ble-footer__nav" aria-label="<?= \esc_attr__( 'Footer navigation', 'brannen-lake-estates' ); ?>">
					<?= $col_1; ?>
					<?= $col_2; ?>
					<?= $col_3; ?>
				</nav>
				<?php endif; ?>
			</div>

			<hr class="ble-footer__rule">

			<?php /* ── Row 2: NAP + Socials ────────────────────────────────── */ ?>
			<div class="ble-footer__middle">
				<div class="ble-footer__nap-group">
					<?php
					$custom_logo_id = \get_theme_mod( 'custom_logo' );
					if ( $custom_logo_id ) :
					?>
					<div class="ble-footer__logo">
						<?= \wp_get_attachment_image( $custom_logo_id, 'thumbnail', false, [
							'class'   => 'ble-footer__logo-img',
							'loading' => 'lazy',
						] ); ?>
					</div>
					<?php endif; ?>

					<div class="ble-footer__nap">
						<p>6650 Doumont Rd, Nanaimo, BC V9T 6H1</p>
						<p>
							<a href="tel:+12506671407">250-667-1407</a>
							<span class="ble-footer__dot">&middot;</span>
							<a href="mailto:info@brannenlakeestates.ca">info@brannenlakeestates.ca</a>
						</p>
					</div>
				</div>

				<div class="ble-footer__social">
					<a href="https://www.instagram.com/brannenlakeestates/" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Instagram">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" fill="currentColor"/>
						</svg>
					</a>
					<a href="https://www.facebook.com/Brannenlakeestates/" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12c0 5.99 4.388 10.954 10.125 11.854V15.47H7.078V12h3.047V9.356c0-3.007 1.792-4.668 4.533-4.668 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874V12h3.328l-.532 3.47h-2.796v8.385C19.612 22.954 24 17.99 24 12z" fill="currentColor"/>
						</svg>
					</a>
				</div>
			</div>

			<hr class="ble-footer__rule">

			<?php /* ── Row 3: Copyright ────────────────────────────────────── */ ?>
			<div class="ble-footer__bottom">
				<p>&copy; <?= date( 'Y' ); ?> Brannen Lake Estates Ltd. All rights reserved.</p>
				<?php
				// Legal links: only pages that are published show up.
				$legal = [];
				foreach ( [ 'privacy-policy' => 'Privacy', 'terms' => 'Terms', 'accessibility' => 'Accessibility' ] as $slug => $label ) {
					$page = \get_page_by_path( $slug );
					if ( $page && 'publish' === $page->post_status ) {
						$legal[] = '<a href="' . \esc_url( \get_permalink( $page ) ) . '">' . \esc_html( $label ) . '</a>';
					}
				}
				if ( $legal ) : ?>
				<nav class="ble-footer__legal" aria-label="<?= \esc_attr__( 'Legal', 'brannen-lake-estates' ); ?>">
					<?= \implode( '<span aria-hidden="true">·</span>', $legal ); // phpcs:ignore ?>
				</nav>
				<?php endif; ?>
				<p>Site by <a href="https://webok.ca" target="_blank" rel="noopener noreferrer">Web Ok Solutions Inc.</a></p>
			</div>

		</div>
	</footer>
	<?php
	return ob_get_clean();
}
