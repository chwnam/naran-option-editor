<?php
/**
 * Mockup module for development.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Mockup' ) ) {
	/**
	 * Class NOE_Mockup
	 *
	 * Mockup menu module
	 */
	class NOE_Mockup implements NOE_Module {
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
				[ $this, 'output_mockup' ]
			);
		}

		public function output_mockup() {
			$tab = $_GET['tab'] ?? 'option-editor';
			if ( 'option-editor' === $tab ) {
				$this->output_option_mockup();
			} elseif ( 'prefix-inspector' ) {
				$this->output_prefix_inspector();
			}
		}

		private function output_option_mockup() {
			$page = $_GET['noe'] ?? 'list';
			if ( 'list' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/list-page.php';
			} elseif ( 'single-new' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single-new.php';
			} elseif ( 'single' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single.php';
			}
		}

		private function output_prefix_inspector() {
			include dirname( NOE_MAIN ) . '/mockup/prefix-inspector.php';
		}
	}
}

