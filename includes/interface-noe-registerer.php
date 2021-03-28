<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NOE_Registerer' ) ) {
	interface NOE_Registerer extends NOE_Module {
		public function register_items();

		public function get_items(): array;
	}
}

