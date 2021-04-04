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
$user_meta_keys = [
	'noe_option_per_page',                 // List page items per page.
	'managetools_page_noecolumnshidden',   // List page hidden column information.
	'noe_prefix_filters',                  // Prefix filter information.
];

foreach ( $user_meta_keys as $meta_key ) {
	$wpdb->delete( $wpdb->usermeta, [ 'meta_key' => $meta_key ], [ 'meta_key' => '%s' ] );
}
