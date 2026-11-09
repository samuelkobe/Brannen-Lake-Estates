<?php
/**
 * Info — render template.
 *
 * Simple text section with optional eyebrow, heading, and rich-text body.
 * Useful for standalone notes, tips, or supplementary content on any page.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$body    = \get_field( 'body' );

if ( ! $heading && ! $body ) {
	return;
}
?>
<section class="ble-info px-6">
	<div class="max-w-6xl xl:max-w-7xl mx-auto" data-ble-reveal>

		<?php if ( $eyebrow ) : ?>
		<p class="ble-info__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
			<?= \esc_html( $eyebrow ); ?>
		</p>
		<?php endif; ?>

		<?php if ( $heading ) : ?>
		<h2 class="ble-info__heading font-serif font-light italic text-charcoal leading-tight mb-6">
			<?= \esc_html( $heading ); ?>
		</h2>
		<?php endif; ?>

		<?php if ( $body ) : ?>
		<div class="ble-info__body font-sans font-light text-charcoal leading-relaxed">
			<?= \wp_kses_post( $body ); ?>
		</div>
		<?php endif; ?>

	</div>
</section>
