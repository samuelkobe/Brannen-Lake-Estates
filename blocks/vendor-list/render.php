<?php
/**
 * Vendor List — render template.
 *
 * Queries all published ble_vendor posts, groups them by ble_vendor_type
 * taxonomy terms, and renders a card grid for each vendor type.
 * External links are dofollow (genuine editorial endorsements).
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow = \get_field( 'eyebrow' );
$heading = \get_field( 'heading' );
$body    = \get_field( 'body' );

// Get all vendor type terms that have at least one vendor.
$terms = \get_terms( [
	'taxonomy'   => 'ble_vendor_type',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );

if ( \is_wp_error( $terms ) || empty( $terms ) ) {
	// Nothing to show — render nothing on the frontend,
	// but show a placeholder message in the editor.
	if ( ! empty( $block['data']['is_preview'] ) ) {
		echo '<p style="padding:2rem;text-align:center;color:#999;">Add vendors under <strong>Vendors</strong> in the sidebar and assign them a <strong>Vendor Type</strong>.</p>';
	}
	return;
}
?>
<section class="ble-vendor-list px-6" aria-labelledby="ble-vendor-list-heading">
	<div class="max-w-6xl xl:max-w-7xl mx-auto">

		<?php /* -- Header -------------------------------------------------- */ ?>
		<div class="mb-12" data-ble-reveal>
			<?php if ( $eyebrow ) : ?>
			<p class="ble-vendor-list__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-vendor-list-heading"
				class="ble-vendor-list__heading font-serif font-light italic text-charcoal leading-tight mb-6"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

			<?php if ( $body ) : ?>
			<p class="ble-vendor-list__body font-sans font-light text-charcoal leading-relaxed text-lg max-w-3xl">
				<?= \wp_kses_post( $body ); ?>
			</p>
			<?php endif; ?>
		</div>

		<?php /* -- Vendor groups by type ----------------------------------- */ ?>
		<?php foreach ( $terms as $term ) :
			$vendors = \get_posts( [
				'post_type'      => 'ble_vendor',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'ble_vendor_type',
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					],
				],
			] );

			if ( empty( $vendors ) ) {
				continue;
			}
		?>
		<div class="ble-vendor-list__group" data-ble-reveal>
			<h3 class="ble-vendor-list__group-heading font-sans text-sm tracking-[0.15em] uppercase text-sage mb-6">
				<?= \esc_html( $term->name ); ?>
			</h3>

			<div class="ble-vendor-list__grid">
				<?php foreach ( $vendors as $vendor ) :
					$website     = \get_field( 'website', $vendor->ID );
					$email       = \get_field( 'email', $vendor->ID );
					$phone       = \get_field( 'phone', $vendor->ID );
					$description = \get_field( 'description', $vendor->ID );
					$logo_id     = \get_field( 'logo', $vendor->ID );
					$name        = \get_the_title( $vendor );
					$initial     = \mb_strtoupper( \mb_substr( $name, 0, 1 ) );
				?>
				<div class="ble-vendor-list__card">
					<div class="ble-vendor-list__avatar">
						<?php if ( $logo_id ) : ?>
							<?= \wp_get_attachment_image( $logo_id, 'thumbnail', false, [
								'class'   => 'ble-vendor-list__avatar-img',
								'loading' => 'lazy',
							] ); ?>
						<?php else : ?>
							<span class="ble-vendor-list__avatar-initial" aria-hidden="true">
								<?= \esc_html( $initial ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="ble-vendor-list__info">
						<h4 class="ble-vendor-list__name">
							<?php if ( $website ) : ?>
								<a href="<?= \esc_url( $website ); ?>" target="_blank" rel="noopener">
									<?= \esc_html( $name ); ?>
									<svg class="ble-vendor-list__ext-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<path d="M7 17L17 7"/>
										<path d="M7 7h10v10"/>
									</svg>
								</a>
							<?php else : ?>
								<?= \esc_html( $name ); ?>
							<?php endif; ?>
						</h4>

						<?php if ( $description ) : ?>
						<div class="ble-vendor-list__description">
							<?= \wp_kses_post( \wpautop( $description ) ); ?>
						</div>
						<?php endif; ?>

						<?php if ( $email ) : ?>
						<p class="ble-vendor-list__email">
							<a href="mailto:<?= \esc_attr( $email ); ?>">
								<?= \esc_html( $email ); ?>
							</a>
						</p>
						<?php endif; ?>

						<?php if ( $phone ) : ?>
						<p class="ble-vendor-list__phone">
							<a href="tel:<?= \esc_attr( \preg_replace( '/[^+\d]/', '', $phone ) ); ?>">
								<?= \esc_html( $phone ); ?>
							</a>
						</p>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>

	</div>
</section>
