<?php
/**
 * includes/modules/class-noe-admin-option-editor.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Admin_Option_Editor' ) ) {
	class NOE_Admin_Option_Editor implements NOE_Admin_Module, NOE_Submenu_Page {
		use NOE_Template_Impl;

		private NOE_Options_List_Table $table;

		public function __construct() {
			add_filter( 'set_screen_option_noe_option_per_page', [ $this, 'set_screen_option_per_page' ], 10, 3 );
		}

		public function current_screen( WP_Screen $screen ) {
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
						'ajaxUrl'                     => admin_url( 'admin-ajax.php' ),
						'nonce'                       => wp_create_nonce( 'noe-option-table' ),
						'textAdd'                     => __( 'Add', 'noe' ),
						'textCancel'                  => __( 'Cancel', 'noe' ),
						'textCheckedIsZeroLength'     => __( 'Check one or more options.', 'noe' ),
						'textConfirmDeleteOption'     => __( 'Are you sure you want to delete this option?', 'noe' ),
						'textConfirmRemoveAllFilters' => __( 'Are you sure you want to remove all prefixes?', 'noe' ),
						'textPrefixAlreadyExists'     => __( 'The prefix is already added. Please choose another one.', 'noe' ),
						'textRestoreOptionAlert'      => __( 'Are you sure you want to restore option table with the file?', 'noe' ),
						'textRestoreComplete'         => __( 'The option table is restored. The page is now reloaded.', 'noe' ),
						'textSubmit'                  => __( 'Submit', 'noe' ),
					]
				);
				wp_enqueue_style( 'noe-option-table' );
			} else {
				wp_enqueue_script( 'noe-option-edit' );
				wp_enqueue_style( 'noe-option-edit' );
			}
		}

		public function output_submenu_page() {
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
				$description  = sanitize_textarea_field( $_POST['description'] ?? '' );
				$autoload     = 'yes' === ( $_POST['autoload'] ?? 'no' );

				if ( empty( $option_id ) || ( ! is_numeric( $option_id ) && 'new' !== $option_id ) ) {
					add_settings_error( 'noe', 'error', __( 'Error: empty option id', 'noe' ) );
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
								sprintf(
								/* translators: option name */
									__( 'Error: option name `%s` is already exists.', 'noe' ),
									$option_name
								)
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
							add_settings_error(
								'noe',
								'success',
								__( 'Option is successfully added.', 'noe' ),
								'success'
							);
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
								sprintf(
								/* translators: option name */
									__( 'Error: option `%s` is already exists.', 'noe' ),
									$option_name
								)
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
							add_settings_error(
								'noe',
								'success',
								__( 'Option is successfully updated.', 'noe' ),
								'success'
							);
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

				$return_url = remove_query_arg(
					[ '_wp_http_referer' ],
					wp_unslash( $_GET['return_url'] ?? '' )
				);
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
		 * Ajax Callback restore option
		 */
		public function restore_options() {
			check_ajax_referer( 'noe-option-table', '_noe_nonce' );

			global $wpdb;

			try {
				if ( ! $wpdb->use_mysqli ) {
					throw new Exception( __( 'Use mysqli to restore option table with this plugin.', 'noe' ) );
				}

				if ( ! current_user_can( 'administrator' ) ) {
					throw new Exception( __( 'You are not allowed to do this task', 'noe' ) );
				}

				if ( ! isset( $_FILES['backup_file'] ) ) {
					throw new Exception( __( 'Backup file not found.', 'noe' ) );
				}

				$file  = &$_FILES['backup_file'];
				$mimes = [
					'gz'  => 'application/gzip',
					'sql' => 'text/plain',
				];

				$mimes_filter = function ( array $mimes ) use ( &$mimes_filter ): array {
					remove_filter( 'upload_mimes', $mimes_filter );
					$mimes['noe-gz'] = 'application/gzip';

					return $mimes;
				};
				add_action( 'upload_mimes', $mimes_filter );
				$checked = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $mimes );
				if ( ! $checked['ext'] && ! $checked['type'] && ! $checked['proper_filename'] ) {
					throw new Exception( __( 'The file uploaded is not allowed.', 'noe' ) );
				}

				$ext   = pathinfo( $file['name'], PATHINFO_EXTENSION );
				$query = file_get_contents( $file['tmp_name'] );
				if ( $ext === 'gz' ) {
					if ( ! function_exists( 'gzdecode' ) ) {
						throw new Exception(
							__( 'A gzipped file is uploaded, but the server cannot decode the file.', 'noe' )
						);
					}
					$query = gzdecode( $query );
				}

				$dbh = $wpdb->dbh;
				if ( mysqli_multi_query( $dbh, $query ) ) {
					wp_send_json_success();
				} else {
					$error = mysqli_error( $dbh );
					throw new Exception( $error );
				}
			} catch ( Exception $e ) {
				wp_send_json_error( new WP_Error( 'Error', $e->getMessage() ) );
			}

			wp_send_json_success();
		}

		/**
		 * AJAX Callback: add prefix.
		 */
		public function add_prefix() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			$prefix = $_REQUEST['prefix'] ?? false;
			if ( $prefix && current_user_can( 'administrator' ) ) {
				noe()->prefix_filter->add_prefix( get_current_user_id(), $prefix );
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
				noe()->prefix_filter->remove_prefix( get_current_user_id(), $prefix );
				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: clear all prefixes.
		 */
		public function clear_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			if ( current_user_can( 'administrator' ) ) {
				noe()->prefix_filter->clear_prefixes( get_current_user_id() );
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
				noe()->prefix_filter->set_prefix( get_current_user_id(), $prefix, true );
				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: enable all prefixes.
		 */
		public function enable_all_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			if ( current_user_can( 'administrator' ) ) {
				noe()->prefix_filter->set_all_prefixes( get_current_user_id(), true );
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
				noe()->prefix_filter->set_prefix( get_current_user_id(), $prefix, false );
				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: disable prefixes.
		 */
		public function disable_all_prefixes() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			if ( current_user_can( 'administrator' ) ) {
				noe()->prefix_filter->set_all_prefixes( get_current_user_id(), false );
				wp_send_json_success();
			}
		}

		/**
		 * AJAX Callback: edit option description.
		 */
		public function edit_option_desc() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			if ( current_user_can( 'administrator' ) ) {
				$option_id   = absint( $_REQUEST['option_id'] ?? '0' );
				$option_desc = sanitize_textarea_field( $_REQUEST['option_desc'] ?? '' );
				if ( $option_id ) {
					$success = noe()->desc_table->update_description( $option_id, $option_desc );
					if ( $success ) {
						wp_send_json_success();
					} else {
						wp_send_json_error( new WP_Error( 'Error', 'Error updating description.' ) );
					}
				}
			}
		}

		/**
		 * AJAX Callback: bulk edit option description.
		 */
		public function bulk_edit_option_desc() {
			check_ajax_referer( 'noe-option-table', 'nonce' );
			if ( current_user_can( 'administrator' ) ) {
				$option_ids  = array_unique( array_filter( array_map( 'absint', $_REQUEST['option_ids'] ?? [] ) ) );
				$option_desc = sanitize_textarea_field( $_REQUEST['option_desc'] ?? '' );
				if ( $option_ids ) {
					$success = noe()->desc_table->bulk_update_description( $option_ids, $option_desc );
					if ( $success ) {
						wp_send_json_success();
					} else {
						wp_send_json_error( new WP_Error( 'Error', 'Error updating description.' ) );
					}
				}
			}
		}

		/**
		 * AJAX Callback: option name search.
		 */
		public function option_name_search() {
			check_ajax_referer( 'noe_edit_option', 'nonce' );

			if ( current_user_can( 'administrator' ) ) {
				$errors = new WP_Error();

				$default_span = [ 'wp-core', 'themes', 'plugins' ];

				$code_span = array_unique(
					array_filter( array_map( 'sanitize_key', (array) ( $_REQUEST['span'] ?? [] ) ) )
				);

				// Span check.
				if ( empty( $code_span ) ) {
					$errors->add( 'Error', __( 'Empty code span', 'noe' ) );
				} elseif ( ! empty( array_diff( $code_span, $default_span ) ) ) {
					$errors->add(
						'Error',
						sprintf(
						/* translators: code span text */
							__( 'Unsupported code span: %s', 'noe' ),
							implode( ', ', array_diff( $code_span, $default_span ) )
						)
					);
				}

				$option_name = sanitize_text_field( $_REQUEST['option_name'] ?? '' );

				global $wpdb;

				// Option name check.
				$has_option_name = boolval(
					$wpdb->get_var(
						$wpdb->prepare(
							"SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name = %s LIMIT 0, 1",
							$option_name
						)
					)
				);

				if ( ! $has_option_name ) {
					$errors->add(
						'Error',
						/* translators: undefined option name */
						__( 'Unknown option name: %s', 'noe' ),
						$option_name
					);
				}

				// Check if the server can run 'egrep'.
				exec( 'which egrep', $output, $result );

				if ( 0 === $result ) {
					$egrep = escapeshellcmd( $output[0] );
				} else {
					$egrep = '/usr/bin/egrep';
				}

				if ( ! is_executable( $egrep ) ) {
					$errors->add( 'Error', 'Cannot find, or cannot execute \'egrep\' on the server.', 'noe' );
				}

				if ( $errors->has_errors() ) {
					wp_send_json_error( $errors );
				}

				// create egrep command
				$command = "{$egrep} --color=never -rn \"('|\\\"){$option_name}('|\\\")\" ";
				$args    = [];

				if ( in_array( 'wp-core', $code_span, true ) ) {
					$args[] = escapeshellarg( ABSPATH ) . '*\'.php\'';
					$args[] = escapeshellarg( ABSPATH . 'wp-admin' );
					$args[] = escapeshellarg( ABSPATH . 'wp-includes' );
				}

				if ( in_array( 'themes', $code_span, true ) ) {
					$args[] = escapeshellarg( WP_CONTENT_DIR . '/themes' );
				}

				if ( in_array( 'plugins', $code_span, true ) ) {
					$args[] = escapeshellarg( WP_CONTENT_DIR . '/plugins' );
				}

				$command .= implode( ' ', $args );

				exec( $command, $output, $result );

				// 1: nothing matched.
				if ( 0 !== $result && 1 !== $result) {
					$errors->add(
						'Error',
						sprintf(
							'Error running egrep. command: %s, code: %d, message: %s',
							$command,
							$result,
							implode( "\n", $output )
						)
					);
				}

				if ( $errors->has_errors() ) {
					wp_send_json_error( $errors );
				}

				if ( $output && $output[0] === $egrep ) {
					unset( $output[0] );
				}

				$trim_len = strlen( ABSPATH );
				$response = array_map(
					function ( $item ) use ( $trim_len ) {
						return substr( $item, $trim_len );
					},
					$output,
				);

				wp_send_json_success( [ 'result' => array_values( $response ) ] );
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
