<?php
/**
 * Hero — render template.
 *
 * Full-bleed cinematic hero with frosted glass text container.
 * This block outputs the page H1 — there must be only one per page.
 * Motion is handled by view.js (load fade-in + scroll parallax).
 *
 * @package WebOk\BrannenLakeEstates
 *
 * @var array $block ACF block metadata.
 */

namespace WebOk\BrannenLakeEstates;

$heading         = \get_field( 'heading' );
$heading_size    = \get_field( 'heading_size' );
$tagline         = \get_field( 'tagline' );
$image           = \get_field( 'background_image' );
$video           = \get_field( 'background_video' );
$video_plays     = \get_field( 'video_plays' );
$video_plays     = ( null === $video_plays || '' === $video_plays ) ? 2 : \max( 0, (int) $video_plays );
$overlay_color   = \get_field( 'overlay_color' ) ?: '#2C2A27';
$overlay_opacity = \get_field( 'overlay_opacity' );
$primary_cta     = \get_field( 'primary_cta' );
$secondary_cta   = \get_field( 'secondary_cta' );

if ( ! $heading ) {
	return;
}

// Fluid heading size: scales from half the max down to 2.5rem at small screens.
$max_size         = $heading_size ? (float) $heading_size : 6;
$min_size         = max( 2.5, round( $max_size * 0.45, 2 ) );
$heading_size_css = sprintf( 'clamp(%srem, 8vw, %srem)', $min_size, $max_size );

// Overlay rgba — convert hex + integer opacity% to CSS rgba().
$hex           = \ltrim( $overlay_color, '#' );
$r             = \hexdec( \substr( $hex, 0, 2 ) );
$g             = \hexdec( \substr( $hex, 2, 2 ) );
$b             = \hexdec( \substr( $hex, 4, 2 ) );
$alpha         = ( $overlay_opacity !== false && $overlay_opacity !== '' )
	? (int) $overlay_opacity / 100
	: 0.50;
$overlay_style = sprintf( 'background-color:rgba(%d,%d,%d,%.2f)', $r, $g, $b, $alpha );

// In the block editor preview, view.js never runs so .is-loaded is never added
// and the glass container stays invisible (opacity:0). ACF automatically provides
// a $is_preview variable in render template scope — use that directly.
// Do NOT reassign $is_preview here; just read it as ACF set it.
$hero_extra = ! empty( $is_preview ) ? ' is-loaded' : '';

// Use the image alt text from ACF if set; fall back to the heading as context.
$img_alt = ! empty( $image['alt'] ) ? $image['alt'] : \esc_attr( $heading );
?>
<section
	class="ble-hero relative w-full min-h-screen flex items-end overflow-hidden<?= \esc_attr( $hero_extra ); ?>"
	aria-labelledby="ble-hero-heading"
	data-ble-hero
>

	<?php /* ── Background image ──────────────────────────────────────────── */ ?>
	<div class="ble-hero__bg absolute inset-0" aria-hidden="true" data-ble-parallax>
		<?php if ( $image ) : ?>
		<img
			src="<?= \esc_url( $image['url'] ); ?>"
			alt="<?= \esc_attr( $img_alt ); ?>"
			class="w-full h-full object-cover object-center"
			loading="eager"
			fetchpriority="high"
			width="<?= \esc_attr( $image['width'] ?? '' ); ?>"
			height="<?= \esc_attr( $image['height'] ?? '' ); ?>"
		/>
		<?php endif; ?>
		<?php
		/*
		 * Optional desktop video. No <source> is output: view.js adds it only on
		 * screens 1024px+ (and not with reduced motion or Data Saver), so phones
		 * and tablets never download the file. The image above stays underneath
		 * as the poster and fallback; the video fades in once it's playing.
		 */
		if ( ! empty( $video['url'] ) && empty( $is_preview ) ) : ?>
		<video
			class="ble-hero__video"
			muted
			<?php if ( 0 === $video_plays ) : ?>loop<?php endif; ?>
			playsinline
			preload="none"
			disablepictureinpicture
			disableremoteplayback
			aria-hidden="true"
			tabindex="-1"
			<?php if ( $image ) : ?>poster="<?= \esc_url( $image['url'] ); ?>"<?php endif; ?>
			data-ble-hero-video
			data-plays="<?= \esc_attr( (string) $video_plays ); ?>"
			data-src="<?= \esc_url( $video['url'] ); ?>"
			data-type="<?= \esc_attr( $video['mime_type'] ?? 'video/mp4' ); ?>"
		></video>
		<?php endif; ?>
		<?php /* Overlay: colour + opacity set by ACF fields */ ?>
		<div class="ble-hero__overlay absolute inset-0" style="<?= \esc_attr( $overlay_style ); ?>"></div>
	</div>

	<?php /* ── Frosted glass content container ────────────────────────────── */ ?>
	<div class="ble-hero__content relative z-10 w-full px-6 pb-16 lg:pb-40">
		<div class="ble-hero__glass max-w-3xl mx-auto lg:mx-0 lg:ml-[8vw]" data-ble-hero-content>

			<h1
				id="ble-hero-heading"
				class="ble-hero__heading font-serif font-light italic text-white leading-tight mb-4 lg:mb-2"
				style="font-size: <?= \esc_attr( $heading_size_css ); ?>;"
			>
				<?= \esc_html( $heading ); ?>
			</h1>

			<?php if ( $tagline ) : ?>
			<p class="ble-hero__tagline font-sans font-light text-white/85 tracking-wide mb-10">
				<?= \esc_html( $tagline ); ?>
			</p>
			<?php endif; ?>

			<?php if ( ! empty( $primary_cta['url'] ) && ! empty( $primary_cta['title'] ) ) : ?>
			<div class="ble-hero__actions flex flex-wrap items-center gap-5">

				<a
					href="<?= \esc_url( $primary_cta['url'] ); ?>"
					class="ble-hero__btn ble-hero__btn--primary font-sans text-sm tracking-[0.18em] uppercase px-12 py-4 inline-flex items-center"
					<?php if ( ! empty( $primary_cta['target'] ) ) : ?>
						target="<?= \esc_attr( $primary_cta['target'] ); ?>"
						rel="noopener noreferrer"
					<?php endif; ?>
					aria-label="<?= \esc_attr( $primary_cta['title'] ); ?>"
				>
					<?= \esc_html( $primary_cta['title'] ); ?>
				</a>

				<?php if ( ! empty( $secondary_cta['url'] ) && ! empty( $secondary_cta['title'] ) ) : ?>
				<a
					href="<?= \esc_url( $secondary_cta['url'] ); ?>"
					class="ble-hero__btn ble-hero__btn--secondary font-sans text-sm tracking-[0.18em] uppercase px-12 py-4 inline-flex items-center"
					<?php if ( ! empty( $secondary_cta['target'] ) ) : ?>
						target="<?= \esc_attr( $secondary_cta['target'] ); ?>"
						rel="noopener noreferrer"
					<?php endif; ?>
					aria-label="<?= \esc_attr( $secondary_cta['title'] ); ?>"
				>
					<?= \esc_html( $secondary_cta['title'] ); ?>
				</a>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div>
	</div>

	<?php if ( ! empty( $video['url'] ) && empty( $is_preview ) ) : ?>
	<?php /* Pause/play for the background video (WCAG 2.2.2). Shown only once the video plays. */ ?>
	<button
		type="button"
		class="ble-hero__video-toggle"
		aria-label="<?= \esc_attr__( 'Pause background video', 'brannen-lake-estates' ); ?>"
		aria-pressed="false"
		data-ble-hero-video-toggle
		hidden
	>
		<svg class="ble-hero__icon-pause" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>
		<svg class="ble-hero__icon-play" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13a1 1 0 0 0 1.5.86l11-6.5a1 1 0 0 0 0-1.72l-11-6.5A1 1 0 0 0 8 5.5z"/></svg>
		<svg class="ble-hero__icon-replay" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>
	</button>
	<?php endif; ?>

	<?php /* ── Scroll-down indicator (mobile only) ──────────────────────── */ ?>
	<button
		class="ble-hero__scroll-arrow"
		aria-label="<?= \esc_attr__( 'Scroll to content', 'brannen-lake-estates' ); ?>"
		data-ble-scroll-next
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<polyline points="6 9 12 15 18 9"/>
		</svg>
	</button>

</section>
