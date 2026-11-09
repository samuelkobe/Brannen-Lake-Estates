<?php
/**
 * Nearby Accommodations — render template.
 *
 * Card list of nearby lodging options with images, distances,
 * and optional map/directions links. External links are dofollow.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow        = \get_field( 'eyebrow' );
$heading         = \get_field( 'heading' );
$accommodations = \get_field( 'accommodations' );

if ( ! $heading || empty( $accommodations ) ) {
	if ( ! empty( $block['data']['is_preview'] ) ) {
		echo '<p style="padding:2rem;text-align:center;color:#999;">Add accommodations using the repeater fields above.</p>';
	}
	return;
}
?>
<section class="ble-nearby-accommodations px-6" aria-labelledby="ble-accommodations-heading">
	<div class="max-w-6xl xl:max-w-7xl mx-auto">

		<?php /* -- Header -------------------------------------------------- */ ?>
		<div class="text-center mb-12" data-ble-reveal>
			<?php if ( $eyebrow ) : ?>
			<p class="ble-nearby-accommodations__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-accommodations-heading"
				class="ble-nearby-accommodations__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
		</div>

		<?php /* -- Accommodation cards ------------------------------------- */ ?>
		<div class="ble-nearby-accommodations__list">
			<?php foreach ( $accommodations as $item ) :
				$name          = $item['name'] ?? '';
				$image_id      = $item['image'] ?? null;
				$description   = $item['description'] ?? '';
				$distance      = $item['distance'] ?? '';
				$website_url   = $item['website_url'] ?? '';
				$map_url       = $item['map_url'] ?? '';
				$directions_url = $item['directions_url'] ?? '';

				if ( ! $name ) {
					continue;
				}
			?>
			<div class="ble-nearby-accommodations__card" data-ble-reveal>
				<?php if ( $image_id ) : ?>
				<div class="ble-nearby-accommodations__image-wrap">
					<?= \wp_get_attachment_image( $image_id, 'medium_large', false, [
						'class'   => 'ble-nearby-accommodations__image',
						'loading' => 'lazy',
					] ); ?>
				</div>
				<?php endif; ?>

				<div class="ble-nearby-accommodations__content">
					<div class="ble-nearby-accommodations__header">
						<h3 class="ble-nearby-accommodations__name">
							<?php if ( $website_url ) : ?>
								<a href="<?= \esc_url( $website_url ); ?>" target="_blank" rel="noopener">
									<?= \esc_html( $name ); ?>
									<svg class="ble-nearby-accommodations__ext-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<path d="M7 17L17 7"/>
										<path d="M7 7h10v10"/>
									</svg>
								</a>
							<?php else : ?>
								<?= \esc_html( $name ); ?>
							<?php endif; ?>
						</h3>

						<?php if ( $distance ) : ?>
						<span class="ble-nearby-accommodations__distance">
							<?= \esc_html( $distance ); ?>
						</span>
						<?php endif; ?>
					</div>

					<?php if ( $description ) : ?>
					<p class="ble-nearby-accommodations__description">
						<?= \wp_kses_post( $description ); ?>
					</p>
					<?php endif; ?>

					<?php if ( $map_url || $directions_url ) : ?>
					<div class="ble-nearby-accommodations__links">
						<?php if ( $map_url ) : ?>
						<a href="<?= \esc_url( $map_url ); ?>" class="ble-nearby-accommodations__map-link" target="_blank" rel="noopener">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
								<circle cx="12" cy="10" r="3"/>
							</svg>
							View on Map
						</a>
						<?php endif; ?>

						<?php if ( $directions_url ) : ?>
						<a href="<?= \esc_url( $directions_url ); ?>" class="ble-nearby-accommodations__directions-link" target="_blank" rel="noopener">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M12 2L2 7l10 5 10-5-10-5z"/>
								<path d="M2 17l10 5 10-5"/>
								<path d="M2 12l10 5 10-5"/>
							</svg>
							Get Directions
						</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
