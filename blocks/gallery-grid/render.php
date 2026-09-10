<?php
/**
 * Gallery Grid — render template.
 *
 * Masonry-style image gallery with staggered scroll reveal.
 * Uses CSS columns for the masonry layout (no JS needed).
 *
 * On mobile (single column), a random subset of images is shown
 * to prevent excessive scrolling. The full set renders in the
 * markup with a data attribute so CSS handles the visibility.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow      = \get_field( 'eyebrow' );
$heading      = \get_field( 'heading' );
$images       = \get_field( 'images' );
$mobile_count = (int) ( \get_field( 'mobile_count' ) ?: 5 );

if ( ! $heading || empty( $images ) ) {
	return;
}

// Pick random indices for mobile display.
$total        = count( $images );
$mobile_limit = min( $mobile_count, $total );
$mobile_keys  = (array) array_rand( $images, $mobile_limit );
?>
<section class="ble-gallery-grid px-6" aria-labelledby="ble-gallery-grid-heading">
	<div class="max-w-7xl 2xl:max-w-[1400px] mx-auto">

		<?php /* ── Section header ──────────────────────────────────────────── */ ?>
		<div class="text-center mb-16" data-ble-reveal>

			<?php if ( $eyebrow ) : ?>
			<p class="ble-gallery-grid__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-gallery-grid-heading"
				class="ble-gallery-grid__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

		</div>

		<?php /* ── Masonry grid ───────────────────────────────────────────── */ ?>
		<div class="ble-gallery-grid__masonry">

			<?php foreach ( $images as $index => $image ) :
				$show_on_mobile = in_array( $index, $mobile_keys, true );
				$img_alt = ! empty( $image['alt'] ) ? $image['alt'] : '';
			?>
			<div
				class="ble-gallery-grid__item<?= $show_on_mobile ? '' : ' ble-gallery-grid__item--desktop-only'; ?>"
				data-ble-reveal
			>
				<img
					src="<?= \esc_url( $image['sizes']['large'] ?? $image['url'] ); ?>"
					alt="<?= \esc_attr( $img_alt ); ?>"
					class="ble-gallery-grid__image"
					loading="lazy"
					width="<?= \esc_attr( $image['width'] ?? '' ); ?>"
					height="<?= \esc_attr( $image['height'] ?? '' ); ?>"
				/>
			</div>
			<?php endforeach; ?>

		</div>

	</div>
</section>
