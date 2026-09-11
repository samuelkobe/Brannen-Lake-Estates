<?php
/**
 * Page Banner — render template.
 *
 * Half-height banner with background image, centered heading and
 * optional tagline. Shares parallax behaviour with the hero block
 * via data-ble-hero / data-ble-parallax attributes.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading          = \get_field( 'heading' );
$tagline          = \get_field( 'tagline' );
$image            = \get_field( 'background_image' );
$overlay_color    = \get_field( 'overlay_color' ) ?: '#2C2A27';
$overlay_opacity  = \get_field( 'overlay_opacity' );
$show_breadcrumbs = \get_field( 'show_breadcrumbs' ) ?? true;

if ( ! $heading ) {
	return;
}

// Build breadcrumb trail from page ancestors.
$crumbs = [];
if ( $show_breadcrumbs ) {
	$post = \get_post();

	if ( $post ) {
		$crumbs[] = [
			'label' => 'Home',
			'url'   => \home_url( '/' ),
		];

		$ancestors = \array_reverse( \get_ancestors( $post->ID, 'page', 'post_type' ) );
		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = [
				'label' => \get_the_title( $ancestor_id ),
				'url'   => \get_permalink( $ancestor_id ),
			];
		}

		// Current page — no link.
		$crumbs[] = [
			'label' => \get_the_title(),
			'url'   => '',
		];
	}
}

// Overlay rgba.
$hex   = \ltrim( $overlay_color, '#' );
$r     = \hexdec( \substr( $hex, 0, 2 ) );
$g     = \hexdec( \substr( $hex, 2, 2 ) );
$b     = \hexdec( \substr( $hex, 4, 2 ) );
$alpha = ( $overlay_opacity !== false && $overlay_opacity !== '' )
	? (int) $overlay_opacity / 100
	: 0.50;
$overlay_style = sprintf( 'background-color:rgba(%d,%d,%d,%.2f)', $r, $g, $b, $alpha );

// Editor preview: skip load animation.
$banner_extra = ! empty( $is_preview ) ? ' is-loaded' : '';

$img_alt = ! empty( $image['alt'] ) ? $image['alt'] : \esc_attr( $heading );
?>
<section
	class="ble-page-banner relative w-full flex items-center justify-center overflow-hidden<?= \esc_attr( $banner_extra ); ?>"
	aria-labelledby="ble-page-banner-heading"
	data-ble-hero
>

	<?php /* ── Background image ──────────────────────────────────────────── */ ?>
	<div class="ble-page-banner__bg absolute inset-0" aria-hidden="true" data-ble-parallax>
		<?php if ( $image ) : ?>
		<img
			src="<?= \esc_url( $image['url'] ); ?>"
			alt="<?= \esc_attr( $img_alt ); ?>"
			class="w-full h-full object-cover object-center"
			loading="eager"
			fetchpriority="high"
			width="<?= \esc_attr( $image['width'] ?? '' ); ?>"
			height="<?= \esc_attr( $image['height'] ?? '' ); ?>"
		/>
		<?php endif; ?>
		<div class="ble-page-banner__overlay absolute inset-0" style="<?= \esc_attr( $overlay_style ); ?>"></div>
	</div>

	<?php /* ── Centered text ─────────────────────────────────────────────── */ ?>
	<div class="ble-page-banner__content relative z-10 text-center px-6 py-16" data-ble-reveal>

		<?php if ( ! empty( $crumbs ) ) : ?>
		<nav class="ble-page-banner__breadcrumbs" aria-label="<?= \esc_attr__( 'Breadcrumb', 'brannen-lake-estates' ); ?>">
			<ol>
				<?php foreach ( $crumbs as $i => $crumb ) : ?>
				<li<?php if ( $i === count( $crumbs ) - 1 ) : ?> aria-current="page"<?php endif; ?>>
					<?php if ( $crumb['url'] ) : ?>
					<a href="<?= \esc_url( $crumb['url'] ); ?>"><?= \esc_html( $crumb['label'] ); ?></a>
					<?php else : ?>
					<span><?= \esc_html( $crumb['label'] ); ?></span>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php endif; ?>

		<h1
			id="ble-page-banner-heading"
			class="ble-page-banner__heading font-serif font-light italic text-white leading-tight"
		>
			<?= \esc_html( $heading ); ?>
		</h1>

		<?php if ( $tagline ) : ?>
		<p class="ble-page-banner__tagline font-sans font-light text-white/85 tracking-wide mt-4">
			<?= \esc_html( $tagline ); ?>
		</p>
		<?php endif; ?>

	</div>

</section>
