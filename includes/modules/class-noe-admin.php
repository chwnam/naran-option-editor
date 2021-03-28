<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin' ) ) {
	/**
	 * Class NOE_Admin
	 *
	 * @property-read NOE_Admin_Menu $menu
	 */
	class NOE_Admin implements NOE_Module {
		use NOE_Submodule_Impl;

		public function __construct() {
			$this->load_modules(
				[
					'menu' => NOE_Admin_Menu::class,
				]
			);
		}
	}
}
