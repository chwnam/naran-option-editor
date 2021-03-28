<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'NOE_Template_Impl' ) ) {
	trait NOE_Template_Impl {
		protected function template( string $tmpl_name, array $context = [], bool $echo = true ): string {
			return $this->include_with_context( $this->locate_tmpl( $tmpl_name ), $context, $echo );
		}

		protected function include_with_context( string $path, array $context = [], bool $echo = true ): string {
			if ( file_exists( $path ) && is_readable( $path ) ) {
				if ( ! empty( $context ) ) {
					extract( $context, EXTR_SKIP );
				}

				if ( ! $echo ) {
					ob_start();
				}

				/** @noinspection PhpIncludeInspection */
				include $path;

				return $echo ? '' : ob_get_clean();
			}

			return '';
		}

		protected function locate_tmpl( string $tmpl_name ): string {
			$tmpl_name = trim( $tmpl_name, '/\\' );

			return dirname( NOE_MAIN ) . "/templates/{$tmpl_name}";
		}
	}
}

