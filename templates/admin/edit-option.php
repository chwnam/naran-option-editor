<?php
/**
 * @var stdClass|null $option 새로 입력시 null.
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
		<?php if ( $option ) : ?>
            Edit Option #<?php echo intval( $option->option_id ); ?>
            &#8220;<?php echo esc_html( $option->option_name ); ?>&#8221;
		<?php else : ?>
            New Option
		<?php endif; ?>
    </h1>

	<?php if ( $option ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'option_id', 'new' ) ); ?>"
           class="page-title-action">Add New</a>
	<?php endif; ?>

	<?php settings_errors( 'noe' ); ?>

    <hr class="wp-header-end">

    <form method="post"
          action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="option-name">Name</label>
                </th>
                <td>
                    <input type="text"
                           name="option_name"
                           class="text large-text"
                           id="option-name"
                           required="required"
                           value="<?php echo esc_attr( $option->option_name ?? '' ); ?>">
                    <p class="description"><?php esc_html_e( 'Option name is required.', 'noe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-value">Value</label>
                </th>
                <td>
                <textarea id="option-value"
                          name="option_value"
                          class="large-text"
                          rows="3"
                          cols="40"
                          autocomplete="off"><?php echo esc_textarea( $option->option_value ?? '' ); ?></textarea>
                    <p class="description">
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
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Option', 'noe' ); ?>">
            <a href="<?php echo esc_url( remove_query_arg( 'option_id' ) ); ?>"
               class="button button-secondary"><?php esc_html_e( 'Back to List', 'noe' ); ?></a>
			<?php if ( $option ) : ?>
                <a href="<?php echo esc_url(
					noe_get_option_remove_url(
						$option->option_id,
						remove_query_arg( 'option_id' )
					)
				); ?>"
                   class="submitdelete"><?php esc_html_e( 'Remove this option.', 'noe' ); ?></a>
			<?php endif; ?>
        </p>
        <input type="hidden" name="option_id" value="<?php echo esc_attr( $option->option_id ?? 'new' ); ?>">
        <input type="hidden" name="action" value="noe_edit_option">
		<?php wp_nonce_field( 'noe_edit_option', '_noe_nonce' ); ?>
    </form>
</div>
