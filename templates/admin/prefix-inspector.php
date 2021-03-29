<?php
/**
 * Prefix Inspector template
 *
 * @var string                $autoload
 * @var string                $orderby
 * @var string                $delimiter
 * @var int                   $min_count
 * @var array<string, string> $orders
 * @var array<string, string> $autoload_options
 * @var array<string, string> $delimiters
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
        <div class="tablenav top">
            <div class="alignleft actions">
                <label for="delimiter" class="screen-reader-text">Delimiter</label>
                <select id="delimiter" name="delimiter" autocomplete="off">
		            <?php foreach( $delimiters as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $autoload ); ?>>
				            <?php echo esc_html( $label ); ?>
                        </option>
		            <?php endforeach; ?>
                </select>

                <label for="autoload" class="screen-reader-text">Filter by autoload</label>
                <select id="autoload" name="autoload" autocomplete="off">
                    <?php foreach( $autoload_options as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $autoload ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="orderby" class="screen-reader-text">Ordering</label>
                <select id="orderby" name="orderby" autocomplete="off">
	                <?php foreach( $orders as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $autoload ); ?>>
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
                       value="1"
                       min="1">
                <input type="submit" class="button" value="Inspect">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num">10 items</span>
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
            <tr>
                <th class="column-prefix" scope="row">(공백)</th>
                <td class="column-option-count">8</td>
                <td class="column-option-size">1432</td>
                <td class="column-action"></td>
            </tr>
            <tr>
                <th class="column-prefix" scope="row">active_</th>
                <td class="column-option-count">1</td>
                <td class="column-option-size">1555</td>
                <td class="column-action">
                    <a href="#">필터 등록</a>
                    <span class="message show">등록되었습니다.</span>
                </td>
            </tr>
            <tr>
                <th class="column-prefix" scope="row">admin_</th>
                <td class="column-option-count">2</td>
                <td class="column-option-size">14</td>
                <td class="column-action">
                    <a href="#">필터 등록</a>
                    <span class="message">등록되었습니다.</span>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="clear"></div>
</div>
