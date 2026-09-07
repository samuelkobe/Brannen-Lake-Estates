/**
 * Scroll reveal — shared utility.
 *
 * Any element with [data-ble-reveal] fades up when it enters the viewport.
 * Uses IntersectionObserver for performance (no scroll listeners).
 * Bails out if prefers-reduced-motion is set — the CSS reduced-motion
 * rule already makes the element visible without animation.
 */

const prefersReducedMotion = window.matchMedia(
	'(prefers-reduced-motion: reduce)'
).matches

if ( prefersReducedMotion ) {
	// Make all reveal elements visible immediately.
	document.querySelectorAll<HTMLElement>( '[data-ble-reveal]' ).forEach( ( el ) => {
		el.classList.add( 'is-revealed' )
	} )
} else {
	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' )
					observer.unobserve( entry.target )
				}
			} )
		},
		{
			// Trigger when 15% of the element is visible.
			threshold: 0.15,
		}
	)

	document.querySelectorAll( '[data-ble-reveal]' ).forEach( ( el ) => {
		observer.observe( el )
	} )
}
