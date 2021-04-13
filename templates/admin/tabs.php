<?php
/**
 * Admin Tabs Template: displaying tabs.
 *
 * @var string                $base_url Base URL.
 * @var array<string, string> $tabs     All tabs.
 * @var string                $current  Current tab.
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $tabs ) || empty( $current ) || 1 === count( $tabs ) ) {
	return;
}
?>

<nav class="nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e( 'Secondary menu' ); ?>">
	<?php
	foreach ( $tabs as $key => $label ) {
		printf(
			'<a href="%s" class="nav-tab%s"%s>%s</a>',
			esc_url( add_query_arg( 'tab', $key, $base_url ) ), // url
			( $current === $key ) ? ' nav-tab-active' : '', // class
			( $current === $key ) ? ' aria-current="page"' : '', // aria-current
			esc_html( $label ) // label
		);
	}
	?>
</nav>
