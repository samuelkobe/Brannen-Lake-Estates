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

$eyebrow       = \get_field( 'eyebrow' );
$heading       = \get_field( 'heading' );
$subheading    = \get_field( 'subheading' );
$primary_cta   = \get_field( 'primary_cta' );
$secondary_cta = \get_field( 'secondary_cta' );
$block_id      = 'ble-cta-' . $block['id'];

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

		<?php if ( $eyebrow ) : ?>
		<p class="ble-cta-banner__eyebrow font-sans text-sm font-medium tracking-[0.2em] uppercase text-white mb-6">
			<?= \esc_html( $eyebrow ); ?>
		</p>
		<?php endif; ?>

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

		<?php if ( ! empty( $primary_cta['url'] ) || ! empty( $secondary_cta['url'] ) ) : ?>
		<div class="ble-cta-banner__actions flex flex-wrap justify-center gap-4 mt-8">

			<?php if ( ! empty( $primary_cta['url'] ) && ! empty( $primary_cta['title'] ) ) : ?>
			<a
				href="<?= \esc_url( $primary_cta['url'] ); ?>"
				class="ble-cta-banner__btn ble-cta-banner__btn--primary font-sans text-sm tracking-[0.18em] uppercase text-white border border-white px-12 py-4 inline-flex items-center"
				<?php if ( ! empty( $primary_cta['target'] ) ) : ?>
					target="<?= \esc_attr( $primary_cta['target'] ); ?>"
					rel="noopener noreferrer"
				<?php endif; ?>
				aria-label="<?= \esc_attr( $primary_cta['title'] ); ?>"
				title="<?= \esc_attr( $primary_cta['title'] ); ?>"
			>
				<?= \esc_html( $primary_cta['title'] ); ?>
			</a>
			<?php endif; ?>

			<?php if ( ! empty( $secondary_cta['url'] ) && ! empty( $secondary_cta['title'] ) ) : ?>
			<a
				href="<?= \esc_url( $secondary_cta['url'] ); ?>"
				class="ble-cta-banner__btn ble-cta-banner__btn--secondary font-sans text-sm tracking-[0.18em] uppercase text-white/65 px-12 py-4 inline-flex items-center"
				<?php if ( ! empty( $secondary_cta['target'] ) ) : ?>
					target="<?= \esc_attr( $secondary_cta['target'] ); ?>"
					rel="noopener noreferrer"
				<?php endif; ?>
				aria-label="<?= \esc_attr( $secondary_cta['title'] ); ?>"
				title="<?= \esc_attr( $secondary_cta['title'] ); ?>"
			>
				<?= \esc_html( $secondary_cta['title'] ); ?>
			</a>
			<?php endif; ?>

		</div>
		<?php endif; ?>

	</div>
</section>
