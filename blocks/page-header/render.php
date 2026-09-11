<?php
/**
 * Page Header — render template.
 *
 * Lightweight alternative to the Page Banner for pages that don't
 * need a background image. Outputs the page H1 with an optional
 * tagline and breadcrumb trail on an ivory background.
 *
 * Breadcrumb schema is already output globally in inc/schema.php —
 * this block only renders the visual breadcrumbs, no duplicate JSON-LD.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading          = \get_field( 'heading' );
$alignment        = \get_field( 'alignment' ) ?: 'center';
$tagline          = \get_field( 'tagline' );
$show_breadcrumbs = \get_field( 'show_breadcrumbs' );

if ( ! $heading ) {
	return;
}

// Build breadcrumb trail from page ancestors.
$crumbs = [];
if ( $show_breadcrumbs ) {
	$post = \get_post();

	if ( $post ) {
		$crumbs[] = [
			'label' => 'Home',
			'url'   => \home_url( '/' ),
		];

		$ancestors = \array_reverse( \get_ancestors( $post->ID, 'page', 'post_type' ) );
		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = [
				'label' => \get_the_title( $ancestor_id ),
				'url'   => \get_permalink( $ancestor_id ),
			];
		}

		// Current page — no link.
		$crumbs[] = [
			'label' => \get_the_title(),
			'url'   => '',
		];
	}
}
?>
<header class="ble-page-header ble-page-header--<?= \esc_attr( $alignment ); ?>">
	<div class="ble-page-header__inner max-w-6xl xl:max-w-7xl mx-auto px-6">

		<?php if ( ! empty( $crumbs ) ) : ?>
		<nav class="ble-page-header__breadcrumbs" aria-label="<?= \esc_attr__( 'Breadcrumb', 'brannen-lake-estates' ); ?>">
			<ol>
				<?php foreach ( $crumbs as $i => $crumb ) : ?>
				<li<?php if ( $i === count( $crumbs ) - 1 ) : ?> aria-current="page"<?php endif; ?>>
					<?php if ( $crumb['url'] ) : ?>
					<a href="<?= \esc_url( $crumb['url'] ); ?>"><?= \esc_html( $crumb['label'] ); ?></a>
					<?php else : ?>
					<span><?= \esc_html( $crumb['label'] ); ?></span>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php endif; ?>

		<h1 class="ble-page-header__heading font-serif font-light italic text-charcoal leading-tight">
			<?= \esc_html( $heading ); ?>
		</h1>

		<?php if ( $tagline ) : ?>
		<p class="ble-page-header__tagline font-sans text-taupe">
			<?= \wp_kses_post( $tagline ); ?>
		</p>
		<?php endif; ?>

	</div>
</header>
