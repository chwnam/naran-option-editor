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
	 * @property-read NOE_Admin_Option_Editor    $option_editor
	 * @property-read NOE_Admin_Prefix_Inspector $prefix_inspector
	 */
	class NOE_Admin implements NOE_Module {
		use NOE_Submodule_Impl;

		public function __construct() {
			$this->load_modules(
				[
					'option_editor'    => NOE_Admin_Option_Editor::class,
					'prefix_inspector' => NOE_Admin_Prefix_Inspector::class,
				]
			);
		}
	}
}
