/**
 * Desktop Dropdowns
 *
 * Handles desktop submenu dropdown behavior:
 * - Toggle on chevron button click
 * - Close on outside click or Escape
 * - Only one dropdown open at a time
 */

let toggles: HTMLElement[] = []

function closeAll(except?: HTMLElement): void {
	toggles.forEach(btn => {
		if (btn === except) return

		const id = btn.getAttribute('aria-controls')
		const submenu = id ? document.getElementById(id) : null

		if (submenu && !submenu.hasAttribute('hidden')) {
			submenu.setAttribute('hidden', '')
			btn.setAttribute('aria-expanded', 'false')
		}
	})
}

function handleToggle(event: Event): void {
	const button = event.currentTarget as HTMLElement
	const id = button.getAttribute('aria-controls')
	if (!id) return

	const submenu = document.getElementById(id)
	if (!submenu) return

	const isOpen = !submenu.hasAttribute('hidden')

	// Close all others first
	closeAll(button)

	if (isOpen) {
		submenu.setAttribute('hidden', '')
		button.setAttribute('aria-expanded', 'false')
	} else {
		submenu.removeAttribute('hidden')
		button.setAttribute('aria-expanded', 'true')
	}
}

function handleOutsideClick(event: MouseEvent): void {
	const target = event.target as HTMLElement

	// If click is inside a dropdown or toggle, ignore
	if (target.closest('[data-ble-submenu]') || target.closest('[data-ble-submenu-toggle]')) {
		return
	}

	closeAll()
}

function handleEscape(event: KeyboardEvent): void {
	if (event.key === 'Escape') {
		closeAll()
	}
}

export function initDropdowns(): void {
	toggles = Array.from(document.querySelectorAll<HTMLElement>('[data-ble-submenu-toggle]'))

	if (!toggles.length) return

	toggles.forEach(btn => {
		btn.addEventListener('click', handleToggle)
	})

	document.addEventListener('click', handleOutsideClick)
	document.addEventListener('keydown', handleEscape)
}
