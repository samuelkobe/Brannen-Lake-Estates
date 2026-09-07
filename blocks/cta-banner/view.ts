/**
 * CTA Banner — view script.
 *
 * Handles scroll-reveal animation using IntersectionObserver.
 * CSS transitions do the visual work; this script only toggles .is-visible.
 * Bails out entirely if prefers-reduced-motion is set.
 */

const prefersReducedMotion = window.matchMedia(
	'(prefers-reduced-motion: reduce)'
).matches

if ( ! prefersReducedMotion ) {
	const banners = document.querySelectorAll<HTMLElement>(
		'.ble-cta-banner[data-ble-animate]'
	)

	if ( banners.length === 0 ) {
		// No banners on this page — nothing to do.
	} else {
		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) return

					const banner = entry.target as HTMLElement

					// Stagger child elements: heading → body → actions.
					const heading = banner.querySelector( '.ble-cta-banner__heading' )
					const body    = banner.querySelector( '.ble-cta-banner__body' )
					const actions = banner.querySelector( '.ble-cta-banner__actions' )

					const staggered: Array<{ el: Element | null; delay: number }> = [
						{ el: heading, delay: 0 },
						{ el: body,    delay: 120 },
						{ el: actions, delay: 240 },
					]

					staggered.forEach( ( { el, delay } ) => {
						if ( el instanceof HTMLElement ) {
							el.style.setProperty( '--delay', `${delay}ms` )
						}
					} )

					// Add .is-visible to trigger the CSS transitions.
					banner.classList.add( 'is-visible' )

					// Observe only once — unobserve after reveal.
					observer.unobserve( banner )
				} )
			},
			{
				// Trigger when 20% of the banner has entered the viewport.
				threshold: 0.2,
			}
		)

		banners.forEach( ( banner ) => observer.observe( banner ) )
	}
}
