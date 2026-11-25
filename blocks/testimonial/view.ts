/**
 * Testimonial — view script.
 *
 * When the block is set to show a random quote, every matching quote is in
 * the HTML (all but the first hidden). Reveal one at random on each visit,
 * so it still changes when the page itself is served from cache.
 */

document.querySelectorAll<HTMLElement>( '[data-ble-testimonial-random]' ).forEach( ( section ) => {
	const quotes = Array.from( section.querySelectorAll<HTMLElement>( '.ble-testimonial__quote' ) )
	if ( quotes.length < 2 ) return
	const pick = Math.floor( Math.random() * quotes.length )
	quotes.forEach( ( q, i ) => {
		q.hidden = i !== pick
	} )
} )

export {}
