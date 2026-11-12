<?php
/**
 * Nearby Accommodations — render template.
 *
 * Card list of nearby lodging options with images, distances,
 * and optional map/directions links. External links are dofollow.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block      ACF block metadata.
 * @var bool  $is_preview True inside the block editor.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow        = \get_field( 'eyebrow' );
$heading         = \get_field( 'heading' );
$accommodations = \get_field( 'accommodations' );

if ( ! $heading || empty( $accommodations ) ) {
	if ( ! empty( $is_preview ) ) {
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
					<?php
					$img_attrs = [
						'class'   => 'ble-nearby-accommodations__image',
						'loading' => 'lazy',
					];
					if ( ! \get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) {
						$img_attrs['alt'] = $name;
					}
					echo \wp_get_attachment_image( $image_id, 'medium_large', false, $img_attrs );
					?>
				</div>
				<?php endif; ?>

				<div class="ble-nearby-accommodations__content">
					<div class="ble-nearby-accommodations__header">
						<h3 class="ble-nearby-accommodations__name"><?= \esc_html( $name ); ?></h3>

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

					<?php if ( $website_url || $map_url || $directions_url ) : ?>
					<div class="ble-nearby-accommodations__actions">
						<?php if ( $website_url ) : ?>
						<a
							class="ble-nearby-accommodations__btn"
							href="<?= \esc_url( $website_url ); ?>"
							target="_blank"
							rel="noopener"
							aria-label="<?= \esc_attr( \sprintf( 'Visit %s website (opens in a new tab)', $name ) ); ?>"
							title="<?= \esc_attr( \sprintf( 'Visit %s website', $name ) ); ?>"
						>
							Visit website
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
						</a>
						<?php endif; ?>

						<?php if ( $map_url || $directions_url ) : ?>
						<span class="ble-nearby-accommodations__map-links">
							<?php if ( $map_url ) : ?>
							<a
								class="ble-nearby-accommodations__icon-link"
								href="<?= \esc_url( $map_url ); ?>"
								target="_blank"
								rel="noopener"
								aria-label="<?= \esc_attr( \sprintf( 'View %s on Google Maps (opens in a new tab)', $name ) ); ?>"
							>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
								View on map
							</a>
							<?php endif; ?>

							<?php if ( $directions_url ) : ?>
							<a
								class="ble-nearby-accommodations__icon-link"
								href="<?= \esc_url( $directions_url ); ?>"
								target="_blank"
								rel="noopener"
								aria-label="<?= \esc_attr( \sprintf( 'Directions from Brannen Lake Estates to %s (opens in a new tab)', $name ) ); ?>"
							>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
								Directions
							</a>
							<?php endif; ?>
						</span>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
