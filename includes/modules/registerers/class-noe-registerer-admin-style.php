<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Style' ) ) {
	/**
	 * Class NOE_Registerer_Admin_Style
	 *
	 * Define and register CSS stylesheet handles.
	 */
	class NOE_Registerer_Admin_Style implements NOE_Registerer {
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'init', [ $this, 'register_items' ] );
			}
		}

		public function register_items() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NOE_Style ) {
					$item->register();
				}
			}
		}

		public function get_items(): array {
			return [
				new NOE_Style( 'noe-option-edit', 'option-edit.css' ),
				new NOE_Style(
					'noe-option-table',
					'option-table.css',
					[
						'list-tables',
						'wp-jquery-ui-dialog'
					]
				),
				new NOE_Style( 'noe-prefix-inspector', 'prefix-inspector.css' ),
			];
		}
	}
}

