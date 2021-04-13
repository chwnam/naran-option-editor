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

		/**
		 * Get the single instance.
		 *
		 * @return static
		 */
		public static function get_instance(): self {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * NOE_Container constructor.
		 */
		private function __construct() {
			/**
			 * Load main modules:
			 *
			 * - admin:         Modules for admin screen.
			 * - desc_table:    Description table management module.
			 * - prefix_filter: Prefix filter management module.
			 * - registerer:    Registerer module.
			 */
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
			add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
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

		/**
		 * Activation callback.
		 *
		 * @callback
		 */
		public function activation() {
			$this->force_activate_dynamic_modules();
			do_action( 'noe_activation' );
		}

		/**
		 * Deactivation callback.
		 *
		 * @callback
		 */
		public function deactivation() {
			$this->force_activate_dynamic_modules();
			do_action( 'noe_deactivation' );
		}

		/**
		 * Load the plugin translation .mo file.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'noe', false, wp_basename( dirname( NOE_MAIN ) ) . '/languages' );
		}

		/**
		 * Load dynamic modules on purpose.
		 *
		 * @used-by NOE_Container::activation()
		 * @used-by NOE_Container::deactivation()
		 */
		protected function force_activate_dynamic_modules() {
			$noe = noe();
			/** @noinspection PhpExpressionResultUnusedInspection */
			$noe->desc_table;
		}

		/**
		 * Load mockup page for development.
		 */
		protected function mockup() {
			if ( in_array( wp_get_environment_type(), [ 'local', 'development' ], true ) ) {
				$this->modules[] = new NOE_Mockup();
			}
		}
	}
}

