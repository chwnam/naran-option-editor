<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NOE_Registrable' ) ) {
	interface NOE_Registrable {
		public function register();
	}
}

