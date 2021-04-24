<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Script' ) ) {
	/**
	 * Class NOE_Registerer_Admin_Script
	 *
	 * Define and register JS script handles.
	 */
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
				new NOE_Script(
					'noe-option-edit',
					'option-edit.js',
					[ 'jquery', 'wp-util' ],
					NOE_VERSION,
					true
				),
				new NOE_Script(
					'noe-option-table',
					'option-table.js',
					[ 'jquery', 'jquery-ui-dialog', 'wp-util' ],
					NOE_VERSION,
					true
				),
				new NOE_Script(
					'noe-prefix-inspector',
					'prefix-inspector.js',
					[ 'jquery' ],
					NOE_VERSION,
					true
				),
			];
		}
	}
}

