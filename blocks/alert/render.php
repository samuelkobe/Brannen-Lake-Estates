<?php
/**
 * Alert — render template.
 *
 * Notice bar for short messages such as appointment-only visits.
 * Optional CTA button, dismiss button, and sticky positioning.
 * Dismissal is handled by view.js and remembered per message text,
 * so editing the message shows it again to visitors who closed it.
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block      ACF block metadata.
 * @var bool  $is_preview True inside the block editor.
 */

namespace WebOk\BrannenLakeEstates;

$message     = \get_field( 'message' );
$style       = \get_field( 'style' ) ?: 'info';
$dismissible = (bool) \get_field( 'dismissible' );
$sticky      = (bool) \get_field( 'sticky' );
$show_cta    = (bool) \get_field( 'show_cta' );
$cta         = \get_field( 'cta' );

// Fallback for alerts saved before the link field replaced text + URL.
if ( empty( $cta['url'] ) && \get_field( 'cta_url' ) ) {
	$cta = [
		'url'    => \get_field( 'cta_url' ),
		'title'  => \get_field( 'cta_text' ),
		'target' => '',
	];
}

if ( ! $message ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p class="ble-alert__placeholder">' . \esc_html__( 'Alert: add a message in the block settings.', 'brannen-lake-estates' ) . '</p>';
	}
	return;
}

if ( ! \in_array( $style, [ 'info', 'warning', 'subtle' ], true ) ) {
	$style = 'info';
}

$classes = [ 'ble-alert', 'ble-alert--' . $style ];
if ( $sticky && empty( $is_preview ) ) {
	$classes[] = 'ble-alert--sticky';
}

// Key changes whenever the message changes, so a new message re-appears.
$dismiss_key = 'ble-alert-' . \substr( \md5( $message ), 0, 10 );
$has_cta     = $show_cta && ! empty( $cta['url'] ) && ! empty( $cta['title'] );

// Descriptive label for screen readers and the title tooltip, e.g.
// "Book a Tour: Contact | Brannen Lake Estates". Uses the linked page's
// title when the link points at a page on this site.
$cta_label = '';
$cta_new   = false;
if ( $has_cta ) {
	$cta_new   = '_blank' === ( $cta['target'] ?? '' );
	$post_id   = \url_to_postid( $cta['url'] );
	$dest      = $post_id ? \get_the_title( $post_id ) : '';
	$cta_label = $dest && 0 !== \strcasecmp( $dest, $cta['title'] )
		? \sprintf( '%s: %s | %s', $cta['title'], $dest, \get_bloginfo( 'name' ) )
		: \sprintf( '%s | %s', $cta['title'], \get_bloginfo( 'name' ) );
	if ( $cta_new ) {
		$cta_label .= ' ' . \__( '(opens in a new tab)', 'brannen-lake-estates' );
	}
}
?>
<div
	class="<?= \esc_attr( \implode( ' ', $classes ) ); ?>"
	role="<?= 'warning' === $style ? 'alert' : 'status'; ?>"
	<?php if ( $dismissible ) : ?>
		data-ble-alert-dismissible
		data-ble-alert-key="<?= \esc_attr( $dismiss_key ); ?>"
	<?php endif; ?>
>
	<div class="ble-alert__inner max-w-6xl xl:max-w-7xl mx-auto px-6 flex flex-wrap md:flex-nowrap items-center justify-center gap-x-6 gap-y-3">

		<p class="ble-alert__message font-sans font-light text-center md:text-left leading-relaxed">
			<?= \esc_html( $message ); ?>
		</p>

		<?php if ( $has_cta ) : ?>
		<a
			href="<?= \esc_url( $cta['url'] ); ?>"
			class="ble-alert__btn font-sans text-xs tracking-[0.18em] uppercase whitespace-nowrap px-6 py-2 inline-flex items-center"
			aria-label="<?= \esc_attr( $cta_label ); ?>"
			title="<?= \esc_attr( $cta_label ); ?>"
			<?php if ( $cta_new ) : ?>
				target="_blank"
				rel="noopener noreferrer"
			<?php endif; ?>
		>
			<?= \esc_html( $cta['title'] ); ?>
		</a>
		<?php endif; ?>

		<?php if ( $dismissible ) : ?>
		<button
			type="button"
			class="ble-alert__close"
			aria-label="<?= \esc_attr__( 'Dismiss this notice', 'brannen-lake-estates' ); ?>"
			data-ble-alert-close
		>
			<svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
				<path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
			</svg>
		</button>
		<?php endif; ?>

	</div>
</div>
<?php if ( $dismissible && empty( $is_preview ) ) : ?>
<script>
/* Hide an already-dismissed alert before first paint, so it doesn't flash
   on reload. view.js handles the close button. */
( function () {
	var a = document.currentScript && document.currentScript.previousElementSibling;
	if ( ! a ) return;
	try {
		if ( /[?&]reset-alerts\b/.test( location.search ) ) {
			Object.keys( localStorage ).forEach( function ( k ) { if ( k.indexOf( 'ble-alert-' ) === 0 ) localStorage.removeItem( k ); } );
		} else if ( localStorage.getItem( a.getAttribute( 'data-ble-alert-key' ) ) === '1' ) {
			a.classList.add( 'is-dismissed' );
		}
	} catch ( e ) {}
} )();
</script>
<?php endif; ?>
