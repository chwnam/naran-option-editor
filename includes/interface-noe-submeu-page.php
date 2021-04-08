<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NOE_Submenu_Page' ) ) {
	interface NOE_Submenu_Page {
		public function current_screen( WP_Screen $screen );

		public function output_submenu_page();
	}
}

