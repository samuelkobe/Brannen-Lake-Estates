/**
 * Mobile Menu
 *
 * Handles the mobile drawer menu:
 * - Open/close drawer with hamburger toggle
 * - Expand/collapse submenus inline
 * - Body scroll lock when open
 * - Focus trap for accessibility
 * - Escape to close, auto-close on resize to desktop
 */

import ScrollLock from '../utils/scroll-lock'
import FocusTrap from '../utils/focus-trap'

const DESKTOP_BREAKPOINT = 1024

let toggle: HTMLElement | null
let menu: HTMLElement | null
let focusTrap: FocusTrap | null = null

function openMenu(): void {
	if (!toggle || !menu) return

	menu.removeAttribute('hidden')
	toggle.setAttribute('aria-expanded', 'true')
	toggle.setAttribute('aria-label', 'Close menu')

	ScrollLock.lock()

	focusTrap = new FocusTrap(menu)
	focusTrap.activate()
}

function closeMenu(): void {
	if (!toggle || !menu) return

	menu.setAttribute('hidden', '')
	toggle.setAttribute('aria-expanded', 'false')
	toggle.setAttribute('aria-label', 'Open menu')

	ScrollLock.unlock()

	if (focusTrap) {
		focusTrap.deactivate()
		focusTrap = null
	}

	toggle.focus()
	closeAllSubmenus()
}

function toggleMenu(): void {
	const isOpen = toggle?.getAttribute('aria-expanded') === 'true'
	isOpen ? closeMenu() : openMenu()
}

function toggleSubmenu(event: Event): void {
	const button = event.currentTarget as HTMLElement
	const submenuId = button.getAttribute('aria-controls')
	if (!submenuId) return

	const submenu = document.getElementById(submenuId)
	if (!submenu) return

	const isExpanded = button.getAttribute('aria-expanded') === 'true'

	if (isExpanded) {
		submenu.setAttribute('hidden', '')
		button.setAttribute('aria-expanded', 'false')
		const label = button.getAttribute('aria-label')
		if (label) button.setAttribute('aria-label', label.replace('Close', 'Open'))
	} else {
		submenu.removeAttribute('hidden')
		button.setAttribute('aria-expanded', 'true')
		const label = button.getAttribute('aria-label')
		if (label) button.setAttribute('aria-label', label.replace('Open', 'Close'))
	}
}

function closeAllSubmenus(): void {
	const toggles = document.querySelectorAll<HTMLElement>('[data-ble-mobile-submenu-toggle]')
	toggles.forEach(btn => {
		const submenuId = btn.getAttribute('aria-controls')
		const submenu = submenuId ? document.getElementById(submenuId) : null

		if (submenu) {
			submenu.setAttribute('hidden', '')
			btn.setAttribute('aria-expanded', 'false')
			const label = btn.getAttribute('aria-label')
			if (label) btn.setAttribute('aria-label', label.replace('Close', 'Open'))
		}
	})
}

function handleEscape(event: KeyboardEvent): void {
	if (event.key === 'Escape' && toggle?.getAttribute('aria-expanded') === 'true') {
		closeMenu()
	}
}

function handleResize(): void {
	if (window.innerWidth >= DESKTOP_BREAKPOINT && toggle?.getAttribute('aria-expanded') === 'true') {
		closeMenu()
	}
}

export function initMobileMenu(): void {
	toggle = document.querySelector<HTMLElement>('[data-ble-mobile-toggle]')
	menu = document.querySelector<HTMLElement>('[data-ble-mobile-menu]')

	if (!toggle || !menu) return

	toggle.addEventListener('click', toggleMenu)

	const submenuToggles = document.querySelectorAll<HTMLElement>('[data-ble-mobile-submenu-toggle]')
	submenuToggles.forEach(btn => {
		btn.addEventListener('click', toggleSubmenu)
	})

	document.addEventListener('keydown', handleEscape)
	window.addEventListener('resize', handleResize)
}
