<?php
/**
 * Prefix filter area.
 *
 * @var array $prefix_filters
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$all_checked = array_reduce(
	$prefix_filters,
	function ( $accum, $value ) { return $accum && $value; },
	true
);

?>

<ul id="prefix-filter">
    <li id="noe-toggle-all-prefixes-wrap"
        style="<?php echo count( $prefix_filters ) > 1 ? '' : 'display:none;' ?>">
        <input type="checkbox" id="noe-toggle-all-prefixes" <?php checked( $all_checked ); ?>>
        <label for="noe-toggle-all-prefixes">Toggle All</label>
    </li>
	<?php foreach ( $prefix_filters as $filter => $checked ) : ?>
        <li>
            <input type="checkbox"
                   id="noe-prefix-<?php echo esc_attr( $filter ); ?>"
                   name="pf[]"
                   value="<?php echo esc_attr( $filter ); ?>" <?php checked( $checked ); ?>>
            <label for="noe-prefix-<?php echo esc_attr( $filter ); ?>">
				<?php echo esc_html( $filter ); ?>
            </label>
            <span class="remove">&times;</span>
        </li>
	<?php endforeach; ?>
</ul>

<script type="text/template" id="tmpl-filter-item">
    <li>
        <input type="checkbox"
               id="noe-prefix-{{ data.prefix }}"
               name="pf[]"
               value="{{ data.prefix }}" checked="checked">
        <label for="noe-prefix-{{ data.prefix }}">{{ data.prefix }}</label>
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
        <a id="remove-all-filters" href="#"><?php esc_html_e( 'Remove all prefixes', 'noe' ); ?></a>
    </p>
</div>
