<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Desc_Table' ) ) {
	class NOE_Desc_Table implements NOE_Module {
		public function __construct() {
			add_action( 'noe_activation', [ $this, 'activation' ] );
			add_action( 'noe_deactivation', [ $this, 'deactivation' ] );
		}

		public function activation() {
			$this->include_required();
			$this->create_table();
		}

		public function deactivation() {
		}

		public function uninstall() {
			$this->drop_table();
		}

		public static function get_table(): string {
			global $wpdb;

			return "{$wpdb->prefix}noe_desc";
		}

		public function get_description( int $option_id ): string {
			global $wpdb;

			$table = static::get_table();
			$query = $wpdb->prepare( "SELECT description FROM {$table} WHERE option_id = %d", $option_id );

			return strval( $wpdb->get_var( $query ) );
		}

		public function update_description( int $option_id, string $description ): bool {
			global $wpdb;

			$table = static::get_table();

			$query = $wpdb->prepare(
				"INSERT INTO {$table} (option_id, description) VALUES (%d, %s) ON DUPLICATE KEY UPDATE description = %s",
				$option_id,
				$description,
				$description
			);

			return (bool) $wpdb->query( $query );
		}

		public function delete_description( int $option_id ) {
			global $wpdb;

			$wpdb->delete(
				static::get_table(),
				[ 'option_id' => $option_id ],
				[ 'option_id' => '%d' ]
			);
		}

		public function bulk_delete( array $option_ids ) {
			global $wpdb;

			$option_ids = array_unique( array_filter( array_map( 'absint', $option_ids ) ) );
			if ( $option_ids ) {
				$table  = static::get_table();
				$holder = implode( ', ', array_fill( 0, count( $option_ids ), '%d' ) );
				$query  = $wpdb->prepare( "DELETE FROM {$table} WHERE option_id IN ({$holder})", $option_ids );

				$wpdb->query( $query );
			}
		}

		protected function create_table() {
			global $wpdb;

			$table = self::get_table();
			$query = "CREATE TABLE IF NOT EXISTS {$table} ("
			         . "option_id INT(20) UNSIGNED NOT NULL,"
			         . "description TEXT NOT NULL,"
			         . "UNIQUE KEY unique_option_id (option_id)"
			         . ") ENGINE=InnoDB DEFAULT CHARSET={$wpdb->charset} COLLATE={$wpdb->collate}";

			dbDelta( $query );
		}

		protected function drop_table() {
			global $wpdb;

			$table = self::get_table();
			$wpdb->query( "DROP TABLE IF EXISTS {$table};" );
		}

		protected function include_required() {
			if ( ! function_exists( 'dbDelta' ) ) {
				include_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}
		}
	}
}
