<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin_Menu' ) ) {
	class NOE_Admin_Menu implements NOE_Admin_Module {
		use NOE_Template_Impl;

		private ?NOE_Submenu_Page $page_module = null;

		private string $page_hook = '';

		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

			add_action( 'current_screen', [ $this, 'current_screen' ] );
		}

		public function add_admin_menu() {
			$this->page_hook = add_submenu_page(
				'tools.php',
				__( 'Naran Option Editor', 'noe' ),
				__( 'Naran Option Editor', 'noe' ),
				'administrator',
				'noe',
				[ $this, 'output_admin_menu_page' ]
			);
		}

		public function output_admin_menu_page() {
			if ( ( $module = $this->get_menu_page_module() ) ) {
				$module->output_submenu_page();
			}
		}

		public function current_screen( WP_Screen $screen ) {
			if ( $this->page_hook === $screen->id && ( $module = $this->get_menu_page_module() ) ) {
				$module->current_screen( $screen );
			}
		}

		public function output_tabs() {
			$this->template(
				'admin/tabs.php',
				[
					'base_url' => add_query_arg( [ 'page' => 'noe' ], admin_url( 'tools.php' ) ),
					'tabs'     => $this->get_tabs(),
					'current'  => $this->get_current_tab(),
				]
			);
		}

		public function output_hidden_tab_values() {
			printf( '<input type="hidden" name="tab" value="%s"', esc_attr( $this->get_current_tab() ) );
		}

		public function get_page_hook(): string {
			return $this->page_hook;
		}

		protected function get_menu_page_module(): ?NOE_Submenu_Page {
			if ( is_null( $this->page_module ) ) {
				switch ( $this->get_current_tab() ) {
					case 'option-editor':
						$this->page_module = noe()->admin->option_editor;
						break;

					case 'prefix-inspector':
						$this->page_module = noe()->admin->prefix_inspector;
						break;

					default:
						$this->page_module = noe()->admin->subpage_blank;
				}
			}

			return $this->page_module;
		}

		protected function get_current_tab(): string {
			$tab = sanitize_key( $_GET['tab'] ?? '' );

			if ( empty( $tab ) ) {
				$tab = $this->get_default_tab();
			}

			return $tab;
		}

		protected function get_tabs(): array {
			return [
				'option-editor'    => __( 'Option Editor', 'noe' ),
				'prefix-inspector' => __( 'Prefix Inspector', 'noe' ),
			];
		}

		protected function get_default_tab(): string {
			return 'option-editor';
		}
	}
}

