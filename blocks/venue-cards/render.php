<?php
/**
 * Venue Cards — render template.
 *
 * Responsive card grid showcasing each ceremony setting.
 * 1 col on mobile, 2 on tablet, up to 4 on desktop.
 * Each card: image, title, short description, optional link.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$items   = \get_field( 'items' );

if ( ! $heading || empty( $items ) ) {
	return;
}
?>
<section class="ble-venue-cards py-24 lg:py-32 px-6" aria-labelledby="ble-venue-cards-heading">
	<div class="max-w-6xl xl:max-w-7xl 2xl:max-w-[1400px] mx-auto">

		<?php /* ── Section header ──────────────────────────────────────────── */ ?>
		<div class="text-center mb-16" data-ble-reveal>

			<?php if ( $eyebrow ) : ?>
			<p class="ble-venue-cards__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-venue-cards-heading"
				class="ble-venue-cards__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

		</div>

		<?php /* ── Card grid ──────────────────────────────────────────────── */ ?>
		<div class="ble-venue-cards__grid">

			<?php foreach ( $items as $item ) :
				$image = $item['image'] ?? null;
				$title = $item['title'] ?? '';
				$desc  = $item['description'] ?? '';
				$link  = $item['link'] ?? null;
				$tag   = ! empty( $link['url'] ) ? 'a' : 'div';
				$img_alt = ! empty( $image['alt'] ) ? $image['alt'] : \esc_attr( $title );
			?>

			<<?= $tag; ?>
				class="ble-venue-cards__card"
				data-ble-reveal
				<?php if ( $tag === 'a' ) : ?>
					href="<?= \esc_url( $link['url'] ); ?>"
					<?php if ( ! empty( $link['target'] ) ) : ?>
						target="<?= \esc_attr( $link['target'] ); ?>"
						rel="noopener noreferrer"
					<?php endif; ?>
					aria-label="<?= \esc_attr( $link['title'] ?? $title ); ?>"
				<?php endif; ?>
			>

				<?php if ( $image ) : ?>
				<div class="ble-venue-cards__image-wrap">
					<img
						src="<?= \esc_url( $image['url'] ); ?>"
						alt="<?= \esc_attr( $img_alt ); ?>"
						class="ble-venue-cards__image"
						loading="lazy"
						width="<?= \esc_attr( $image['width'] ?? '' ); ?>"
						height="<?= \esc_attr( $image['height'] ?? '' ); ?>"
					/>
				</div>
				<?php endif; ?>

				<h3 class="ble-venue-cards__title font-serif font-light italic text-charcoal">
					<?= \esc_html( $title ); ?>
				</h3>

				<?php if ( $desc ) : ?>
				<p class="ble-venue-cards__desc font-sans font-light text-charcoal leading-relaxed">
					<?= \esc_html( $desc ); ?>
				</p>
				<?php endif; ?>

			</<?= $tag; ?>>

			<?php endforeach; ?>

		</div>

	</div>
</section>
