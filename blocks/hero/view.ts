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

// ── 4. Background video (desktop only) ─────────────────────────────────────
//
// The <video> ships without a source. It's attached only on screens 1024px+,
// and never with reduced motion or Data Saver, so phones and tablets don't
// download it. The hero image stays underneath the whole time.
//
// data-plays: how many times it plays (0 = loop forever). During the last
// 1.5s of the final play it fades out to the image, and the pause button
// becomes a replay button (replay plays it once more).

const desktopQuery = window.matchMedia( '(min-width: 1024px)' )
const saveData = ( navigator as Navigator & { connection?: { saveData?: boolean } } ).connection?.saveData === true
const FADE_SECONDS = 1.5

document.querySelectorAll<HTMLVideoElement>( '[data-ble-hero-video]' ).forEach( ( video ) => {
	if ( prefersReducedMotion || saveData ) return

	const hero   = video.closest<HTMLElement>( '[data-ble-hero]' )
	const toggle = hero?.querySelector<HTMLButtonElement>( '[data-ble-hero-video-toggle]' ) ?? null
	const total  = Math.max( 0, parseInt( video.dataset.plays ?? '0', 10 ) || 0 )

	let remaining  = total     // plays left, including the current one (0 = infinite)
	let userPaused = false
	let finished   = false

	const setToggle = ( mode: 'pause' | 'play' | 'replay' ): void => {
		if ( ! toggle ) return
		toggle.dataset.mode = mode
		toggle.setAttribute( 'aria-pressed', String( mode === 'play' ) )
		toggle.setAttribute( 'aria-label', {
			pause:  'Pause background video',
			play:   'Play background video',
			replay: 'Replay background video',
		}[ mode ] )
	}

	const load = (): void => {
		if ( video.querySelector( 'source' ) ) return
		const source = document.createElement( 'source' )
		source.src  = video.dataset.src ?? ''
		source.type = video.dataset.type ?? 'video/mp4'
		video.appendChild( source )
		video.load()
	}

	const play = (): void => {
		if ( userPaused || finished ) return
		video.play().catch( () => { /* Autoplay blocked: the image stays. */ } )
	}

	video.addEventListener( 'playing', () => {
		video.classList.add( 'is-playing' )
		if ( toggle ) toggle.hidden = false
	} )

	// Fade out during the last moments of the final play.
	video.addEventListener( 'timeupdate', () => {
		if ( total === 0 || remaining !== 1 || ! video.duration ) return
		if ( video.duration - video.currentTime <= FADE_SECONDS ) video.classList.add( 'is-fading' )
	} )

	video.addEventListener( 'ended', () => {
		if ( total === 0 ) return // native loop handles it
		remaining--
		if ( remaining > 0 ) {
			video.currentTime = 0
			play()
			return
		}
		finished = true
		video.classList.add( 'is-fading' )
		setToggle( 'replay' )
	} )

	const apply = (): void => {
		if ( desktopQuery.matches ) {
			load()
			play()
		} else if ( ! video.paused ) {
			video.pause()
		}
	}

	apply()
	desktopQuery.addEventListener( 'change', apply )

	// Pause while the tab is hidden; resume when it's back.
	document.addEventListener( 'visibilitychange', () => {
		if ( document.hidden ) video.pause()
		else if ( desktopQuery.matches ) play()
	} )

	toggle?.addEventListener( 'click', () => {
		if ( finished ) {
			// Replay once.
			finished   = false
			userPaused = false
			remaining  = 1
			video.classList.remove( 'is-fading' )
			video.currentTime = 0
			video.play().catch( () => {} )
			setToggle( 'pause' )
			return
		}
		userPaused = ! video.paused
		if ( userPaused ) video.pause()
		else video.play().catch( () => {} )
		setToggle( userPaused ? 'play' : 'pause' )
	} )
} )
