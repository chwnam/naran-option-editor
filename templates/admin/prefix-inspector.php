<?php
/**
 * Prefix Inspector template
 *
 * @var string                $page
 * @var string                $autoload
 * @var string                $orderby
 * @var string                $delimiter
 * @var string                $core
 * @var int                   $min_count
 * @var array<string, string> $orders
 * @var array<string, string> $autoload_options
 * @var array<string, string> $delimiters
 * @var array<string, string> $core_options
 * @var array<stdClass>       $items
 * @var array<string, bool>   $prefix_filters
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Prefix Inspector</h1>

    <hr class="wp-header-end">

    <form id="prefix-inspector" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>">

        <div class="tablenav top">
            <div class="alignleft actions">
                <label for="delimiter" class="screen-reader-text">Delimiter</label>
                <select id="delimiter" name="delimiter">
					<?php foreach ( $delimiters as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $delimiter ); ?>>
							<?php echo esc_html( $label ); ?>
                        </option>
					<?php endforeach; ?>
                </select>

                <label for="autoload" class="screen-reader-text">Filter by autoload</label>
                <select id="autoload" name="autoload">
					<?php foreach ( $autoload_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $autoload ); ?>>
							<?php echo esc_html( $label ); ?>
                        </option>
					<?php endforeach; ?>
                </select>

                <label for="autoload" class="screen-reader-text">Filter by core options</label>
                <select id="core" name="core">
					<?php foreach ( $core_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $core ); ?>>
							<?php echo esc_html( $label ); ?>
                        </option>
					<?php endforeach; ?>
                </select>

                <label for="orderby" class="screen-reader-text">Ordering</label>
                <select id="orderby" name="orderby">
					<?php foreach ( $orders as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $orderby ); ?>>
							<?php echo esc_html( $label ); ?>
                        </option>
					<?php endforeach; ?>
                </select>
            </div>

            <div class="alignleft actions">
                <label for="min_count">Min. Count</label>:
                <input id="min_count"
                       name="min_count"
                       type="number"
                       value="<?php echo absint( $min_count ); ?>"
                       min="1">
                <input type="submit" class="button" value="Inspect">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num">
                    <?php printf( _n( '%d item', '%d items', count( $items ) ), count( $items ) ); ?></span>
            </div>
            <br class="clear">
        </div>

        <h2 class="screen-reader-text">Prefixes list</h2>
        <table class="wp-list-table widefat striped table-view-list"
               id="inspection-table">
            <thead>
            <tr>
                <th class="column-prefix">Prefix</th>
                <th class="column-option-count">Count</th>
                <th class="column-option-size">Size</th>
                <th class="column-action">Action</th>
            </tr>
            </thead>
            <tbody>
			<?php if ( ! empty( $items ) ) : ?>
				<?php foreach ( $items as $item ) : ?>
                    <tr>
                        <th class="column-prefix" scope="row">
							<?php echo esc_html( $item->prefix ?? '' ); ?>
                        </th>
                        <td class="column-option-count">
							<?php echo intval( $item->cnt ?? '0' ); ?>
                        </td>
                        <td class="column-option-size">
							<?php echo intval( $item->size ?? '0' ); ?>
                        </td>
                        <td class="column-action">
                            <div class="add <?php echo isset( $prefix_filters[ $item->prefix ] ) ? 'hidden' : ''; ?>">
                                <a href="#"
                                   data-prefix="<?php echo esc_attr( $item->prefix ?? '' ); ?>">
									<?php esc_html_e( 'Add to Filter List', 'noe' ); ?>
                                </a>
                                <span class="message"><?php
									esc_html_e( 'Removed from the prefixes list.', 'noe' ); ?></span>
                            </div>
                            <div class="remove <?php echo isset( $prefix_filters[ $item->prefix ] ) ? '' : 'hidden'; ?>">
                                <a href="#"
                                   class="submitdelete"
                                   data-prefix="<?php echo esc_attr( $item->prefix ?? '' ); ?>">
									<?php esc_html_e( 'Remove from Filter List', 'noe' ); ?>
                                </a>
                                <span class="message"><?php
									esc_html_e( 'Added to the prefixes list.', 'noe' ); ?></span>
                            </div>
                        </td>
                    </tr>
				<?php endforeach; ?>
			<?php else: ?>
                <tr>
                    <td colspan="4">No results.</td>
                </tr>
			<?php endif; ?>
            </tbody>
        </table>
    </form>

    <div class="clear"></div>
</div>
