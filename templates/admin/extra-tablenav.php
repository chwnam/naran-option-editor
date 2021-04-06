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
               value="<?php esc_attr_e( 'Prefix...', 'noe' ); ?>">

        <label class="screen-reader-text"
               for="filter-by-autoload"><?php esc_html_e( 'Filter by Autoload', 'noe' ); ?></label>
        <select id="filter-by-autoload"
                name="autoload">
            <option value="" <?php selected( $autoload, '' ); ?>>
				<?php esc_html_e( 'Filter by Autoload', 'noe' ); ?>
            </option>
            <option value="yes" <?php selected( $autoload, 'yes' ); ?>>
				<?php esc_html_e( 'Autoload: Yes', 'noe' ); ?>
            </option>
            <option value="no" <?php selected( $autoload, 'no' ); ?>>
				<?php esc_html_e( 'Autoload: No', 'noe' ); ?>
            </option>
        </select>

		<?php
		submit_button(
			__( 'Filter', 'noe' ),
			'',
			'filter_action',
			false,
			[ 'id' => 'option-filter-submit' ]
		);
		?>
        <span class="horizontal-spacer"></span>

        <a id="backup-option-<?= $which ?>"
           href="<?php echo esc_url( $backup_options_url ); ?>"
           class="button action"><?php esc_html_e( 'Backup Options', 'noe' ); ?></a>
    </div>
<?php endif; ?>
