<?php
/**
 * includes/modules/class-noe-admin-prefix-inspector.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin_Module' ) ) {
	class NOE_Admin_Prefix_Inspector implements NOE_Admin_Module {
		use NOE_Template_Impl;

		private string $page_hook = '';

		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

			add_action( 'current_screen', [ $this, 'current_screen' ] );
		}

		public function add_admin_menu() {
			$this->page_hook = add_submenu_page(
				'tools.php',
				'Prefix Inspector',
				'Prefix Inspector',
				'administrator',
				'noe-pi',
				[ $this, 'output_admin_menu' ]
			);
		}

		public function current_screen( WP_Screen $screen ) {
			if ( $this->page_hook === $screen->id ) {
				wp_enqueue_script( 'noe-prefix-inspector' );
				wp_enqueue_style( 'noe-prefix-inspector' );
			}
		}

		public function output_admin_menu() {
			$default_autoload_options = [
				'yes' => 'Autoload: yes',
				'no'  => 'Autoload: no',
				'all' => 'Autoload: all',
			];

			$default_autoload = 'yes';

			$default_orders = [
				'prefix_asc'  => 'Prefix Asc.',
				'prefix_desc' => 'Prefix Desc.',
				'cnt_asc'     => 'Count Asc.',
				'cnt_desc'    => 'Count Desc.',
				'size_asc'    => 'Size Asc.',
				'size_desc'   => 'Size Desc.',
			];

			$default_orderby = 'cnt_desc';

			$default_delimiters = [
				'_' => 'Delimiter: _ (underscore)',
				'-' => 'Delimiter: - (hyphen)',
			];

			$default_delimiter = '_';

			$default_core_options = [
				'include' => 'Include Core Options',
				'exclude' => 'Exclude Core Options',
			];

			$default_core = 'exclude';

			$default_min_count = 1;

			$delimiter = wp_unslash( $_GET['delimiter'] ?? $default_delimiter );
			$autoload  = wp_unslash( $_GET['autoload'] ?? $default_autoload );
			$orderby   = wp_unslash( $_GET['orderby'] ?? $default_orderby );
			$core      = wp_unslash( $_GET['core'] ?? $default_core );
			$min_count = max( $default_min_count, intval( $_GET['min_count'] ?? '1' ) );

			if ( ! in_array( $delimiter, array_keys( $default_delimiters ), true ) ) {
				$delimiter = $default_delimiter;
			}

			if ( ! in_array( $autoload, array_keys( $default_autoload_options ), true ) ) {
				$autoload = $default_autoload;
			}

			if ( ! in_array( $orderby, array_keys( $default_orders ), true ) ) {
				$orderby = $default_orderby;
			}

			if ( ! in_array( $core, array_keys( $default_core_options ), true ) ) {
				$core = $default_core;
			}

			global $wpdb;

			/*
			 * SELECT
			 *  prefix,
			 *  COUNT(prefix) AS cnt,
			 *  SUM(size) AS size
			 * FROM (
			 *  SELECT
			 *   LEFT(option_name, POSITION('_' in option_name)) AS prefix,
			 *   LENGTH(option_value) AS size
			 *  FROM `naran_options`
			 *  WHERE autoload = 'yes'
			 * ) AS s
			 * WHERE
			 *  LENGTH(prefix) > 1
			 * GROUP BY prefix
			 * HAVING cnt >= 1
			 * ORDER BY size desc;
			 */
			$sub_query = "SELECT LEFT(option_name, POSITION('{$delimiter}' in option_name)) AS prefix, LENGTH(option_value) AS option_size FROM {$wpdb->options}";

			if ( 'yes' === $autoload ) {
				$sub_query .= " WHERE autoload='yes'";
			} elseif ( 'no' === $autoload ) {
				$sub_query .= " WHERE autoload='no'";
			} else {
				$sub_query .= " WHERE 1=1";
			}

			if ( 'exclude' === $core ) {
				$option_names = noe_get_core_option_names();
				$placeholders = implode( ', ', array_fill( 0, count( $option_names ), '%s' ) );

				$sub_query .= $wpdb->prepare( " AND option_name NOT IN ({$placeholders})", $option_names );
			}

			[ $f, $o ] = explode( '_', strtoupper( $orderby ), 2 );

			$query = "SELECT prefix, COUNT(prefix) AS cnt, SUM(option_size) AS size FROM ({$sub_query}) AS s "
			         . " WHERE LENGTH(prefix) > 1 GROUP BY prefix HAVING cnt >= {$min_count} ORDER BY {$f} {$o}";

			$results = $wpdb->get_results( $query );

			$this->template(
				'admin/prefix-inspector.php',
				[
					'page'             => 'noe-pi',
					'autoload'         => $autoload,
					'orderby'          => $orderby,
					'delimiter'        => $delimiter,
					'core'             => $core,
					'min_count'        => $min_count,
					'orders'           => &$default_orders,
					'autoload_options' => &$default_autoload_options,
					'delimiters'       => &$default_delimiters,
					'core_options'     => &$default_core_options,
					'items'            => &$results,
				]
			);
		}
	}
}
