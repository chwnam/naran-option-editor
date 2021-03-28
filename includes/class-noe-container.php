<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Container' ) ) {
	/**
	 * Class NOE_Container
	 *
	 * @property-read NOE_Admin             $admin
	 * @property-read NOE_Registerer_Module $registerer
	 */
	final class NOE_Container {
		use NOE_Submodule_Impl;

		private static ?NOE_Container $instance = null;

		public static function get_instance(): self {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {
			$this->load_modules(
				[
					'admin'      => NOE_Admin::class,
					'registerer' => NOE_Registerer_Module::class,
				]
			);
			$this->mockup();
		}

		public function __clone() {
			throw new RuntimeException( __CLASS__ . ' cannot be cloned.' );
		}

		public function __wakeup() {
			throw new RuntimeException( __CLASS__ . ' cannot be unserialized.' );
		}

		public function __sleep() {
			throw new RuntimeException( __CLASS__ . ' cannot be serialized.' );
		}

		protected function mockup() {
			if ( in_array( wp_get_environment_type(), [ 'local', 'development' ], true ) ) {
				$this->modules[] = new NOE_Mockup();
			}
		}
	}
}

