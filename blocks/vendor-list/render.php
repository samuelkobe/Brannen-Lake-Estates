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
					$booking     = \get_field( 'booking_url', $vendor->ID );
					$instagram   = \get_field( 'instagram', $vendor->ID );
					$email       = \get_field( 'email', $vendor->ID );
					$phone       = \get_field( 'phone', $vendor->ID );
					$description = \get_field( 'description', $vendor->ID );
					$logo_id     = \get_field( 'logo', $vendor->ID );
					$name        = \get_the_title( $vendor );
					$initial     = \mb_strtoupper( \mb_substr( $name, 0, 1 ) );
					$has_actions = $website || $booking || $instagram || $email || $phone;
					$new_tab     = \__( '(opens in a new tab)', 'brannen-lake-estates' );
				?>
				<div class="ble-vendor-list__card">
					<div class="ble-vendor-list__top">
						<div class="ble-vendor-list__avatar">
							<?php if ( $logo_id ) : ?>
								<?= \wp_get_attachment_image( $logo_id, 'thumbnail', false, [
									'class'   => 'ble-vendor-list__avatar-img',
									'loading' => 'lazy',
									// Media Library alt text wins; fall back to "<name> logo".
									'alt'     => \get_post_meta( $logo_id, '_wp_attachment_image_alt', true ) ?: \sprintf( '%s logo', $name ),
								] ); ?>
							<?php else : ?>
								<span class="ble-vendor-list__avatar-initial" aria-hidden="true">
									<?= \esc_html( $initial ); ?>
								</span>
							<?php endif; ?>
						</div>

						<div class="ble-vendor-list__info">
							<h4 class="ble-vendor-list__name"><?= \esc_html( $name ); ?></h4>

							<?php if ( $description ) : ?>
							<div class="ble-vendor-list__description">
								<?php
								// Text formatting only. Links belong in the action row, so any
								// link left in older descriptions renders as plain text.
								echo \wp_kses( \wpautop( $description ), [
									'p'      => [],
									'br'     => [],
									'strong' => [],
									'b'      => [],
									'em'     => [],
									'i'      => [],
									'ul'     => [],
									'ol'     => [],
									'li'     => [],
								] );
								?>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( $has_actions ) : ?>
					<div class="ble-vendor-list__actions">
						<?php if ( $website ) : ?>
						<a
							class="ble-vendor-list__btn ble-vendor-list__btn--primary"
							href="<?= \esc_url( $website ); ?>"
							target="_blank"
							rel="noopener"
							aria-label="<?= \esc_attr( \sprintf( 'Visit %s website %s', $name, $new_tab ) ); ?>"
							title="<?= \esc_attr( \sprintf( 'Visit %s website', $name ) ); ?>"
						>
							<?= \esc_html__( 'Visit website', 'brannen-lake-estates' ); ?>
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
						</a>
						<?php endif; ?>

						<?php if ( $booking ) : ?>
						<a
							class="ble-vendor-list__btn"
							href="<?= \esc_url( $booking ); ?>"
							target="_blank"
							rel="nofollow noopener"
							aria-label="<?= \esc_attr( \sprintf( 'Book with %s %s', $name, $new_tab ) ); ?>"
							title="<?= \esc_attr( \sprintf( 'Book with %s', $name ) ); ?>"
						>
							<?= \esc_html__( 'Book now', 'brannen-lake-estates' ); ?>
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7 17L17 7"/><path d="M7 7h10v10"/></svg>
						</a>
						<?php endif; ?>

						<?php if ( $instagram ) : ?>
						<a
							class="ble-vendor-list__btn<?= $website ? '' : ' ble-vendor-list__btn--primary'; ?>"
							href="<?= \esc_url( $instagram ); ?>"
							target="_blank"
							rel="noopener"
							aria-label="<?= \esc_attr( \sprintf( '%s on Instagram %s', $name, $new_tab ) ); ?>"
							title="<?= \esc_attr( \sprintf( '%s on Instagram', $name ) ); ?>"
						>
							<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.75" fill="currentColor"/></svg>
							<?= \esc_html__( 'Instagram', 'brannen-lake-estates' ); ?>
						</a>
						<?php endif; ?>

						<?php if ( $email || $phone ) : ?>
						<span class="ble-vendor-list__contact">
							<?php if ( $email ) : ?>
							<a
								class="ble-vendor-list__icon-link"
								href="mailto:<?= \esc_attr( $email ); ?>"
								aria-label="<?= \esc_attr( \sprintf( 'Email %s at %s', $name, $email ) ); ?>"
								title="<?= \esc_attr( $email ); ?>"
							>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M3.5 6.5l8.5 6 8.5-6"/></svg>
								<?= \esc_html__( 'Email', 'brannen-lake-estates' ); ?>
							</a>
							<?php endif; ?>

							<?php if ( $phone ) : ?>
							<a
								class="ble-vendor-list__icon-link"
								href="tel:<?= \esc_attr( \preg_replace( '/[^+\d]/', '', $phone ) ); ?>"
								aria-label="<?= \esc_attr( \sprintf( 'Call %s at %s', $name, $phone ) ); ?>"
								title="<?= \esc_attr( $phone ); ?>"
							>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
								<?= \esc_html__( 'Call', 'brannen-lake-estates' ); ?>
							</a>
							<?php endif; ?>
						</span>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>

	</div>
</section>
