<?php
/**
 * "Owner" user role: everything an Editor can do, plus a few site-owner extras.
 *
 * For the client (site owner) so they can run their own site without full
 * Administrator access to plugins, themes, users and site settings.
 *
 * Owner = Editor capabilities +
 *   - edit_theme_options : Site Editor (header, footer, navigation menus, templates)
 *   - cfdb7_access       : Contact Form 7 Database entries (saved inquiries)
 *
 * To add a capability later, add it to OWNER_EXTRA_CAPS and bump OWNER_ROLE_VERSION.
 * The role is stored in the database, so it is synced once per version bump.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const OWNER_ROLE         = 'ble_owner';
const OWNER_ROLE_VERSION = '1';
const OWNER_EXTRA_CAPS   = [
	'edit_theme_options',
	'cfdb7_access',
];

/**
 * Create or refresh the Owner role when its definition changes.
 */
function sync_owner_role(): void {
	if ( \get_option( 'ble_owner_role_version' ) === OWNER_ROLE_VERSION && \get_role( OWNER_ROLE ) ) {
		return;
	}
	$editor = \get_role( 'editor' );
	if ( ! $editor ) {
		return;
	}
	$caps = $editor->capabilities;
	foreach ( OWNER_EXTRA_CAPS as $cap ) {
		$caps[ $cap ] = true;
	}
	\remove_role( OWNER_ROLE ); // Rebuild cleanly so removed caps don't linger.
	\add_role( OWNER_ROLE, 'Owner', $caps );
	\update_option( 'ble_owner_role_version', OWNER_ROLE_VERSION, false );
}
\add_action( 'init', __NAMESPACE__ . '\\sync_owner_role' );
