<?php
/**
 * Welcome Intro — render template.
 *
 * Centered text section introducing the venue's story and heritage.
 * Sits directly below the hero to establish identity before
 * showing venue features.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$body    = \get_field( 'body' );
$cta     = \get_field( 'cta' );

if ( ! $heading ) {
	return;
}
?>
<section class="ble-welcome-intro py-24 lg:py-32 px-6" aria-labelledby="ble-welcome-heading">
	<div class="max-w-3xl mx-auto text-center" data-ble-reveal>

		<?php if ( $eyebrow ) : ?>
		<p class="ble-welcome-intro__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
			<?= \esc_html( $eyebrow ); ?>
		</p>
		<?php endif; ?>

		<h2
			id="ble-welcome-heading"
			class="ble-welcome-intro__heading font-serif font-light italic text-charcoal leading-tight mb-8"
		>
			<?= \esc_html( $heading ); ?>
		</h2>

		<p class="ble-welcome-intro__body font-sans font-light text-charcoal leading-relaxed text-lg">
			<?= \esc_html( $body ); ?>
		</p>

		<?php if ( ! empty( $cta['url'] ) && ! empty( $cta['title'] ) ) : ?>
		<div class="mt-10">
			<a
				href="<?= \esc_url( $cta['url'] ); ?>"
				class="ble-welcome-intro__btn font-sans text-sm tracking-[0.18em] uppercase px-10 py-3.5 inline-flex items-center"
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
</section>
