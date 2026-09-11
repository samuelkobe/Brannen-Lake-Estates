<?php
/**
 * Contact Details — render template.
 *
 * Two-column layout: NAP details on the left, Google Maps on the right.
 * No additional schema needed — global LocalBusiness in inc/schema.php
 * already covers NAP and geo coordinates.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading       = \get_field( 'heading' ) ?: 'Find Us';
$map_embed_url = \get_field( 'map_embed_url' );
$address       = \get_field( 'address' );
$phone         = \get_field( 'phone' );
$phone_display = \get_field( 'phone_display' );
$email         = \get_field( 'email' );

if ( ! $map_embed_url ) {
	return;
}
?>
<section class="ble-contact-details px-6">
	<div class="max-w-6xl mx-auto">
		<div class="ble-contact-details__grid">

			<?php /* ── NAP details (left) ────────────────────────────────────── */ ?>
			<?php if ( $address || $phone || $email ) : ?>
			<div class="ble-contact-details__info" data-ble-reveal>

				<h3 class="ble-contact-details__heading font-serif font-light italic text-charcoal leading-tight">
					<?= \esc_html( $heading ); ?>
				</h3>

				<?php if ( $address ) : ?>
				<div class="ble-contact-details__item">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
						<circle cx="12" cy="10" r="3"/>
					</svg>
					<span><?= \wp_kses_post( $address ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $phone && $phone_display ) : ?>
				<div class="ble-contact-details__item">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
					</svg>
					<a href="tel:<?= \esc_attr( $phone ); ?>" class="ble-contact-details__link">
						<?= \esc_html( $phone_display ); ?>
					</a>
				</div>
				<?php endif; ?>

				<?php if ( $email ) : ?>
				<div class="ble-contact-details__item">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
						<polyline points="22,6 12,13 2,6"/>
					</svg>
					<a href="mailto:<?= \esc_attr( $email ); ?>" class="ble-contact-details__link">
						<?= \esc_html( $email ); ?>
					</a>
				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

			<?php /* ── Map (right) ───────────────────────────────────────────── */ ?>
			<div class="ble-contact-details__map" data-ble-reveal>
				<iframe
					src="<?= \esc_url( $map_embed_url ); ?>"
					width="100%"
					height="100%"
					style="border:0;"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="Brannen Lake Estates location on Google Maps"
				></iframe>
			</div>

		</div>
	</div>
</section>
