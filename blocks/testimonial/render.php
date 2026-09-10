<?php
/**
 * Testimonial — render template.
 *
 * Displays a single randomly selected testimonial from a repeater.
 * Each page load shows a different quote, keeping the homepage fresh.
 * In the editor preview, the first testimonial is always shown.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$items   = \get_field( 'items' );

if ( empty( $items ) ) {
	return;
}

// In the editor, always show the first. On the frontend, pick randomly.
$index = ! empty( $is_preview ) ? 0 : array_rand( $items );
$item  = $items[ $index ];

$quote = $item['quote'] ?? '';
$name  = $item['name'] ?? '';
$date  = $item['date'] ?? '';

if ( ! $quote ) {
	return;
}
?>
<section class="ble-testimonial px-6" aria-labelledby="ble-testimonial-heading">
	<div class="max-w-3xl mx-auto text-center">

		<?php /* ── Section header ──────────────────────────────────────────── */ ?>
		<?php if ( $eyebrow || $heading ) : ?>
		<div class="mb-12" data-ble-reveal>

			<?php if ( $eyebrow ) : ?>
			<p class="ble-testimonial__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
			<h2
				id="ble-testimonial-heading"
				class="ble-testimonial__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
			<?php endif; ?>

		</div>
		<?php endif; ?>

		<?php /* ── Quote ──────────────────────────────────────────────────── */ ?>
		<blockquote class="ble-testimonial__quote" data-ble-reveal>

			<p class="ble-testimonial__text font-serif italic text-charcoal leading-relaxed">
				"<?= \esc_html( $quote ); ?>"
			</p>

			<footer class="ble-testimonial__footer mt-6">
				<cite class="ble-testimonial__name font-sans font-medium text-charcoal not-italic tracking-wide">
					<?= \esc_html( $name ); ?>
				</cite>

				<?php if ( $date ) : ?>
				<span class="ble-testimonial__date font-sans text-sm text-charcoal/50 ml-2">
					&mdash; <?= \esc_html( $date ); ?>
				</span>
				<?php endif; ?>
			</footer>

		</blockquote>

	</div>
</section>
