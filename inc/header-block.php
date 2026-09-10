<?php
/**
 * Header block - server-side rendered.
 *
 * Registers a lightweight block (webok/header) with no editor UI.
 * The render callback outputs the full header HTML, using wp_nav_menu()
 * with BLE_Nav_Walker for dynamic navigation.
 *
 * On the home page the header starts transparent (overlaying the hero)
 * and transitions to a solid ivory background on scroll via JS.
 * On interior pages the header is always solid.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the header block.
 */
function register_header_block(): void {
	\register_block_type( 'webok/header', [
		'render_callback' => __NAMESPACE__ . '\\render_header_block',
	] );
}
\add_action( 'init', __NAMESPACE__ . '\\register_header_block' );

/**
 * Render the header.
 *
 * @return string
 */
function render_header_block(): string {
	$is_home   = \is_front_page();
	$page_type = $is_home ? 'home' : 'default';
	$variant   = $is_home ? 'ble-header--transparent' : 'ble-header--solid';

	\ob_start();
	?>

	<a href="#main" class="ble-header__skip">Skip to main content</a>

	<header
		class="ble-header <?= \esc_attr( $variant ); ?>"
		data-ble-header
		data-page-type="<?= \esc_attr( $page_type ); ?>"
	>
		<div class="ble-header__inner">

			<?php /* ── Logo ────────────────────────────────────────────────── */ ?>
			<div class="ble-header__logo" data-ble-logo>
				<a href="<?= \esc_url( \home_url( '/' ) ); ?>" aria-label="<?= \esc_attr( \get_bloginfo( 'name' ) ); ?> - Home">
					<?php
					$logo_id = \get_theme_mod( 'custom_logo' );
					if ( $logo_id ) {
						echo \wp_get_attachment_image( $logo_id, 'medium', false, [
							'class'   => 'ble-header__logo-img',
							'loading' => 'eager',
						] );
					}
					?>
					<span class="ble-header__site-name"><?= \esc_html( \get_bloginfo( 'name' ) ); ?></span>
				</a>
			</div>

			<?php /* ── Desktop navigation ──────────────────────────────────── */ ?>
			<nav class="ble-header__nav" aria-label="Primary Navigation">
				<?php
				if ( \has_nav_menu( 'primary' ) ) {
					\wp_nav_menu( [
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'ble-header__menu',
						'walker'         => new BLE_Nav_Walker( 'desktop' ),
						'fallback_cb'    => false,
						'depth'          => 2,
					] );
				}
				?>
			</nav>

			<?php /* ── Hamburger (mobile) ──────────────────────────────────── */ ?>
			<button
				class="ble-header__hamburger"
				aria-label="Open menu"
				aria-expanded="false"
				aria-controls="ble-mobile-menu"
				data-ble-mobile-toggle
			>
				<span class="ble-header__hamburger-box">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>

		</div>

		<?php /* ── Mobile drawer ───────────────────────────────────────────── */ ?>
		<div
			class="ble-header__drawer"
			id="ble-mobile-menu"
			data-ble-mobile-menu
			hidden
		>
			<nav aria-label="Mobile Navigation">
				<?php
				if ( \has_nav_menu( 'primary' ) ) {
					\wp_nav_menu( [
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'ble-header__mobile-menu',
						'walker'         => new BLE_Nav_Walker( 'mobile' ),
						'fallback_cb'    => false,
						'depth'          => 2,
					] );
				}
				?>
			</nav>
		</div>

	</header>

	<?php if ( ! $is_home ) : ?>
		<div class="ble-header__spacer" aria-hidden="true"></div>
	<?php endif; ?>

	<?php
	return \ob_get_clean();
}
