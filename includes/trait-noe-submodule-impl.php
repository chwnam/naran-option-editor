<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Submodule_Impl' ) ) {
	trait NOE_Submodule_Impl {
		protected array $modules = [];

		public function __get( string $name ) {
			if ( ! is_numeric( $name ) && isset( $this->modules[ $name ] ) ) {
				if ( $this->modules[ $name ] instanceof Closure ) {
					$this->modules[ $name ] = ( $this->modules[ $name ] )();
				}

				return $this->modules[ $name ];
			}

			return null;
		}

		protected function load_modules( array $modules ) {
			foreach ( $modules as $alias => $module ) {
				if ( is_object( $module ) ) {
					$this->modules[ $alias ] = $module;
				} elseif ( class_exists( $module ) ) {
					$this->modules[ $alias ] = new $module();
				} elseif ( $module instanceof Closure ) {
					$this->modules[ $alias ] = $module;
				}
			}
		}
	}
}
