/**
 * Scroll Behavior
 *
 * Handles scroll-based header transitions:
 * - Home page: transparent-to-solid at 80px (desktop only)
 * - Desktop: hide on scroll down, show on scroll up
 * - Mobile: always sticky, always solid
 * - Logo shrink at 80px scroll threshold
 */

const LOGO_TRANSITION_PX = 80
const DESKTOP_BREAKPOINT = 1024

let header: HTMLElement | null
let logo: HTMLElement | null
let isHome: boolean
let isMobile: boolean
let lastScroll = 0
let ticking = false
let scrollListenerActive = false

function getHideThreshold(): number {
	return window.innerHeight * 0.75
}

function isInternalNavigation(): boolean {
	try {
		if (!document.referrer) return false
		return new URL(document.referrer).origin === new URL(window.location.href).origin
	} catch {
		return false
	}
}

function applyScrollState(): void {
	if (!header) return
	const y = window.scrollY

	if (isMobile) {
		if (isHome) {
			header.classList.remove('ble-header--transparent')
			header.classList.add('ble-header--solid')
		}
		lastScroll = y
		return
	}

	// Desktop: logo shrink
	if (isHome && logo) {
		logo.classList.toggle('ble-header__logo--small', y >= LOGO_TRANSITION_PX)
	}

	// Desktop: color switch (home only)
	if (isHome) {
		if (y >= LOGO_TRANSITION_PX) {
			header.classList.remove('ble-header--transparent')
			header.classList.add('ble-header--solid')
		} else {
			header.classList.add('ble-header--transparent')
			header.classList.remove('ble-header--solid')
		}
	}

	lastScroll = y
}

function handleScroll(): void {
	if (!header) return
	const y = window.scrollY

	// Logo shrink (home only)
	if (isHome && logo) {
		logo.classList.toggle('ble-header__logo--small', y >= LOGO_TRANSITION_PX)
	}

	// Color switch (home only)
	if (isHome) {
		if (y >= LOGO_TRANSITION_PX) {
			header.classList.remove('ble-header--transparent')
			header.classList.add('ble-header--solid')
		} else {
			header.classList.add('ble-header--transparent')
			header.classList.remove('ble-header--solid')
		}
	}

	// Hide on scroll down (past threshold), show on any scroll up
	if (y > lastScroll && y > getHideThreshold()) {
		header.classList.add('ble-header--hidden')
	} else if (y < lastScroll) {
		header.classList.remove('ble-header--hidden')
	}

	lastScroll = y
	ticking = false
}

function requestTick(): void {
	if (!ticking) {
		requestAnimationFrame(handleScroll)
		ticking = true
	}
}

function addScrollListener(): void {
	if (!scrollListenerActive) {
		window.addEventListener('scroll', requestTick)
		scrollListenerActive = true
	}
}

function removeScrollListener(): void {
	if (scrollListenerActive) {
		window.removeEventListener('scroll', requestTick)
		scrollListenerActive = false
	}
}

function handleResize(): void {
	const wasMobile = isMobile
	isMobile = window.innerWidth < DESKTOP_BREAKPOINT

	if (isMobile && !wasMobile) {
		removeScrollListener()
		header?.classList.remove('ble-header--hidden')
		logo?.classList.remove('ble-header__logo--small')
		if (isHome) {
			header?.classList.remove('ble-header--transparent')
			header?.classList.add('ble-header--solid')
		}
	}

	if (!isMobile && wasMobile) {
		addScrollListener()
		applyScrollState()
		handleScroll()
	}
}

export function initScrollBehavior(): void {
	header = document.querySelector<HTMLElement>('[data-ble-header]')
	logo = document.querySelector<HTMLElement>('[data-ble-logo]')

	if (!header) return

	isHome = header.dataset.pageType === 'home'
	isMobile = window.innerWidth < DESKTOP_BREAKPOINT

	const skipTransition = isInternalNavigation() || window.scrollY > LOGO_TRANSITION_PX

	if (skipTransition) {
		header.classList.add('ble-header--no-transition')
		header.offsetHeight // force reflow
	}

	applyScrollState()
	header.classList.add('ble-header--ready')

	if (skipTransition) {
		requestAnimationFrame(() => {
			header?.classList.remove('ble-header--no-transition')
		})
	}

	window.addEventListener('resize', handleResize)

	if (!isMobile) {
		addScrollListener()
	}
}
