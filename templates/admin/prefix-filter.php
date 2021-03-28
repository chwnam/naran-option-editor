<?php
/**
 * Prefix filter area.
 *
 * @var array $pf
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul id="prefix-filter">
	<?php if ( ! empty( $pf ) ) : ?>
		<?php foreach ( $pf as $v ) : ?>
            <li>
                <input type="hidden"
                       name="pf[]"
                       value="<?php echo esc_attr( $v ); ?>">
				<?php echo esc_html( $v ); ?>
                <span class="remove">&times;</span>
            </li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>

<script type="text/template" id="tmpl-filter-item">
    <li>
        <input type="hidden"
               name="pf[]"
               value="{{ data.value }}">
        {{ data.value }}
        <span class="remove">&times;</span>
    </li>
</script>

<div id="prefix-filter-dialog"
     title="Prefix Filter"
     style="display: none;">
    <p>
        <label for="new-prefix">Prefix</label>
        <input type="text"
               id="new-prefix"
               value="">
    </p>
    <p id="remove-all-filters-wrap">
        <a id="remove-all-filters" href="#">현재 모든 접두어 제거</a>
    </p>
</div>