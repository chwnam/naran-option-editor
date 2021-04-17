<?php
/**
 * Description edit dialog.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="edit-desc-dialog"
     class="edit-dialog"
     title="<?php esc_attr_e( 'Edit Description', 'noe' ); ?>"
     style="display:none;">
    <form>
        <input type="hidden"
               id="edit-desc-option_id"
               value="">
        <fieldset>
            <label><?php _e( 'Option Name', 'noe' ); ?></label>
            <span id="edit-desc-option_name"></span>
        </fieldset>
        <fieldset>
            <label><?php _e( 'Option Value', 'noe' ); ?></label>
            <span id="edit-desc-option_value"></span>
        </fieldset>
        <fieldset>
            <label for="edit-desc-textarea"><?php _e( 'Description', 'noe' ); ?></label>
            <textarea id="edit-desc-textarea"
                      name="edit_option_desc"
                      rows="8"></textarea>
        </fieldset>
    </form>
</div>
