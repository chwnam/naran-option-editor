<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Prefix_Filter' ) ) {
	class NOE_Prefix_Filter implements NOE_Module {
		protected NOE_Meta $field;

		public function __construct() {
			$this->field = noe_meta()->user_prefix_filters;
		}

		public function add_prefix( int $user_id, string $prefix ) {
			$filter = $this->get_filter( $user_id );
			if ( ! isset( $filter[ $prefix ] ) ) {
				$filter[ $prefix ] = true;
				$this->update_filter( $user_id, $filter );
			}
		}

		public function remove_prefix( int $user_id, string $prefix ) {
			$filter = $this->get_filter( $user_id );
			if ( isset( $filter[ $prefix ] ) ) {
				unset( $filter[ $prefix ] );
				$this->update_filter( $user_id, $filter );
			}
		}

		public function clear_prefixes( int $user_id ) {
			$filter = $this->get_filter( $user_id );
			if ( ! empty( $filter ) ) {
				$filter = [];
				$this->update_filter( $user_id, $filter );
			}
		}

		public function set_prefix( int $user_id, string $prefix, bool $enabled ) {
			$filter = $this->get_filter( $user_id );
			if ( ! isset( $filter[ $prefix ] ) || $filter[ $prefix ] !== $enabled ) {
				$filter[ $prefix ] = $enabled;
				$this->update_filter( $user_id, $filter );
			}
		}

		public function set_all_prefixes( int $user_id, bool $enabled ) {
			$filter = $this->get_filter( $user_id );
			foreach ( array_keys( $filter ) as $prefix ) {
				$filter[ $prefix ] = $enabled;
			}
			$this->update_filter( $user_id, $filter );
		}

		protected function get_filter( int $user_id ): array {
			return $this->field->get_value( $user_id );
		}

		protected function update_filter( int $user_id, array $filter ) {
			return $this->field->update( $user_id, $filter );
		}
	}
}

