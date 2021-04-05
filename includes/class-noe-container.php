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
	 * @property-read NOE_Desc_Table        $desc_table
	 * @property-read NOE_Prefix_Filter     $prefix_filter
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
					'admin'         => NOE_Admin::class,
					'desc_table'    => function () { return new NOE_Desc_Table(); },
					'prefix_filter' => function () { return new NOE_Prefix_Filter(); },
					'registerer'    => NOE_Registerer_Module::class,
				]
			);
			$this->mockup();

			register_activation_hook( NOE_MAIN, [ $this, 'activation' ] );
			register_deactivation_hook( NOE_MAIN, [ $this, 'deactivation' ] );
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

		public function activation() {
			$this->force_activate_dynamic_modules();
			do_action( 'noe_activation' );
		}

		public function deactivation() {
			$this->force_activate_dynamic_modules();
			do_action( 'noe_deactivation' );
		}

		protected function force_activate_dynamic_modules() {
			$noe = noe();
			$noe->desc_table;
		}

		protected function mockup() {
			if ( in_array( wp_get_environment_type(), [ 'local', 'development' ], true ) ) {
				$this->modules[] = new NOE_Mockup();
			}
		}
	}
}

