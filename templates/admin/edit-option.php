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
                    <p class="description">반드시 입력해야 합니다.</p>
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
                        옵션 길이: <span id="option-size"><?php
							echo intval( strlen( $option->option_value ?? '' ) ); ?></span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-desc">Description</label>
                </th>
                <td>
                <textarea id="option-desc"
                          name="option_desc"
                          class="large-text"
                          rows="3"
                          cols="40"
                          disabled="disabled"></textarea>
                    <!-- <p class="description">이 옵션에 대한 설명을 작성할 수 있습니다.</p> -->
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="autoload">Autoload</label>
                </th>
                <td>
                    <input type="checkbox"
                           id="autoload"
                           name="autoload"
                           value="yes"
						<?php checked( 'yes', $option->autoload ?? 'yes' ); ?>>
                    <label for="autoload">Autoload option.</label>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="Save Option">
            <a href="<?php echo esc_url( remove_query_arg( 'option_id' ) ); ?>"
               class="button button-secondary">Back to List</a>
			<?php if ( $option ) : ?>
                <a href="<?php echo esc_url(
					noe_get_option_remove_url(
						$option->option_id,
						remove_query_arg( 'option_id' )
					)
				); ?>"
                   class="submitdelete">Remove this option</a>
			<?php endif; ?>
        </p>
        <input type="hidden" name="option_id" value="<?php echo esc_attr( $option->option_id ?? 'new' ); ?>">
        <input type="hidden" name="action" value="noe_edit_option">
		<?php wp_nonce_field( 'noe_edit_option', '_noe_nonce' ); ?>
    </form>
</div>
