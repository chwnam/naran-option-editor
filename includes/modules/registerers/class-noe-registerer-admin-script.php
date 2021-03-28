<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Script' ) ) {
	class NOE_Registerer_Admin_Script implements NOE_Registerer {
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'init', [ $this, 'register_items' ] );
			}
		}

		public function register_items() {
			foreach ( $this->get_items() as $item ) {
				if ( $item instanceof NOE_Script ) {
					$item->register();
				}
			}
		}

		public function get_items(): array {
			return [
				new NOE_Script( 'noe-option-edit', 'option-edit.js', [ 'jquery' ] ),
				new NOE_Script(
					'noe-option-table',
					'option-table.js',
					[ 'jquery', 'jquery-ui-dialog', 'wp-util' ]
				),
			];
		}
	}
}

