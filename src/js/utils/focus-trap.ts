/**
 * Focus Trap Utility
 *
 * Traps keyboard focus within a container element.
 * Used for mobile menu drawer and accessible navigation.
 */

const FOCUSABLE =
	'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'

export default class FocusTrap {
	private element: HTMLElement
	private focusableElements: NodeListOf<HTMLElement> | null = null
	private firstFocusable: HTMLElement | null = null
	private lastFocusable: HTMLElement | null = null
	private handleTabBound = this.handleTab.bind(this)

	constructor(element: HTMLElement) {
		this.element = element
	}

	activate(): void {
		this.focusableElements = this.element.querySelectorAll<HTMLElement>(FOCUSABLE)

		if (!this.focusableElements.length) return

		this.firstFocusable = this.focusableElements[0]
		this.lastFocusable = this.focusableElements[this.focusableElements.length - 1]

		this.element.addEventListener('keydown', this.handleTabBound)
		this.firstFocusable.focus()
	}

	deactivate(): void {
		this.element.removeEventListener('keydown', this.handleTabBound)
	}

	private handleTab(event: KeyboardEvent): void {
		if (event.key !== 'Tab') return

		if (event.shiftKey) {
			if (document.activeElement === this.firstFocusable) {
				event.preventDefault()
				this.lastFocusable?.focus()
			}
		} else {
			if (document.activeElement === this.lastFocusable) {
				event.preventDefault()
				this.firstFocusable?.focus()
			}
		}
	}
}
