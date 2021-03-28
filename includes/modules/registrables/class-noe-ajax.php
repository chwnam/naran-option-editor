<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Ajax' ) ) {
	class NOE_Ajax implements NOE_Registrable {
		public string $action = '';

		/** @var string|array|callable */
		public $callback;

		public int $priority;

		public function __construct( string $action, $callback, int $priority = 10 ) {
			$this->action   = $action;
			$this->callback = $callback;
			$this->priority = $priority;
		}

		public function register() {
			if ( $this->action && $this->callback ) {
				add_action( "wp_ajax_{$this->action}", $this->callback, $this->priority );
			}
		}
	}
}

