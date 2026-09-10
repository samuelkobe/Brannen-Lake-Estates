<?php
/**
 * FAQ — render template.
 *
 * Accordion-style FAQ section. Each question expands/collapses on click.
 * Uses native <details>/<summary> for no-JS accessibility, enhanced
 * with a view script for smooth open/close animation.
 *
 * Outputs FAQPage structured data (JSON-LD) for rich results in Google.
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
<section class="ble-faq px-6" aria-labelledby="ble-faq-heading">
	<div class="max-w-3xl mx-auto" data-ble-reveal>

		<div class="text-center mb-12">
			<?php if ( $eyebrow ) : ?>
			<p class="ble-faq__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<h2
				id="ble-faq-heading"
				class="ble-faq__heading font-serif font-light italic text-charcoal leading-tight"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
		</div>

		<div class="ble-faq__list">
			<?php foreach ( $items as $i => $item ) : ?>
			<details class="ble-faq__item" data-ble-faq-item>
				<summary class="ble-faq__question">
					<span class="ble-faq__question-text"><?= \esc_html( $item['question'] ); ?></span>
					<span class="ble-faq__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
							<line x1="10" y1="4" x2="10" y2="16" class="ble-faq__icon-vertical" />
							<line x1="4" y1="10" x2="16" y2="10" />
						</svg>
					</span>
				</summary>
				<div class="ble-faq__answer">
					<?= \wp_kses_post( $item['answer'] ); ?>
				</div>
			</details>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<?php
// FAQPage structured data for Google rich results.
$faq_schema = [
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => [],
];

foreach ( $items as $item ) {
	$faq_schema['mainEntity'][] = [
		'@type'          => 'Question',
		'name'           => $item['question'],
		'acceptedAnswer' => [
			'@type' => 'Answer',
			'text'  => wp_strip_all_tags( $item['answer'] ),
		],
	];
}
?>
<script type="application/ld+json"><?= wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
