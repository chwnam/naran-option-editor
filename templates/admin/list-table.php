<?php
/**
 *
 * @var NOE_Options_List_Table $table
 * @var string                 $o 'core', 'custom'
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'administrator' ) ) {
	wp_die( 'Sorry, you are not authorized to access this page.' );
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Options List', 'noe' ); ?></h1>

    <a href="<?php echo esc_url( add_query_arg( 'option_id', 'new' ) ); ?>"
       class="page-title-action"><?php esc_html_e( 'Add New', 'noe' ); ?></a>

	<?php if ( isset( $_GET['s'] ) && strlen( $_GET['s'] ) ) : ?>
        <span class="subtitle">
            <?php
            printf(
            /* translators: search keyword */
	            __( 'Search results for: <strong>%s</strong>', 'noe' ),
	            esc_html( $_GET['s'] )
            );
            ?>
        </span>
	<?php endif; ?>

    <hr class="wp-header-end">

    <?php noe()->admin->menu->output_tabs(); ?>

	<?php $table->views(); ?>

    <form id="options-filter" method="get">
		<?php $table->search_box( __( 'Search', 'noe' ), 'option' ); ?>
        <input type="hidden" name="page" value="noe">
        <input type="hidden" name="o" value="<?php echo esc_attr( $o ); ?>">
	    <?php noe()->admin->menu->output_hidden_tab_values(); ?>

		<?php $table->display(); ?>
    </form>
    <div id="ajax-response"></div>
    <div class="clear"></div>
</div>
