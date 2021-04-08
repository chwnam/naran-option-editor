<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin_Subpage_Blank' ) ) {
	/**
	 * Class NOE_Admin_Subpage_Blank
	 *
	 * Blank page for fallback page.
	 */
	class NOE_Admin_Subpage_Blank implements NOE_Admin_Module, NOE_Submenu_Page {
		public function current_screen( WP_Screen $screen ) {
		}

		public function output_submenu_page() {
			echo 'this is blank page.';
		}
	}
}

