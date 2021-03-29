<?php
/**
 * Uninstall script.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

/* *******************************
 * Delete all usermeta keys.
 * *******************************/
$wpdb->delete(
	$wpdb->usermeta,
	[ 'meta_key' => 'noe_option_per_page' ],
	[ 'meta_key' => '%s' ]
);

$wpdb->delete(
	$wpdb->usermeta,
	[ 'meta_key' => 'managetools_page_noecolumnshidden' ],
	[ 'meta_key' => '%s' ]
);
