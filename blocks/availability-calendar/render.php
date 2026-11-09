<?php
/**
 * Availability Calendar — render template.
 *
 * Embeds a read-only Google Calendar iframe showing venue availability.
 * Visitors cannot book through this calendar — all inquiries go through
 * the contact form, phone, or email.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading   = \get_field( 'heading' );
$tagline   = \get_field( 'tagline' );
$embed_url = \get_field( 'calendar_embed_url' );

if ( ! $embed_url ) {
	if ( ! empty( $block['data']['is_preview'] ) ) {
		echo '<p style="padding:2rem;text-align:center;color:#999;">Enter a <strong>Google Calendar Embed URL</strong> in the block fields.</p>';
	}
	return;
}
?>
<section class="ble-availability-calendar px-6" aria-labelledby="ble-calendar-heading">
	<div class="max-w-6xl mx-auto">

		<?php /* -- Header -------------------------------------------------- */ ?>
		<div class="text-center mb-10" data-ble-reveal>
			<?php if ( $heading ) : ?>
			<h2
				id="ble-calendar-heading"
				class="ble-availability-calendar__heading font-serif font-light italic text-charcoal leading-tight mb-4"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
			<?php endif; ?>

			<?php if ( $tagline ) : ?>
			<p class="ble-availability-calendar__tagline font-sans font-light text-taupe leading-relaxed text-lg max-w-3xl mx-auto">
				<?= \wp_kses_post( $tagline ); ?>
			</p>
			<?php endif; ?>
		</div>

		<?php /* -- Calendar iframe ----------------------------------------- */ ?>
		<div class="ble-availability-calendar__iframe-wrap" data-ble-reveal>
			<iframe
				src="<?= \esc_url( $embed_url ); ?>"
				class="ble-availability-calendar__iframe"
				title="<?= \esc_attr__( 'Venue availability calendar', 'brannen-lake-estates' ); ?>"
				loading="lazy"
				frameborder="0"
				scrolling="no"
			></iframe>
		</div>

		<p class="ble-availability-calendar__disclaimer text-center font-sans text-sm text-taupe mt-6" data-ble-reveal>
			Calendar is updated regularly. Contact us to confirm availability for your preferred date.
		</p>

	</div>
</section>
