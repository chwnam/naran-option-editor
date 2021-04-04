<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Module' ) ) {
	/**
	 * Class NOE_Registerer_Module
	 *
	 * @property-read NOE_Registerer_Admin_Ajax  $admin_ajax
	 * @property-read NOE_Registerer_Admin_Style $admin_script
	 * @property-read NOE_Registerer_Admin_Style $admin_style
	 * @property-read NOE_Registerer_Admin_Post  $admin_post
	 * @property-read NOE_Registerer_Meta        $meta
	 */
	class NOE_Registerer_Module implements NOE_Module {
		use NOE_Submodule_Impl;

		public function __construct() {
			$this->load_modules(
				[
					'admin_ajax'   => new NOE_Registerer_Admin_Ajax(),
					'admin_script' => new NOE_Registerer_Admin_Script(),
					'admin_style'  => new NOE_Registerer_Admin_Style(),
					'admin_post'   => new NOE_Registerer_Admin_Post(),
					'meta'         => new NOE_Registerer_Meta(),
				]
			);
		}
	}
}
