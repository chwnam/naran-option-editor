<?php
/**
 * @var string $which    'top', or 'bottom'.
 * @var string $autoload 'yes', or 'no'.
 * @var string $backup_options_url
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$which = esc_attr( $which );
?>

<?php if ( 'top' === $which ) : ?>
    <div class="alignleft actions">
        <input id="prefix-setup-<?= $which ?>"
               class="alignleft button action"
               type="button"
               value="Prefix...">

        <label class="screen-reader-text"
               for="filter-by-autoload">Filter by Autoload</label>
        <select id="filter-by-autoload"
                name="autoload">
            <option value="" <?php selected( $autoload, '' ); ?>>Filter by Autoload</option>
            <option value="yes" <?php selected( $autoload, 'yes' ); ?>>Autoload: Yes</option>
            <option value="no" <?php selected( $autoload, 'no' ); ?>>Autoload: No</option>
        </select>

		<?php
		submit_button(
			'Filter',
			'',
			'filter_action',
			false,
			[ 'id' => 'option-filter-submit' ]
		);
		?>
        <span class="horizontal-spacer"></span>

        <a id="backup-option-<?= $which ?>"
           href="<?php echo esc_url( $backup_options_url ); ?>"
           class="button action">Backup Options</a>
    </div>
<?php endif; ?>
