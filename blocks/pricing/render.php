<?php
/**
 * Pricing — render template.
 *
 * Grouped pricing display. Each section uses one of two layouts:
 *   - cards: package cards (name, venue, capacity, price, includes, link)
 *   - list:  menu-style rows (name, description, price) for add-ons
 * Optional footnote and CTA button below all sections.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block      ACF block metadata.
 * @var bool  $is_preview True inside the block editor.
 */

namespace WebOk\BrannenLakeEstates;

$eyebrow  = \get_field( 'eyebrow' );
$heading  = \get_field( 'heading' );
$intro    = \get_field( 'intro' );
$sections = \get_field( 'sections' );
$footnote = \get_field( 'footnote' );
$cta      = \get_field( 'cta' );
$block_id = 'ble-pricing-' . $block['id'];

if ( empty( $sections ) ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p class="ble-pricing__placeholder">' . \esc_html__( 'Pricing: add a section in the block settings.', 'brannen-lake-estates' ) . '</p>';
	}
	return;
}

/**
 * Split a textarea into trimmed, non-empty lines.
 */
$to_lines = static function ( ?string $text ): array {
	if ( ! $text ) {
		return [];
	}
	return \array_values( \array_filter( \array_map( 'trim', \preg_split( '/\r\n|\r|\n/', $text ) ) ) );
};

/**
 * Print the price + note pair.
 */
$print_price = static function ( array $item, string $base ): void {
	$price = $item['price'] ?? '';
	$note  = $item['price_note'] ?? '';
	if ( ! $price ) {
		return;
	}
	?>
	<p class="<?= \esc_attr( $base ); ?>__price">
		<span class="<?= \esc_attr( $base ); ?>__amount font-serif"><?= \esc_html( $price ); ?></span>
		<?php if ( $note ) : ?>
		<span class="<?= \esc_attr( $base ); ?>__note font-sans"><?= \esc_html( $note ); ?></span>
		<?php endif; ?>
	</p>
	<?php
};
?>
<section
	id="<?= \esc_attr( $block_id ); ?>"
	class="ble-pricing px-6"
	<?php if ( $heading ) : ?>aria-labelledby="<?= \esc_attr( $block_id ); ?>-heading"<?php endif; ?>
>
	<div class="max-w-6xl xl:max-w-7xl mx-auto">

		<?php if ( $eyebrow || $heading || $intro ) : ?>
		<header class="ble-pricing__header text-center mb-14" data-ble-reveal>
			<?php if ( $eyebrow ) : ?>
			<p class="ble-pricing__eyebrow font-sans text-xs tracking-[0.2em] uppercase text-sage mb-6">
				<?= \esc_html( $eyebrow ); ?>
			</p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
			<h2
				id="<?= \esc_attr( $block_id ); ?>-heading"
				class="ble-pricing__heading font-serif font-light italic text-charcoal leading-tight mb-6"
			>
				<?= \esc_html( $heading ); ?>
			</h2>
			<?php endif; ?>

			<?php if ( $intro ) : ?>
			<p class="ble-pricing__intro font-sans font-light text-charcoal leading-relaxed mx-auto">
				<?= \esc_html( $intro ); ?>
			</p>
			<?php endif; ?>
		</header>
		<?php endif; ?>

		<?php foreach ( $sections as $section ) :
			$items  = $section['items'] ?? [];
			$layout = ( $section['layout'] ?? 'cards' ) === 'list' ? 'list' : 'cards';
			if ( empty( $items ) ) {
				continue;
			}
		?>
		<div class="ble-pricing__section ble-pricing__section--<?= \esc_attr( $layout ); ?>">

			<?php if ( ! empty( $section['section_heading'] ) ) : ?>
			<h3 class="ble-pricing__section-heading font-serif font-light text-charcoal text-center" data-ble-reveal>
				<?= \esc_html( $section['section_heading'] ); ?>
			</h3>
			<?php endif; ?>

			<?php if ( 'cards' === $layout ) : ?>
			<ul class="ble-pricing__cards" role="list">
				<?php foreach ( $items as $item ) :
					if ( empty( $item['name'] ) ) {
						continue;
					}
					$includes = $to_lines( $item['includes'] ?? '' );
					$link     = $item['link'] ?? null;
				?>
				<li class="ble-pricing__card" data-ble-reveal>

					<div class="ble-pricing__card-top">
						<?php if ( ! empty( $item['venue'] ) ) : ?>
						<p class="ble-pricing__venue font-sans text-xs tracking-[0.2em] uppercase text-sage">
							<?= \esc_html( $item['venue'] ); ?>
						</p>
						<?php endif; ?>

						<h4 class="ble-pricing__name font-serif font-light text-charcoal leading-tight">
							<?= \esc_html( $item['name'] ); ?>
						</h4>

						<?php if ( ! empty( $item['capacity'] ) ) : ?>
						<p class="ble-pricing__capacity font-sans"><?= \esc_html( $item['capacity'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php $print_price( $item, 'ble-pricing' ); ?>

					<?php if ( ! empty( $item['description'] ) ) : ?>
					<p class="ble-pricing__desc font-sans font-light text-charcoal leading-relaxed">
						<?= \esc_html( $item['description'] ); ?>
					</p>
					<?php endif; ?>

					<?php if ( $includes ) : ?>
					<ul class="ble-pricing__includes font-sans font-light text-charcoal" role="list">
						<?php foreach ( $includes as $line ) : ?>
						<li><?= \esc_html( $line ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<?php if ( ! empty( $link['url'] ) && ! empty( $link['title'] ) ) : ?>
					<a
						href="<?= \esc_url( $link['url'] ); ?>"
						class="ble-pricing__link font-sans text-xs tracking-[0.18em] uppercase"
						<?php if ( ! empty( $link['target'] ) ) : ?>
							target="<?= \esc_attr( $link['target'] ); ?>"
							rel="noopener noreferrer"
						<?php endif; ?>
					>
						<?= \esc_html( $link['title'] ); ?>
					</a>
					<?php endif; ?>

				</li>
				<?php endforeach; ?>
			</ul>

			<?php else : ?>
			<ul class="ble-pricing__list" role="list" data-ble-reveal>
				<?php foreach ( $items as $item ) :
					if ( empty( $item['name'] ) ) {
						continue;
					}
				?>
				<li class="ble-pricing__row">
					<div class="ble-pricing__row-text">
						<p class="ble-pricing__row-name font-serif text-charcoal"><?= \esc_html( $item['name'] ); ?></p>
						<?php if ( ! empty( $item['description'] ) ) : ?>
						<p class="ble-pricing__row-desc font-sans font-light"><?= \esc_html( $item['description'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php $print_price( $item, 'ble-pricing__row' ); ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		</div>
		<?php endforeach; ?>

		<?php if ( $footnote || ! empty( $cta['url'] ) ) : ?>
		<footer class="ble-pricing__footer text-center" data-ble-reveal>
			<?php if ( $footnote ) : ?>
			<p class="ble-pricing__footnote font-sans font-light mx-auto"><?= \wp_kses( $footnote, [ 'br' => [] ] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $cta['url'] ) && ! empty( $cta['title'] ) ) : ?>
			<a
				href="<?= \esc_url( $cta['url'] ); ?>"
				class="ble-pricing__cta font-sans text-sm tracking-[0.18em] uppercase px-12 py-4 inline-flex items-center"
				<?php if ( ! empty( $cta['target'] ) ) : ?>
					target="<?= \esc_attr( $cta['target'] ); ?>"
					rel="noopener noreferrer"
				<?php endif; ?>
			>
				<?= \esc_html( $cta['title'] ); ?>
			</a>
			<?php endif; ?>
		</footer>
		<?php endif; ?>

	</div>
</section>
