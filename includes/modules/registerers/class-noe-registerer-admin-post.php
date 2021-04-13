<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Post' ) ) {
	/**
	 * Class NOE_Registerer_Admin_Post
	 *
	 * Register and handle admin-post.php callbacks.
	 */
	class NOE_Registerer_Admin_Post implements NOE_Registerer {
		public function __construct() {
			if ( is_admin() ) {
				$this->register_items();
			}
		}

		public function register_items() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NOE_Submit ) {
					$item->register();
				}
			}
		}

		public function get_items(): array {
			return [
				new NOE_Submit(
					'noe_edit_option',
					function () { noe()->admin->option_editor->edit_option(); }
				),
				new NOE_Submit(
					'noe_delete_option',
					function () { noe()->admin->option_editor->delete_option(); }
				),
				new NOE_Submit(
					'noe_backup_options',
					function () { noe()->admin->option_editor->backup_options(); }
				),
			];
		}
	}
}

