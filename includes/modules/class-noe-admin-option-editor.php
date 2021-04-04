<?php
/**
 * includes/modules/class-noe-admin-option-editor.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin_Option_Editor' ) ) {
	class NOE_Admin_Option_Editor implements NOE_Admin_Module {
		use NOE_Template_Impl;

		private string $page_hook = '';

		private NOE_Options_List_Table $table;

		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

			add_action( 'current_screen', [ $this, 'current_screen' ] );

			add_filter( 'set_screen_option_noe_option_per_page', [ $this, 'set_screen_option_per_page' ], 10, 3 );
		}

		public function current_screen( WP_Screen $screen ) {
			if ( $this->page_hook === $screen->id ) {
				$this->handle_action();

				if ( ! isset( $_GET['option_id'] ) ) {
					$this->table = new NOE_Options_List_Table();

					$screen->add_option(
						'per_page',
						[
							'default' => 20,
							'option'  => 'noe_option_per_page',
						]
					);

					$this->table->prepare_items();

					wp_enqueue_script( 'noe-option-table' );
					wp_localize_script(
						'noe-option-table',
						'noeOptionTable',
						[
							'ajaxUrl'                 => admin_url( 'admin-ajax.php' ),
							'nonce'                   => wp_create_nonce( 'noe-option-table' ),
							'textPrefixAlreadyExists' => 'The prefix is already added. Please choose another one.',
						]
					);
					wp_enqueue_style( 'noe-option-table' );
				} else {
					wp_enqueue_script( 'noe-option-edit' );
					wp_enqueue_style( 'noe-option-edit' );
				}
			}
		}

		public function add_admin_menu() {
			$this->page_hook = add_submenu_page(
				'tools.php',
				'Option Editor',
				'Option Editor',
				'administrator',
				'noe',
				[ $this, 'output_admin_menu' ]
			);
		}

		public function output_admin_menu() {
			if ( isset( $_GET['option_id'] ) ) {
				$this->output_single_page();
			} else {
				$this->output_list_page();
			}
		}

		public function edit_option() {
			check_admin_referer( 'noe_edit_option', '_noe_nonce' );

			if ( current_user_can( 'administrator' ) ) {
				$option_id    = $_POST['option_id'] ?? false;
				$option_name  = sanitize_text_field( $_POST['option_name'] ?? '' );
				$option_value = wp_unslash( $_POST['option_value'] ?? '' );
				$description  = sanitize_text_field( $_POST['description'] ?? '' );
				$autoload     = 'yes' === ( $_POST['autoload'] ?? 'no' );

				if ( empty( $option_id ) || ( ! is_numeric( $option_id ) && 'new' !== $option_id ) ) {
					add_settings_error( 'noe', 'error', 'Error: empty option id' );
				} else {
					global $wpdb;

					if ( 'new' === $option_id ) {
						$existing_id = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT option_id FROM {$wpdb->options} WHERE option_name = %s LIMIT 0, 1",
								$option_name
							)
						);
						if ( $existing_id ) {
							add_settings_error(
								'noe',
								'error',
								sprintf( 'Error: option name `%s` is already exists.', $option_name )
							);
						} else {
							add_option( $option_name, $option_value, '', $autoload );
							$option_id = $wpdb->get_var(
								$wpdb->prepare(
									"SELECT option_id FROM {$wpdb->options} WHERE option_name = %s LIMIT 0, 1",
									$option_name
								)
							);
							noe()->desc_table->update_description( $option_id, $description );
							add_settings_error( 'noe', 'success', 'Option is successfully inserted.', 'success' );
						}
					} elseif ( is_numeric( $option_id ) ) {
						$existing_id = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT option_id FROM {$wpdb->options} WHERE option_name = %s AND option_id != %d LIMIT 0, 1",
								$option_name,
								$option_id
							)
						);
						if ( $existing_id ) {
							add_settings_error(
								'noe',
								'error',
								sprintf( 'Error: option `%s` is already exists.', $option_name )
							);
						} else {
							$existing = $wpdb->get_row(
								$wpdb->prepare(
									"SELECT * FROM {$wpdb->options} WHERE option_id = %d",
									$option_id
								)
							);

							$existing->autoload = filter_var( $existing->autoload, FILTER_VALIDATE_BOOLEAN );

							if ( $existing->option_name === $option_name && $existing->autoload === $autoload ) {
								update_option( $option_name, $option_value, $autoload );
							} else {
								$wpdb->update(
									$wpdb->options,
									[
										'option_name' => $option_name,
										'autoload'    => $autoload ? 'yes' : 'no.'
									],
									[ 'option_name' => $existing->option_name ]
								);
							}
							noe()->desc_table->update_description( $existing->option_id, $description );
							add_settings_error( 'noe', 'success', 'Option is successfully updated.', 'success' );
						}
					}
				}

				$errors = get_settings_errors( 'noe' );
				if ( ! empty( $errors ) ) {
					set_transient( 'noe_settings_errors', $errors, 30 );
				}
				$redirect = add_query_arg( 'option_id', $option_id, wp_get_referer() );
				wp_safe_redirect( $redirect );
				exit;
			}
		}

		public function delete_option() {
			global $wpdb;

			$option_id = $_GET['option_id'] ?? false;

			if ( current_user_can( 'administrator' ) && $option_id ) {
				check_admin_referer( 'noe_delete_option_' . $option_id, '_noe_nonce' );
				$wpdb->delete(
					$wpdb->options,
					[ 'option_id' => $option_id ],
					[ 'option_id' => '%d' ]
				);
				noe()->desc_table->delete_description( $option_id );

				$return_url = wp_unslash( $_GET['return_url'] ?? '' );
				if ( empty( $return_url ) ) {
					$return_url = wp_get_referer();
				}
				wp_safe_redirect( $return_url );
				exit;
			}
		}

		/**
		 * @param mixed  $screen_option
		 * @param string $option
		 * @param mixed  $value
		 *
		 * @return int
		 */
		public function set_screen_option_per_page( $screen_option, string $option, $value ): int {
			if ( 'noe_option_per_page' === $option ) {
				$screen_option = intval( $value );
			}

			return $screen_option;
		}

		public function backup_options() {
			check_ajax_referer( 'noe_backup_options', '_noe_nonce' );

			if ( current_user_can( 'administrator' ) ) {
				global $wpdb;

				$user    = wp_get_current_user();
				$version = NOE_VERSION;
				$now     = wp_date( 'Y-m-d H:i:s T' );

				$content = <<< PHP_EOL
-- Naran Option Editor Backup 
-- Version:  {$version}
-- Operator: {$user->display_name} ({$user->user_email})
-- Created:  {$now}

-- Table Schema
DROP TABLE IF EXISTS `{$wpdb->options}`;

PHP_EOL;

				$create_table = $wpdb->get_row( "SHOW CREATE TABLE {$wpdb->options}", ARRAY_N );
				if ( ! $create_table ) {
					wp_die( 'Cannot get SHOW CREATE TABLE result.' );
				}
				$content .= $create_table[1] . ";\n\n";
				$content .= <<< PHP_EOL
-- Table Data

PHP_EOL;

				$dbh        = $wpdb->dbh;
				$use_mysqli = $wpdb->use_mysqli;

				$query = "SELECT * FROM {$wpdb->options}";

				if ( $wpdb->use_mysqli ) {
					$result = mysqli_query( $dbh, $query );
					$rows   = mysqli_affected_rows( $dbh );
				} elseif ( function_exists( 'mysql_query' ) && function_exists( 'mysql_affected_rows' ) ) {
					$result = mysql_query( $query, $dbh );
					$rows   = mysql_affected_rows( $dbh );
				} else {
					$result = false;
					$rows   = false;
				}

				$limit  = 1000;
				$buffer = [];

				$push_to_buffer = function ( $row ) use ( &$buffer ) {
					$str = implode(
						', ',
						[
							$row[0],                                                       // option_id,
							"'{$row[1]}'",                                                 // option_name,
							"'" . addslashes( $row[2] ) . "'", // option_value,
							"'{$row[3]}'",                                                 // autoload
						]
					);

					$buffer[] = "({$str})";
				};

				if ( $use_mysqli && $result instanceof mysqli_result ) {
					$r = 0;
					while ( $r < $rows ) {
						$buffer  = [];
						$content .= "INSERT INTO `{$wpdb->options}` VALUES ";
						while ( $row = mysqli_fetch_row( $result ) ) {
							$push_to_buffer( $row );
							++ $r;
							if ( $r % $limit === 0 ) {
								break;
							}
						}
						$content .= implode( ", ", $buffer ) . ";\n";
					}
				} elseif ( is_resource( $result ) && function_exists( 'mysql_fetch_row' ) ) {
					$r = 0;
					while ( $r < $rows ) {
						$buffer  = [];
						$content .= "INSERT INTO `{$wpdb->options}` VALUES ";
						while ( $row = mysql_fetch_row( $result ) ) {
							$push_to_buffer( $row );
							++ $r;
							if ( $r % $limit === 0 ) {
								break;
							}
						}
						$content .= implode( ", ", $buffer ) . ";\n";
					}
				}

				$content .= "\n-- End of backup\n";

				$file_name = $wpdb->options . '-' . wp_date( 'Ymd-His-T' ) . '.sql';

				if ( function_exists( 'gzencode' ) ) {
					$content   = gzencode( $content, 9 );
					$mime_type = 'application/gzip';
					$file_name .= '.gz';
				} else {
					$mime_type = 'application/sql';
				}

				header( "Pragma: public" );
				header( "Expires: 0" );
				header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( "Cache-Control: private", false ); // required for certain browsers
				header( "Content-Type: {$mime_type}" );
				header( "Content-Disposition: attachment; filename={$file_name};" );
				header( "Content-Transfer-Encoding: binary" );
				header( "Content-Length: " . strlen( $content ) );
				echo $content;
				exit;
			}
		}

		/**
		 * AJAX Callback: add prefix.
		 */
		public function add_prefix() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			$prefix = $_REQUEST['prefix'] ?? false;

			if ( $prefix && current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( ! isset( $prefix_filters[ $prefix ] ) ) {
					$prefix_filters[ $prefix ] = true;
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: remove prefix.
		 */
		public function remove_prefix() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			$prefix = $_REQUEST['prefix'] ?? false;

			if ( $prefix && current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( isset( $prefix_filters[ $prefix ] ) ) {
					unset( $prefix_filters[ $prefix ] );
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: clear all prefixes.
		 */
		public function clear_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			if ( current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( ! empty( $prefix_filters ) ) {
					$meta_field->update( $user_id, [] );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: enable prefix.
		 */
		public function enable_prefix() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			$prefix = $_REQUEST['prefix'] ?? false;

			if ( $prefix && current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( isset( $prefix_filters[ $prefix ] ) && ! $prefix_filters[ $prefix ] ) {
					$prefix_filters[ $prefix ] = true;
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: enable all prefixes.
		 */
		public function enable_all_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			if ( current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( is_array( $prefix_filters ) && ! empty( $prefix_filters ) ) {
					foreach ( array_keys( $prefix_filters ) as $prefix ) {
						$prefix_filters[ $prefix ] = true;
					}
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: disable prefix.
		 */
		public function disable_prefix() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			$prefix = $_REQUEST['prefix'] ?? false;

			if ( $prefix && current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( isset( $prefix_filters[ $prefix ] ) && $prefix_filters[ $prefix ] ) {
					$prefix_filters[ $prefix ] = false;
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: disable prefixes.
		 */
		public function disable_all_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );

			if ( current_user_can( 'administrator' ) ) {
				$user_id        = get_current_user_id();
				$meta_field     = noe_meta()->user_prefix_filters;
				$prefix_filters = $meta_field->get_value( $user_id );

				if ( is_array( $prefix_filters ) && ! empty( $prefix_filters ) ) {
					foreach ( array_keys( $prefix_filters ) as $prefix ) {
						$prefix_filters[ $prefix ] = false;
					}
					$meta_field->update( $user_id, $prefix_filters );
				}

				wp_send_json_success();
			}
		}

		private function output_single_page() {
			global $wp_settings_errors;

			$errors = get_transient( 'noe_settings_errors' );
			if ( false !== $errors ) {
				delete_transient( 'noe_settings_errors' );
				$wp_settings_errors = array_merge( (array) $wp_settings_errors, $errors );
			}
			if ( is_numeric( $_GET['option_id'] ) ) {
				$this->output_edit_page( absint( $_GET['option_id'] ) );
			} elseif ( 'new' === $_GET['option_id'] ) {
				$this->output_new_page();
			}
		}

		private function output_list_page() {
			if ( $this->table ) {
				$this->template(
					'admin/list-table.php',
					[
						'table' => $this->table,
						'o'     => wp_unslash( $_GET['o'] ?? '' ),
					]
				);
			}
		}

		private function output_new_page() {
			$this->template(
				'admin/edit-option.php',
				[ 'option' => null ]
			);
		}

		private function output_edit_page( int $option_id ) {
			global $wpdb;

			if ( ! $option_id ) {
				return;
			}

			$option = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->options} WHERE option_id = %d",
					$option_id
				)
			);

			$description = noe()->desc_table->get_description( $option_id );
			if ( $description ) {
				$option->description = $description;
			}

			if ( $option ) {
				$this->template( 'admin/edit-option.php', [ 'option' => $option ] );
			}
		}

		private function handle_action() {
			$action = $_GET['action'] ?? '';

			if ( $action && $action != '-1' ) {
				check_admin_referer( 'bulk-options' );

				switch ( $_GET['action'] ) {
					case 'delete':
						$this->bulk_delete();
						break;
				}

				$return_url = remove_query_arg( [ 'action', 'action2', 'option_id' ], wp_get_referer() );
				wp_redirect( $return_url );
				exit;
			}
		}

		private function bulk_delete() {
			if ( current_user_can( 'administrator' ) ) {
				$option_ids = array_filter(
					array_unique( array_map( 'absint', (array) ( $_GET['option'] ?? [] ) ) )
				);

				if ( $option_ids ) {
					global $wpdb;

					$placeholder = implode( ', ', array_fill( 0, count( $option_ids ), '%d' ) );

					$query = $wpdb->prepare(
						"SELECT option_name FROM {$wpdb->options} WHERE option_id IN ({$placeholder})",
						$option_ids
					);

					$option_names = $wpdb->get_col( $query );
					if ( $option_names ) {
						foreach ( $option_names as $option_name ) {
							delete_option( $option_name );
						}
					}
					noe()->desc_table->bulk_delete( $option_ids );
				}
			}
		}
	}
}
