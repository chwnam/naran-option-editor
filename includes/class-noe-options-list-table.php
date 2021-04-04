<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'NOE_Options_List_Table' ) ) {
	class NOE_Options_List_Table extends WP_List_Table {
		use NOE_Template_Impl;

		public function __construct() {
			parent::__construct(
				[
					'plural'   => 'options',
					'singular' => 'option',
					'ajax'     => false,
					'screen'   => null,
				]
			);
		}

		public function ajax_user_can(): bool {
			return current_user_can( 'administrator' );
		}

		public function get_columns(): array {
			return [
				'cb'           => '<input type="checkbox">',
				'option_name'  => 'Name',
				'option_value' => 'Value',
				'option_desc'  => 'Description',
				'autoload'     => 'Autoload',
				'option_size'  => 'Size',
			];
		}

		protected function get_sortable_columns(): array {
			return [
				'option_name' => 'option_name',
				'option_size' => [ 'option_size', true ],
			];
		}

		public function prepare_items() {
			global $wpdb;

			/*
			 * Params
			 * ------
			 * autoload: 'yes', 'no'
			 * o:        'core', 'custom'
			 * pf:       prefix filter keywords
			 * s:        search keyword\
			 * order:    asc, desc
			 * orderby:  option_name, option_size
			 */
			$where = 'WHERE 1=1';

			$autoload = wp_unslash( $_GET['autoload'] ?? '' );
			if ( 'yes' === $autoload ) {
				$where .= ' AND autoload = \'yes\'';
			} elseif ( 'no' === $autoload ) {
				$where .= ' AND autoload = \'no\'';
			}

			$o = wp_unslash( $_GET['o'] ?? '' );
			if ( 'core' === $o ) {
				$options_names = noe_get_core_option_names();
				$placeholders  = implode( ', ', array_fill( 0, count( noe_get_core_option_names() ), '%s' ) );

				$where .= $wpdb->prepare( " AND option_name IN ({$placeholders})", $options_names );
			} elseif ( 'custom' === $o ) {
				$options_names = noe_get_core_option_names();
				$placeholders  = implode( ', ', array_fill( 0, count( noe_get_core_option_names() ), '%s' ) );

				$where .= $wpdb->prepare( " AND option_name NOT IN ({$placeholders})", $options_names );
			}

			$pf = array_filter( array_map( 'sanitize_key', array_unique( (array) ( $_GET['pf'] ?? [] ) ) ) );
			if ( $pf ) {
				$buf = [];
				foreach ( $pf as $v ) {
					$buf[] = "option_name LIKE '" . str_replace( '_', '\_', esc_sql( $v ) ) . "%'";
				}
				$where .= ' AND (' . implode( ' OR ', $buf ) . ')';
				unset( $buf );
			}

			$s = wp_unslash( $_GET['s'] ?? '' );
			if ( $s ) {
				$where .= $wpdb->prepare(
					" AND (option_name LIKE '%%%s%%' OR option_value LIKE '%%%s%%')",
					$s,
					$s
				);
			}

			$orderby = '';
			$ob      = wp_unslash( $_GET['orderby'] ?? '' );
			$order   = 'desc' === wp_unslash( $_GET['order'] ?? '' ) ? 'DESC' : 'ASC';
			if ( 'option_name' === $ob ) {
				$orderby = "ORDER BY option_name {$order}";
			} elseif ( 'option_size' === $ob ) {
				$orderby = "ORDER BY option_size {$order}";
			}

			$per_page = $this->get_items_per_page( 'noe_option_per_page' );
			$paged    = max( 1, intval( $_GET['paged'] ?? '0' ) );
			$offset   = ( $paged - 1 ) * $per_page;

			$query       = "SELECT SQL_CALC_FOUND_ROWS option_id, option_name, option_value, autoload, LENGTH(option_value) AS option_size  FROM {$wpdb->options} {$where} {$orderby} LIMIT {$offset}, {$per_page}";
			$rows        = $wpdb->get_results( $query );
			$total_items = intval( $wpdb->get_var( 'SELECT FOUND_ROWS()' ) );

			$this->set_pagination_args(
				[
					'total_items' => $total_items,
					'per_page'    => $per_page,
				]
			);

			$this->items = $rows;
		}

		protected function column_cb( $item ) {
			printf(
				'<label class="screen-reader-text" for="cb-select-%1$d">%2$s</label>' .
				'<input id="cb-select-%1$d" type="checkbox" name="option[]" value="%1$d">',
				$item->option_id,
				'Select option `' . esc_html( $item->option_name ) . '`'
			);
		}

		protected function column_option_name( $item ) {
			if ( strlen( $item->option_name ) > 30 ) {
				$option_name = substr( $item->option_name, 0, 27 ) . ' [&hellip;]';
			} else {
				$option_name = $item->option_name;
			}

			printf(
				'<strong>[#%d] <a href="%s" class="row-title" title="%s">%s</a></strong>',
				intval( $item->option_id ),
				esc_url( add_query_arg( 'option_id', $item->option_id ) ),
				esc_attr( $item->option_name ),
				esc_html( $option_name )
			);
		}

		protected function column_option_value( $item ) {
			if ( is_serialized( $item->option_value ) ) {
				echo '<span>[Serialized]</span> ';
			}

			if ( mb_strlen( $item->option_value ) > 55 ) {
				$value = mb_substr( $item->option_value, 0, 52 ) . ' [&hellip;]';
			} else {
				$value = $item->option_value;
			}

			echo esc_html( $value );
		}

		protected function column_autoload( $item ) {
			echo $item->autoload === 'yes' ? 'Yes' : 'No';
		}

		protected function column_option_desc( $item ) {
			echo ''; // TODO: implement option description
		}

		protected function column_option_size( $item ) {
			echo $item->option_size;
		}

		protected function handle_row_actions( $item, $column_name, $primary ): string {
			if ( $primary !== $column_name ) {
				return '';
			}

			$actions = [
				'edit'  => sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					esc_url( add_query_arg( 'option_id', $item->option_id ) ),
					'Edit &#8220;' . esc_attr( $item->option_name ) . '&#8221;',
					'Edit'
				),
				'trash' => sprintf(
					'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
					esc_url( noe_get_option_remove_url( $item->option_id ) ),
					'Remove &#8220;' . esc_attr( $item->option_name ) . '&#8221;',
					'Remove'
				),
			];

			return $this->row_actions( $actions );
		}

		protected function get_bulk_actions(): array {
			return [
				'delete' => 'Delete Selected'
			];
		}

		protected function display_tablenav( $which ) {
			parent::display_tablenav( $which );

			if ( 'top' === $which ) {
				$meta = noe_meta()->user_prefix_filters;

				$prefix_filters = $meta->get_value( get_current_user_id() );
				if ( ! is_array( $prefix_filters ) ) {
					$prefix_filters = [];
				}

				$this->template(
					'admin/prefix-filter.php',
					[ 'prefix_filters' => &$prefix_filters ]
				);
			}
		}

		/**
		 * @param string $which
		 */
		protected function extra_tablenav( $which ) {
			$this->template(
				'admin/extra-tablenav.php',
				[
					'which'              => $which,
					'autoload'           => wp_unslash( $_GET['autoload'] ?? '' ),
					'backup_options_url' => add_query_arg(
						[
							'action'     => 'noe_backup_options',
							'_noe_nonce' => wp_create_nonce( 'noe_backup_options' ),
						],
						admin_url( 'admin-post.php' )
					)
				]
			);
		}

		/**
		 * @return string[]
		 * @see populate_options()
		 */
		protected function get_views(): array {
			global $wpdb;

			$status_links = [
				'all'            => '',
				'core-options'   => '',
				'custom-options' => '',
			];

			$list_url = noe_get_options_list_url();

			$all = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->options}" ) );

			$option_names = noe_get_core_option_names();
			$placeholders = implode( ', ', array_fill( 0, count( $option_names ), '%s' ) );
			$core_options = intval(
				$wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name in ({$placeholders})",
						$option_names
					)
				)
			);

			$status_links['all'] = sprintf(
				'<a class="%s" href="%s">All <span class="count">(%d)</span></a>',
				empty( $_GET['o'] ) ? 'current' : '',
				esc_url( $list_url ),
				$all
			);

			$status_links['core-options'] = sprintf(
				'<a class="%s" href="%s">Core Options <span class="count">(%d)</span></a>',
				'core' === ( $_GET['o'] ?? '' ) ? 'current' : '',
				esc_url( add_query_arg( 'o', 'core', $list_url ) ),
				$core_options
			);

			$status_links['custom-options'] = sprintf(
				'<a class="%s" href="%s">Custom Options <span class="count">(%d)</span></a>',
				'custom' === ( $_GET['o'] ?? '' ) ? 'current' : '',
				esc_url( add_query_arg( 'o', 'custom', $list_url ) ),
				$all - $core_options
			);

			return $status_links;
		}
	}
}
