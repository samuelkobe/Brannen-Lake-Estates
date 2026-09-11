<?php
/**
 * Image + Form — render template.
 *
 * Two-column layout: image on one side, form on the other.
 * The form is rendered from a shortcode (e.g. Contact Form 7).
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$image          = \get_field( 'image' );
$image_position = \get_field( 'image_position' ) ?: 'left';
$bg_color       = \get_field( 'bg_color' ) ?: 'ivory';
$anchor_id      = \get_field( 'anchor_id' );
$eyebrow        = \get_field( 'eyebrow' );
$heading        = \get_field( 'heading' );
$description    = \get_field( 'description' );
$shortcode      = \get_field( 'shortcode' );

if ( ! $heading || ! $shortcode ) {
	return;
}

$img_src = $image['sizes']['large'] ?? $image['url'] ?? '';
$img_alt = $image['alt'] ?? '';
$img_w   = $image['sizes']['large-width'] ?? $image['width'] ?? '';
$img_h   = $image['sizes']['large-height'] ?? $image['height'] ?? '';

$is_right = $image_position === 'right';

$uid = 'ble-image-form-' . ( $block['id'] ?? wp_unique_id() );
?>
<section
	<?php if ( $anchor_id ) : ?>id="<?= \esc_attr( \sanitize_title( $anchor_id ) ); ?>"<?php endif; ?>
	class="ble-image-form ble-image-form--<?= \esc_attr( $bg_color ); ?> px-6"
	aria-labelledby="<?= \esc_attr( $uid ); ?>"
>
	<div class="max-w-6xl xl:max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 xl:gap-20 items-center">

		<?php /* ── Image column ─────────────────────────────────────────── */ ?>
		<?php if ( $img_src ) : ?>
		<div class="ble-image-form__image <?= $is_right ? 'lg:order-2' : ''; ?>" data-ble-reveal>
			<img
				src="<?= \esc_url( $img_src ); ?>"
				alt="<?= \esc_attr( $img_alt ); ?>"
				width="<?= \esc_attr( $img_w ); ?>"
				height="<?= \esc_attr( $img_h ); ?>"
				class="w-full h-auto object-cover rounded-sm"
				loading="lazy"
			>
		</div>
		<?php endif; ?>

		<?php /* ── Form column ──────────────────────────────────────────── */ ?>
		<div class="ble-image-form__content <?= $is_right ? 'lg:order-1' : ''; ?>" data-ble-reveal>

			<?php if ( $eyebrow ) : ?>
			<p class="ble-image-form__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="<?= \esc_attr( $uid ); ?>"
				class="ble-image-form__heading font-serif font-light italic text-charcoal leading-tight mb-8"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

			<?php if ( $description ) : ?>
			<p class="ble-image-form__desc font-sans font-light text-charcoal leading-relaxed text-lg mb-8">
				<?= \esc_html( $description ); ?>
			</p>
			<?php endif; ?>

			<div class="ble-image-form__form">
				<?= \do_shortcode( $shortcode ); ?>
			</div>

		</div>

	</div>
</section>
