<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Ajax' ) ) {
	/**
	 * Class NOE_Registerer_Admin_Ajax
	 *
	 * Define and register AJAX callback items.
	 */
	class NOE_Registerer_Admin_Ajax implements NOE_Registerer {
		public function __construct() {
			$this->register_items();
		}

		public function register_items() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NOE_Ajax ) {
					$item->register();
				}
			}
		}

		public function get_items(): array {
			return [
				new NOE_Ajax(
					'noe_add_prefix',
					function () {
						noe()->admin->option_editor->add_prefix();
					},
				),
				new NOE_Ajax(
					'noe_remove_prefix',
					function () {
						noe()->admin->option_editor->remove_prefix();
					},
				),
				new NOE_Ajax(
					'noe_clear_prefixes',
					function () {
						noe()->admin->option_editor->clear_prefixes();
					},
				),
				new NOE_Ajax(
					'noe_enable_prefix',
					function () {
						noe()->admin->option_editor->enable_prefix();
					},
				),
				new NOE_Ajax(
					'noe_enable_all_prefixes',
					function () {
						noe()->admin->option_editor->enable_all_prefixes();
					},
				),
				new NOE_Ajax(
					'noe_disable_prefix',
					function () {
						noe()->admin->option_editor->disable_prefix();
					},
				),
				new NOE_Ajax(
					'noe_disable_all_prefixes',
					function () {
						noe()->admin->option_editor->disable_all_prefixes();
					},
				),
				new NOE_Ajax(
					'noe_restore_options',
					function () {
						noe()->admin->option_editor->restore_options();
					}
				),
				new NOE_Ajax(
					'noe_edit_option_desc',
					function () {
						noe()->admin->option_editor->edit_option_desc();
					}
				),
				new NOE_Ajax(
					'noe_bulk_edit_option_desc',
					function () {
						noe()->admin->option_editor->bulk_edit_option_desc();
					}
				),
				new NOE_Ajax(
					'noe_option_name_search',
					function () {
						noe()->admin->option_editor->option_name_search();
					}
				)
			];
		}
	}
}

