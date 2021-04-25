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
    <div>
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
    </div>
</div>

<div id="bulk-edit-desc-dialog"
     class="edit-dialog"
     title="<?php esc_attr_e( 'Bulk Edit Description', 'noe' ); ?>"
     style="display: none;">
    <div>
        <fieldset>
            <label><?php _e( 'Option Name(s)', 'noe' ); ?></label>
            <ul id="bulk-edit-desc-option-names"></ul>
        </fieldset>
        <fieldset>
            <label for="bulk-edit-desc-textarea"><?php _e( 'Description', 'noe' ); ?></label>
            <textarea id="bulk-edit-desc-textarea"
                      name="bulk_edit_option_desc"
                      rows="8"></textarea>
        </fieldset>
    </div>
</div>

<script type="text/template" id="tmpl-bulk-edit-option-name">
    <# data.map(function (idx, elem) { #>
    <li>
        {{ elem.option_name }}
        <input type="hidden" name="bulk_edit_option_id[]" value="{{ elem.option_id}}">
    </li>
    <# }); #>
</script>