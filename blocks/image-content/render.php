<?php
/**
 * Image + Content — render template.
 *
 * Two-column layout: image on one side, text on the other.
 * Image position is toggled via ACF button group.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$image          = \get_field( 'image' );
$image_position = \get_field( 'image_position' ) ?: 'left';
$eyebrow        = \get_field( 'eyebrow' );
$heading        = \get_field( 'heading' );
$body           = \get_field( 'body' );
$cta            = \get_field( 'cta' );

if ( ! $image || ! $heading ) {
	return;
}

$img_src = $image['sizes']['large'] ?? $image['url'];
$img_alt = $image['alt'] ?? '';
$img_w   = $image['sizes']['large-width'] ?? $image['width'];
$img_h   = $image['sizes']['large-height'] ?? $image['height'];

$is_right = $image_position === 'right';

// Unique ID for aria-labelledby.
$uid = 'ble-image-content-' . ( $block['id'] ?? wp_unique_id() );
?>
<section
	class="ble-image-content px-6"
	aria-labelledby="<?= \esc_attr( $uid ); ?>"
>
	<div class="max-w-6xl xl:max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 xl:gap-20 items-center">

		<?php /* ── Image column ─────────────────────────────────────────── */ ?>
		<div class="ble-image-content__image <?= $is_right ? 'lg:order-2' : ''; ?>" data-ble-reveal>
			<img
				src="<?= \esc_url( $img_src ); ?>"
				alt="<?= \esc_attr( $img_alt ); ?>"
				width="<?= \esc_attr( $img_w ); ?>"
				height="<?= \esc_attr( $img_h ); ?>"
				class="w-full h-auto object-cover rounded-sm"
				loading="lazy"
			>
		</div>

		<?php /* ── Text column ──────────────────────────────────────────── */ ?>
		<div class="ble-image-content__text <?= $is_right ? 'lg:order-1' : ''; ?>" data-ble-reveal>

			<?php if ( $eyebrow ) : ?>
			<p class="ble-image-content__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="<?= \esc_attr( $uid ); ?>"
				class="ble-image-content__heading font-serif font-light italic text-charcoal leading-tight mb-8"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

			<?php if ( $body ) : ?>
			<div class="ble-image-content__body font-sans font-light text-charcoal leading-relaxed text-lg">
				<?= \wp_kses_post( \wpautop( $body ) ); ?>
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $cta['url'] ) && ! empty( $cta['title'] ) ) : ?>
			<div class="mt-10">
				<a
					href="<?= \esc_url( $cta['url'] ); ?>"
					class="ble-image-content__btn font-sans text-sm tracking-[0.18em] uppercase px-12 py-4 inline-flex items-center border border-sage text-sage"
					<?php if ( ! empty( $cta['target'] ) ) : ?>
						target="<?= \esc_attr( $cta['target'] ); ?>"
						rel="noopener noreferrer"
					<?php endif; ?>
					aria-label="<?= \esc_attr( $cta['title'] ); ?>"
				>
					<?= \esc_html( $cta['title'] ); ?>
				</a>
			</div>
			<?php endif; ?>

		</div>

	</div>
</section>
