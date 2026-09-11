/**
 * Hero — view script.
 *
 * Three behaviours:
 *   1. Load fade-in: adds .is-loaded to the hero section shortly after
 *      DOMContentLoaded, triggering the CSS glass container reveal.
 *   2. Parallax: translates the background image layer at 0.4× scroll
 *      speed, creating depth without aggressive movement.
 *   3. Scroll-down arrow: on mobile, a circled chevron scrolls smoothly
 *      to the next content block, offset by the fixed header height.
 *
 * Motion (1 & 2) bails out if prefers-reduced-motion is set.
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

// ── 3. Scroll-down arrow ────────────────────────────────────────────────────
//
// Runs regardless of motion preference — this is navigation, not decoration.
// Scrolls to the next sibling block, offset by the fixed header height so
// the eyebrow/heading of the next section isn't hidden behind the nav bar.

document.querySelectorAll<HTMLButtonElement>( '[data-ble-scroll-next]' ).forEach( ( btn ) => {
	btn.addEventListener( 'click', () => {
		const hero = btn.closest<HTMLElement>( '[data-ble-hero]' )
		if ( ! hero ) return

		// The ACF wrapper div sits between the hero section and the next block.
		const wrapper = hero.closest( '.wp-block-acf-hero' ) || hero
		const next    = wrapper.nextElementSibling as HTMLElement | null

		if ( next ) {
			// Account for the fixed header (5rem = 80px) + a little breathing room.
			const header = document.querySelector<HTMLElement>( '.ble-header' )
			const headerHeight = header ? header.offsetHeight : 80
			const offset = headerHeight + 16

			const targetY = next.getBoundingClientRect().top + window.scrollY - offset
			window.scrollTo( { top: targetY, behavior: 'smooth' } )
		}
	} )
} )
