<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Registerer_Meta' ) ) {
	/**
	 * Class NOE_Registerer_Meta
	 *
	 * @property-read NOE_Meta $user_prefix_filters
	 */
	class NOE_Registerer_Meta implements NOE_Registerer {
		/**
		 * @var array<string, array>|NOE_Meta[]
		 */
		private array $fields = [];

		public function __construct() {
			add_action( 'init', [ $this, 'register_items' ] );
		}

		public function register_items() {
			foreach ( $this->get_items() as $index => $item ) {
				if ( $item instanceof NOE_Meta ) {
					$item->register();
					$alias = is_int( $index ) ? $item->get_key() : $index;

					$this->fields[ $alias ] = [ $item->get_object_type(), $item->object_subtype, $item->get_key() ];
				}
			}
		}

		public function get_items(): array {
			return [
				'user_prefix_filters' => new NOE_Meta(
					'user',
					'noe_prefix_filters',
					[
						'object_subtype'    => 'user',
						'type'              => 'array',
						'description'       => __( "'Each user's prefix filters list.", 'noe' ),
						'default'           => [],
						'single'            => true,
						'show_in_rest'      => false,
						'sanitize_callback' => function ( $value ) {
							$sanitized = [];

							foreach ( $value as $k => $v ) {
								$k = sanitize_text_field( $k );
								$v = filter_var( $v, FILTER_VALIDATE_BOOLEAN );

								if ( $k && is_bool( $v ) ) {
									$sanitized[ $k ] = $v;
								}
							}

							return $sanitized;
						},
					]
				)
			];
		}

		public function __get( string $alias ): ?NOE_Meta {
			if ( isset( $this->fields[ $alias ] ) ) {
				if ( is_array( $this->fields[ $alias ] ) ) {
					$this->fields[ $alias ] = NOE_Meta::factory( ...$this->fields[ $alias ] );
				}

				return $this->fields[ $alias ];
			} else {
				return null;
			}
		}
	}
}

