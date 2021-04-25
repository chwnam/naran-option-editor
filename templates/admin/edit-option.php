<?php
/**
 * @var stdClass|null $option null if add new.
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php
		if ( $option ) {
			printf(
			/* translators: option id and name */
				__( 'Edit Option #%1$d &#8220;%2$s&#8221;', 'noe' ),
				intval( $option->option_id ),
				esc_html( $option->option_name )
			);
		} else {
			_e( 'New Option', 'noe' );
		}
		?>
    </h1>

	<?php if ( $option ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'option_id', 'new' ) ); ?>"
           class="page-title-action"><?php esc_html_e( 'Add New', 'noe' ); ?></a>
	<?php endif; ?>

	<?php settings_errors( 'noe' ); ?>

    <hr class="wp-header-end">

    <form method="post"
          action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="option-name"><?php esc_html_e( 'Name', 'noe' ); ?></label>
                </th>
                <td>
                    <input type="text"
                           name="option_name"
                           class="text large-text"
                           id="option-name"
                           required="required"
                           data-option_name="<?php echo esc_attr( $option->option_name ?? '' ); ?>"
                           value="<?php echo esc_attr( $option->option_name ?? '' ); ?>">
                    <p class="description"><?php esc_html_e( 'Option name is required.', 'noe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-value"><?php esc_html_e( 'Value', 'noe' ); ?></label>
                </th>
                <td>
                <textarea id="option-value"
                          name="option_value"
                          class="large-text"
                          rows="3"
                          cols="40"
                          autocomplete="off"><?php echo esc_textarea( $option->option_value ?? '' ); ?></textarea>
                    <p class="description">
                        <a href="https://sciactive.com/phpserialeditor.php" target="_blank"><?php
							esc_html_e( 'Edit serialized value', 'noe' );
							?></a>
                        |
						<?php esc_html_e( 'Option size', 'noe' ); ?>: <span id="option-size"><?php
							echo intval( strlen( $option->option_value ?? '' ) ); ?></span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-desc"><?php esc_html_e( 'Description', 'noe' ); ?></label>
                </th>
                <td>
                <textarea id="option-desc"
                          name="description"
                          class="large-text"
                          rows="3"
                          cols="40"><?php echo esc_textarea( $option->description ?? '' ); ?></textarea>
                    <p class="description">
						<?php esc_html_e( 'Some description about this option.', 'noe' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="autoload"><?php esc_html_e( 'Autoload', 'noe' ); ?></label>
                </th>
                <td>
                    <input type="checkbox"
                           id="autoload"
                           name="autoload"
                           value="yes"
						<?php checked( 'yes', $option->autoload ?? 'yes' ); ?>>
                    <label for="autoload"><?php esc_html_e( 'Autoload this option.', 'noe' ); ?></label>
                </td>
            </tr>
			<?php if ( $option ) : ?>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Option Name Search', 'noe' ); ?></th>
                    <td>
                        <div id="search-span-setup"
                             class="search-option-name">
                            <h4><?php esc_html_e( 'Search Setup', 'noe' ); ?></h4>
                            <ul>
                                <li>
                                    <input type="checkbox"
                                           id="search-wp-core"
                                           class="search-span"
                                           value="wp-core"
                                           checked="checked">
                                    <label for="search-wp-core"><?php esc_html_e( 'WP Core', 'noe' ); ?></label>
                                </li>
                                <li>
                                    <input type="checkbox"
                                           id="search-themes"
                                           class="search-span"
                                           value="themes"
                                           checked="checked">
                                    <label for="search-themes"><?php esc_html_e( 'Themes', 'noe' ); ?></label>
                                </li>
                                <li>
                                    <input type="checkbox"
                                           id="search-plugins"
                                           class="search-span"
                                           value="plugins"
                                           checked="checked">
                                    <label for="search-plugins"><?php esc_html_e( 'Plugins', 'noe' ); ?></label>
                                </li>
                            </ul>
                            <button id="search-button" type="button" class="button">
								<?php esc_html_e( 'Search', 'noe' ); ?>
                            </button>
                        </div>
                        <div id="search-result"
                             class="search-option-name">
                            <h4><?php esc_html_e( 'Search Result', 'noe' ); ?>
                                <span id="search-total-num"></span></h4>
                            <pre id="search-result-code"><?php echo esc_textarea( __( 'Press \'Search\' button to search code.', 'noe' ) ); ?></pre>
                        </div>
                    </td>
                </tr>
			<?php endif; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Option', 'noe' ); ?>">
            <a href="<?php echo esc_url( remove_query_arg( 'option_id' ) ); ?>"
               class="button button-secondary"><?php esc_html_e( 'Back to List', 'noe' ); ?></a>
			<?php if ( $option ) : ?>
                <a href="<?php echo esc_url( noe_get_option_remove_url( $option->option_id, remove_query_arg( 'option_id' ) ) ); ?>"
                   class="submitdelete"><?php esc_html_e( 'Remove this option.', 'noe' ); ?></a>
			<?php endif; ?>
        </p>
        <input type="hidden" name="option_id" value="<?php echo esc_attr( $option->option_id ?? 'new' ); ?>">
        <input type="hidden" name="action" value="noe_edit_option">
		<?php wp_nonce_field( 'noe_edit_option', '_noe_nonce' ); ?>
    </form>
</div>
