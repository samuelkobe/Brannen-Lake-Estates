/**
 * Scroll Lock Utility
 *
 * Prevents body scrolling when overlays are open.
 * Compensates for scrollbar width to prevent layout shift.
 */

export default class ScrollLock {
	static lock(): void {
		const scrollbarWidth = this.getScrollbarWidth()

		document.body.style.overflow = 'hidden'
		document.body.classList.add('scroll-locked')

		if (scrollbarWidth > 0) {
			document.body.style.paddingRight = scrollbarWidth + 'px'

			const header = document.querySelector<HTMLElement>('[data-ble-header]')
			if (header) {
				const orig = header.style.transition
				header.style.transition = 'none'
				header.style.paddingRight = scrollbarWidth + 'px'
				header.offsetHeight // force reflow
				header.style.transition = orig
			}
		}
	}

	static unlock(): void {
		document.body.style.overflow = ''
		document.body.style.paddingRight = ''
		document.body.classList.remove('scroll-locked')

		const header = document.querySelector<HTMLElement>('[data-ble-header]')
		if (header) {
			const orig = header.style.transition
			header.style.transition = 'none'
			header.style.paddingRight = ''
			header.offsetHeight // force reflow
			header.style.transition = orig
		}
	}

	private static getScrollbarWidth(): number {
		return window.innerWidth - document.documentElement.clientWidth
	}
}
