<?php
/**
 * Mockup module for development.
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
				'Editor Mockup',
				'Editor Mockup',
				'administrator',
				'noe-editor-mockup',
				[ $this, 'output_editor_mockup' ]
			);

			add_submenu_page(
				'tools.php',
				'Inspector Mockup',
				'Inspector Mockup',
				'administrator',
				'noe-inspector-mockup',
				[ $this, 'output_prefix_inspector' ]
			);
		}

		public function output_editor_mockup() {
			$page = $_GET['noe'] ?? 'list';
			if ( 'list' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/list-page.php';
			} elseif ( 'single-new' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single-new.php';
			} elseif ( 'single' === $page ) {
				include dirname( NOE_MAIN ) . '/mockup/single.php';
			}
		}

		public function output_prefix_inspector() {
			include dirname( NOE_MAIN ) . '/mockup/prefix-inspector.php';
		}
	}
}

