<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Submit' ) ) {
	class NOE_Submit implements NOE_Registrable {
		public string $action;

		/** @var string|array|callable */
		public $callback;

		public int $priority;

		public function __construct( string $action, $callback, ?int $priority = 10 ) {
			$this->action   = $action;
			$this->callback = $callback;
			$this->priority = $priority ? $priority : 10;
		}

		public function register() {
			if ( $this->action && $this->callback ) {
				add_action( "admin_post_{$this->action}", $this->callback );
			}
		}
	}
}
