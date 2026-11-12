/**
 * Availability Calendar (Private Feed) — view script.
 *
 * Turns the stacked list of months into a paged view: one month on mobile,
 * two side by side from 900px. Without this script every month stays
 * visible, so the calendar still works with JavaScript off.
 */

const DESKTOP = window.matchMedia( '(min-width: 900px)' )

document.querySelectorAll<HTMLElement>( '[data-ble-avfeed]' ).forEach( ( root ) => {
	const months = Array.from( root.querySelectorAll<HTMLElement>( '[data-ble-avfeed-month]' ) )
	const nav    = root.querySelector<HTMLElement>( '[data-ble-avfeed-nav]' )
	const prev   = root.querySelector<HTMLButtonElement>( '[data-ble-avfeed-prev]' )
	const next   = root.querySelector<HTMLButtonElement>( '[data-ble-avfeed-next]' )
	const range  = root.querySelector<HTMLElement>( '[data-ble-avfeed-range]' )

	if ( months.length === 0 || ! nav || ! prev || ! next || ! range ) return

	let index = 0

	const perView = (): number => ( DESKTOP.matches ? 2 : 1 )

	const render = (): void => {
		const shown = perView()
		index = Math.min( index, Math.max( 0, months.length - shown ) )

		months.forEach( ( m, i ) => {
			m.classList.toggle( 'is-current', i === index )
			m.classList.toggle( 'is-next', i === index + 1 )
		} )

		const first = months[ index ]?.dataset.label ?? ''
		const last  = shown > 1 ? months[ index + 1 ]?.dataset.label ?? '' : ''
		range.textContent = last ? `${ first } – ${ last }` : first

		prev.disabled = index === 0
		next.disabled = index + shown >= months.length
	}

	prev.addEventListener( 'click', () => {
		index = Math.max( 0, index - 1 )
		render()
	} )

	next.addEventListener( 'click', () => {
		index = Math.min( months.length - perView(), index + 1 )
		render()
	} )

	DESKTOP.addEventListener( 'change', render )

	root.classList.add( 'is-enhanced' )
	nav.hidden = false
	render()
} )

export {}
