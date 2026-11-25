<?php
/**
 * Testimonial — render template.
 *
 * Shows one testimonial from the Testimonials post type, filtered by the
 * block's Show setting. For "random", every matching quote is printed and
 * view.ts reveals one at random in the browser, so it still varies when the
 * host serves a cached page. Without JS (and in the editor) the first shows.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$source  = \get_field( 'source' ) ?: 'all';
$types   = (array) ( \get_field( 'types' ) ?: [] );
$order   = \get_field( 'order' ) ?: 'random';

$items = get_testimonials( $source, $types, $order );

if ( ! $items ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p style="padding:2rem;text-align:center;opacity:.6">' . \esc_html__( 'No testimonials match this block\'s settings yet. Add them under Testimonials in the admin menu.', 'brannen-lake-estates' ) . '</p>';
	}
	return;
}

if ( 'random' !== $order || ! empty( $is_preview ) ) {
	$items = [ $items[0] ];
}

$heading_id = 'ble-testimonial-heading-' . \substr( \md5( $block['id'] ?? \uniqid() ), 0, 6 );
?>
<section class="ble-testimonial px-6"<?php if ( $heading ) : ?> aria-labelledby="<?= \esc_attr( $heading_id ); ?>"<?php endif; ?><?php if ( \count( $items ) > 1 ) : ?> data-ble-testimonial-random<?php endif; ?>>
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
				id="<?= \esc_attr( $heading_id ); ?>"
				class="ble-testimonial__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
			<?php endif; ?>

		</div>
		<?php endif; ?>

		<?php /* ── Quote(s) ───────────────────────────────────────────────── */ ?>
		<?php foreach ( $items as $i => $item ) : ?>
		<blockquote class="ble-testimonial__quote" data-ble-reveal<?= $i > 0 ? ' hidden' : ''; ?>>

			<p class="ble-testimonial__text font-serif italic text-charcoal leading-relaxed">
				&ldquo;<?= \esc_html( $item['quote'] ); ?>&rdquo;
			</p>

			<footer class="ble-testimonial__footer mt-6">
				<cite class="ble-testimonial__name font-sans font-medium text-charcoal not-italic tracking-wide">
					<?= \esc_html( $item['name'] ); ?>
				</cite>

				<?php if ( $item['date'] ) : ?>
				<span class="ble-testimonial__date font-sans text-sm text-charcoal/50 ml-2">
					&mdash; <?= \esc_html( $item['date'] ); ?>
				</span>
				<?php endif; ?>
			</footer>

		</blockquote>
		<?php endforeach; ?>

	</div>
</section>
