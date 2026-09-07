/**
 * Hero — view script.
 *
 * Two motion behaviours:
 *   1. Load fade-in: adds .is-loaded to the hero section shortly after
 *      DOMContentLoaded, triggering the CSS glass container reveal.
 *   2. Parallax: translates the background image layer at 0.4× scroll
 *      speed, creating depth without aggressive movement.
 *
 * Bails out entirely if prefers-reduced-motion is set.
 * Does not run in the block editor (checks for wp-admin body class).
 */

// Don't run motion in the block editor.
if ( document.body.classList.contains( 'wp-admin' ) ) {
	// eslint-disable-next-line no-throw-literal
	throw new Error( 'Hero view.js: skipping in editor context.' )
}

const prefersReducedMotion = window.matchMedia(
	'(prefers-reduced-motion: reduce)'
).matches

if ( prefersReducedMotion ) {
	// Ensure the hero is visible even without JS-triggered class.
	document.querySelectorAll<HTMLElement>( '[data-ble-hero]' ).forEach( ( hero ) => {
		hero.classList.add( 'is-loaded' )
	} )
} else {
	// ── 1. Load fade-in ────────────────────────────────────────────────────
	//
	// A short delay lets the browser start rendering the hero image before
	// the glass container fades in, making the reveal feel more cinematic.

	const triggerLoad = (): void => {
		document.querySelectorAll<HTMLElement>( '[data-ble-hero]' ).forEach( ( hero ) => {
			// Small timeout so the initial paint completes first.
			setTimeout( () => {
				hero.classList.add( 'is-loaded' )
			}, 100 )
		} )
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', triggerLoad )
	} else {
		triggerLoad()
	}

	// ── 2. Parallax ────────────────────────────────────────────────────────
	//
	// Moves the background layer at 40% of scroll speed.
	// requestAnimationFrame keeps it smooth without layout thrashing.

	const parallaxLayers = document.querySelectorAll<HTMLElement>(
		'[data-ble-parallax]'
	)

	if ( parallaxLayers.length > 0 ) {
		let ticking = false

		const updateParallax = (): void => {
			const scrollY = window.scrollY

			parallaxLayers.forEach( ( layer ) => {
				const section = layer.closest<HTMLElement>( '[data-ble-hero]' )
				if ( ! section ) return

				const rect         = section.getBoundingClientRect()
				const sectionTop   = rect.top + scrollY
				const relativeScroll = scrollY - sectionTop

				// Only apply when the hero is in or near the viewport.
				if ( rect.bottom < 0 || rect.top > window.innerHeight ) return

				const offset = relativeScroll * 0.4
				layer.style.transform = `translateY(${offset}px)`
			} )

			ticking = false
		}

		window.addEventListener( 'scroll', () => {
			if ( ! ticking ) {
				window.requestAnimationFrame( updateParallax )
				ticking = true
			}
		}, { passive: true } )
	}
}
