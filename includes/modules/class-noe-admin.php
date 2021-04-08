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
	 * @property-read NOE_Admin_Menu             $menu
	 * @property-read NOE_Admin_Option_Editor    $option_editor
	 * @property-read NOE_Admin_Prefix_Inspector $prefix_inspector
	 * @property-read NOE_Admin_Subpage_Blank    $subpage_blank
	 */
	class NOE_Admin implements NOE_Module {
		use NOE_Submodule_Impl;

		public function __construct() {
			$this->load_modules(
				[
					'menu'             => NOE_Admin_Menu::class,
					'option_editor'    => function () { return new NOE_Admin_Option_Editor(); },
					'prefix_inspector' => function () { return new NOE_Admin_Prefix_Inspector(); },
					'subpage_blank'    => function () { return new NOE_Admin_Subpage_Blank(); },
				]
			);
		}
	}
}
