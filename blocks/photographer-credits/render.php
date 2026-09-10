<?php
/**
 * Photographer Credits — render template.
 *
 * Grid of photographer cards with external portfolio links.
 * Links are dofollow (genuine editorial endorsements, not paid).
 * Outputs Person schema with sameAs for each photographer.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow       = \get_field( 'eyebrow' );
$heading        = \get_field( 'heading' );
$body           = \get_field( 'body' );
$photographers = \get_field( 'photographers' );

if ( ! $heading || empty( $photographers ) ) {
	return;
}
?>
<section class="ble-photographer-credits px-6" aria-labelledby="ble-photographer-credits-heading">
	<div class="max-w-5xl mx-auto">

		<?php /* ── Header ─────────────────────────────────────────────────── */ ?>
		<div class="text-center mb-12" data-ble-reveal>
			<?php if ( $eyebrow ) : ?>
			<p class="ble-photographer-credits__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-photographer-credits-heading"
				class="ble-photographer-credits__heading font-serif font-light italic text-charcoal leading-tight mb-6"
			>
				<?= \esc_html( $heading ); ?>
			</h2>

			<?php if ( $body ) : ?>
			<p class="ble-photographer-credits__body font-sans font-light text-charcoal leading-relaxed text-lg max-w-3xl mx-auto">
				<?= \esc_html( $body ); ?>
			</p>
			<?php endif; ?>
		</div>

		<?php /* ── Photographer grid ─────────────────────────────────────── */ ?>
		<div class="ble-photographer-credits__grid" data-ble-reveal>
			<?php foreach ( $photographers as $photographer ) :
				$name = $photographer['name'];
				$url  = $photographer['portfolio_url'];
				$photo_id = $photographer['photo'] ?? null;
				$initial  = \mb_strtoupper( \mb_substr( $name, 0, 1 ) );
			?>
			<a
				href="<?= \esc_url( $url ); ?>"
				class="ble-photographer-credits__card"
				target="_blank"
				rel="noopener"
				aria-label="<?= \esc_attr( $name ); ?> — view portfolio"
			>
				<div class="ble-photographer-credits__avatar">
					<?php if ( $photo_id ) : ?>
						<?= \wp_get_attachment_image( $photo_id, 'thumbnail', false, [
							'class'   => 'ble-photographer-credits__avatar-img',
							'loading' => 'lazy',
						] ); ?>
					<?php else : ?>
						<span class="ble-photographer-credits__avatar-initial" aria-hidden="true">
							<?= \esc_html( $initial ); ?>
						</span>
					<?php endif; ?>
				</div>

				<h3 class="ble-photographer-credits__name">
					<?= \esc_html( $name ); ?>
				</h3>

				<span class="ble-photographer-credits__link-label">
					View Portfolio
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M7 17L17 7"/>
						<path d="M7 7h10v10"/>
					</svg>
				</span>
			</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<?php
// Person structured data for each photographer.
$person_schemas = [];
foreach ( $photographers as $photographer ) {
	$person = [
		'@type'  => 'Person',
		'name'   => $photographer['name'],
		'sameAs' => $photographer['portfolio_url'],
	];

	if ( ! empty( $photographer['photo'] ) ) {
		$img_src = \wp_get_attachment_image_url( $photographer['photo'], 'medium' );
		if ( $img_src ) {
			$person['image'] = $img_src;
		}
	}

	$person_schemas[] = $person;
}

$schema = [
	'@context' => 'https://schema.org',
	'@type'    => 'ItemList',
	'name'     => 'Photographers at Brannen Lake Estates',
	'itemListElement' => \array_map(
		fn( $p, $i ) => [
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'item'     => $p,
		],
		$person_schemas,
		\array_keys( $person_schemas )
	),
];
?>
<script type="application/ld+json"><?= \wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
