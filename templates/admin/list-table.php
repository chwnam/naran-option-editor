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
    <h1 class="wp-heading-inline">
        Options List
    </h1>

    <a href="<?php echo esc_url( add_query_arg( 'option_id', 'new' ) ); ?>"
       class="page-title-action">Add New</a>

	<?php if ( isset( $_GET['s'] ) && strlen( $_GET['s'] ) ) : ?>
        <span class="subtitle">
            Search results for: <strong><?php echo esc_attr( $_GET['s'] ); ?></strong>
        </span>
	<?php endif; ?>

    <hr class="wp-header-end">

	<?php $table->views(); ?>

    <form id="options-filter" method="get">
		<?php $table->search_box( '옵션 검색하기', 'option' ); ?>
        <input type="hidden" name="page" value="noe">
        <input type="hidden" name="o" value="<?php echo esc_attr( $o ); ?>">

		<?php $table->display(); ?>
    </form>
    <div id="ajax-response"></div>
    <div class="clear"></div>
</div>
