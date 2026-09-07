<?php
/**
 * CTA Banner — render template.
 *
 * Full-width call-to-action band. Used on every page.
 * Background: sage. Typography: Cormorant heading + DM Sans body/buttons.
 * Motion is handled by view.js (IntersectionObserver scroll-reveal).
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading         = \get_field( 'heading' );
$subheading      = \get_field( 'subheading' );
$primary_label   = \get_field( 'primary_cta_label' );
$primary_url     = \get_field( 'primary_cta_url' );
$secondary_label = \get_field( 'secondary_cta_label' );
$secondary_url   = \get_field( 'secondary_cta_url' );
$block_id        = 'ble-cta-' . $block['id'];

if ( ! $heading ) {
	return;
}
?>
<section
	id="<?= \esc_attr( $block_id ); ?>"
	class="ble-cta-banner w-full bg-sage py-24 px-6 overflow-hidden"
	aria-labelledby="<?= \esc_attr( $block_id ); ?>-heading"
	data-ble-animate
>
	<div class="ble-cta-banner__inner max-w-3xl mx-auto text-center">

		<h2
			id="<?= \esc_attr( $block_id ); ?>-heading"
			class="ble-cta-banner__heading font-serif font-light text-white leading-tight mb-6"
		>
			<?= \esc_html( $heading ); ?>
		</h2>

		<?php if ( $subheading ) : ?>
		<p class="ble-cta-banner__body font-sans font-light text-white/75 text-lg leading-relaxed mb-10 max-w-xl mx-auto">
			<?= \wp_kses_post( $subheading ); ?>
		</p>
		<?php endif; ?>

		<?php if ( $primary_label || $secondary_label ) : ?>
		<div class="ble-cta-banner__actions flex flex-wrap justify-center gap-4 mt-8">

			<?php if ( $primary_label && $primary_url ) : ?>
			<a
				href="<?= \esc_url( $primary_url ); ?>"
				class="ble-cta-banner__btn ble-cta-banner__btn--primary font-sans text-xs tracking-[0.2em] uppercase text-white border border-white px-10 py-3.5 inline-flex items-center"
			>
				<?= \esc_html( $primary_label ); ?>
			</a>
			<?php endif; ?>

			<?php if ( $secondary_label && $secondary_url ) : ?>
			<a
				href="<?= \esc_url( $secondary_url ); ?>"
				class="ble-cta-banner__btn ble-cta-banner__btn--secondary font-sans text-xs tracking-[0.2em] uppercase text-white/65 px-10 py-3.5 inline-flex items-center"
			>
				<?= \esc_html( $secondary_label ); ?>
			</a>
			<?php endif; ?>

		</div>
		<?php endif; ?>

	</div>
</section>
