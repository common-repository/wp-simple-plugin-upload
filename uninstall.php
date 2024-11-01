<?php

/**
 * WP Simple Plugin Upload Uninstall
 *
 * Uninstalling WP Simple Plugin Upload deletes options.
 *
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/* Delete all data created by plugin, such as any options that were added to the options table. */
delete_option( 'seerox_wpspu_activated' );
delete_option( 'seerox_wpspu_deactivated' );

// Clear any cached data that has been removed
wp_cache_flush();
