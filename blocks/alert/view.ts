/**
 * Alert — view script.
 *
 * Handles the dismiss button on dismissible alerts. A dismissal is stored in
 * localStorage under a key derived from the message text, so a closed alert
 * stays closed until the message changes. Storage can be unavailable
 * (private mode, blocked site data); the alert then simply closes for the
 * current page view.
 */

const alerts = document.querySelectorAll<HTMLElement>( '.ble-alert[data-ble-alert-dismissible]' )

function isDismissed( key: string ): boolean {
	try {
		return window.localStorage.getItem( key ) === '1'
	} catch {
		return false
	}
}

function rememberDismissal( key: string ): void {
	try {
		window.localStorage.setItem( key, '1' )
	} catch {
		// Storage unavailable — dismissal lasts for this page view only.
	}
}

alerts.forEach( ( alert ) => {
	const key = alert.dataset.bleAlertKey

	if ( key && isDismissed( key ) ) {
		alert.classList.add( 'is-dismissed' )
		return
	}

	const close = alert.querySelector<HTMLButtonElement>( '[data-ble-alert-close]' )
	if ( ! close ) return

	close.addEventListener( 'click', () => {
		alert.classList.add( 'is-dismissed' )
		if ( key ) rememberDismissal( key )
	} )
} )
