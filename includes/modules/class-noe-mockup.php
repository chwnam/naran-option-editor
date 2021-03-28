<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Mockup' ) ) {
	class NOE_Mockup {
		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		}

		public function add_menu_page() {
			add_submenu_page(
				'tools.php',
				'NOE Mockup',
				'NOE Mockup',
				'administrator',
				'noe-mockup',
				[ $this, 'output_menu_page' ]
			);
		}

		public function output_menu_page() {
			$page = $_GET['noe'] ?? 'list';
			if ( 'list' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/list-page.php';
			} elseif ( 'single-new' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single-new.php';
			} elseif ( 'single' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single.php';
			}
		}
	}
}

