<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Admin_Ajax' ) ) {
	class NOE_Registerer_Admin_Ajax implements NOE_Registerer {
		public function __construct() {
			$this->register_items();
		}

		public function register_items() {
			foreach( $this->get_items() as $item ) {
				if( $item instanceof NOE_Ajax ) {
					$item->register();
				}
			}
		}

		public function get_items(): array {
			return [];
		}
	}
}

