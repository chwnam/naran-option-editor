<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'NOE_MAIN' ) ) {
	exit;
}

wp_enqueue_script( 'jquery-ui-dialog' );
wp_enqueue_style( 'wp-jquery-ui-dialog' );
?>

<style>
    #prefix-setup-top {
        margin-right: 6px;
    }

    #prefix-filter > li,
    #bulk-desc-edit-dialog li {
        float: left;
        padding: 3px 6px;
        margin: 3px 4px;
        background-color: #cecece;
        border-radius: 4px;
        color: #333;
    }

    #prefix-filter .remove {
        cursor: pointer;
        background-color: #ececec;
        padding: 0 4px;
        border-radius: 8px;
        margin-left: 4px;
        margin-right: 0;
    }

    #prefix-filter:after {
        content: '';
        display: block;
        clear: both;
    }

    .edit-dialog fieldset {
        margin-bottom: 15px;
    }

    .edit-dialog label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .edit-dialog input,
    .edit-dialog textarea {
        width: 100%;
    }

    #remove-all-filters {
        margin: 1.5em 0 0;
    }

    .horizontal-spacer {
        display: inline-block;
        width: 0.85em;
    }

    .column-option-id {
        width: 5em;
    }

    .column-autoload {
        width: 5em;
    }

    .column-option-size {
        width: 9em;
    }

    .nav-tab-wrapper {
        margin-bottom: 10px;
    }

    .subsubsub {
        margin-top: 2px;
    }

    .edit-description {
        display: none;
    }

    .column-option-desc:hover .edit-description {
        display: inline;
    }

    @media screen and (max-width: 782px) {
        .wp-list-table .column-option_id {
            display: none;
        }

        .wp-list-table .is-expanded .column-option_id {
            padding: 3px 8px 3px 35% !important;
        }

        .tablenav.top .actions.prefix-filter-setup {
            display: block;
            margin-bottom: 14px;
        }

        .tablenav.top .displaying-num {
            display: block !important;
            margin: 5px 0 20px;
            font-size: 16px;
        }
    }

    @media screen and (max-width: 488px) {
        .prefix-filter-setup .horizontal-spacer {
            display: block;
        }
    }
</style>

<script>
    jQuery(function ($) {
        // Prefix filter dialog.
        $('#prefix-filter-dialog').dialog({
            modal: true,
            autoOpen: false,
            draggable: false,
            resizable: false,
            buttons: {
                "??????": function () {
                    $(this).dialog('close');
                },
                "??????": function () {
                    $(this).dialog('close');
                },
            },
        });

        $('#prefix-setup-top').on('click', function () {
            $('#prefix-filter-dialog').dialog('open');
        });

        // Description edit dialog.
        $('#desc-edit-dialog').dialog({
            modal: true,
            autoOpen: false,
            draggable: false,
            resizable: false,
            buttons: {
                "??????": function () {
                    $(this).dialog('close');
                },
                "??????": function () {
                    $(this).dialog('close');
                },
            }
        });

        $('.edit-description').on('click', function (e) {
            e.preventDefault();
            $('#desc-edit-dialog').dialog('open');
        });

        // Bulk description edit dialog.
        $('#bulk-desc-edit-dialog').dialog({
            modal: true,
            autoOpen: false,
            draggable: false,
            resizable: false,
            buttons: {
                "??????": function () {
                    $(this).dialog('close');
                },
                "??????": function () {
                    $(this).dialog('close');
                },
            }
        });

        $('#mockup-only-open-bulk-edit-desc').on('click', function () {
            $('#bulk-desc-edit-dialog').dialog('open');
        });

        // Option backup / restore
        $('#restore-option-1').on('click', function () {
            $('#sql-file-upload').trigger('click');
        });

        $('#sql-file-upload').on('change', function (e) {
            alert('The file is uploaded.');
            e.currentTarget.value = '';
        });


    });
</script>

<!-- Prefix filter dialog -->
<div id="prefix-filter-dialog"
     class="edit-dialog"
     title="????????? ??????"
     style="display: none;">
    <form>
        <fieldset>
            <label for="new-prefix">?????????</label>
            <input type="text" id="new-prefix" value="" name="new_prefix">
        </fieldset>
    </form>
    <p id="remove-all-filters">
        <a href="#">?????? ?????? ????????? ??????</a>
    </p>
</div>

<!-- Desc edit dialog -->
<div id="desc-edit-dialog"
     class="edit-dialog"
     title="Edit Description"
     style="display:none;">
    <form>
        <fieldset>
            <label for="edit-desc-option-name">Option Name</label>
            <span>blogname</span>
        </fieldset>
        <fieldset>
            <label for="edit-desc">Description</label>
            <textarea id="edit-desc" name="edit_desc" rows="8">?????? ??????. ??? ???????????? ??????</textarea>
        </fieldset>
    </form>
</div>

<!-- Bulk desc edit dialog -->
<div id="bulk-desc-edit-dialog"
     class="edit-dialog"
     title="Descriptions Bulk Edit"
     style="display: none;">
    <form>
        <fieldset>
            <label for="bulk-edit-desc-option-names">Option Name(s)</label>
            <ul>
                <li>siteurl</li>
                <li>home</li>
                <li>blog_name</li>
            </ul>
        </fieldset>
        <fieldset>
            <label for="bulk-edit-desc">Description</label>
            <textarea id="bulk-edit-desc" name="bulk_edit_desc" rows="8">?????? ?????? ?????? ??????</textarea>
        </fieldset>
    </form>
</div>

<div class="wrap">
    <h1 class="wp-heading-inline">NOE</h1>
    <a href="<?php echo esc_url( add_query_arg( 'noe', 'single-new' ) ); ?>"
       class="page-title-action">??? ?????? ??????</a>
    <hr class="wp-header-end">

    <nav class="nav-tab-wrapper wp-clearfix" aria-label="2??? ??????">
        <a class="nav-tab nav-tab-active"
           href="<?php echo esc_url( add_query_arg( 'tab', 'option-editor' ) ); ?>"
           aria-current="page">?????? ?????????</a>
        <a class="nav-tab"
           href="<?php echo esc_url( add_query_arg( 'tab', 'prefix-inspector' ) ); ?>">????????? ?????????</a>
    </nav>

    <h2 class="screen-reader-text">?????? ?????? ????????????</h2>
    <ul class="subsubsub">
        <li class="all">
            <a class="current"
               href="#"
               aria-current="page">?????? <span class="count">(255)</span>
            </a>
            |
        </li>
        <li class="core-item">
            <a href="#">?????? ?????? <span class="count">(55)</span>
            </a>
            |
        </li>
        <li class="custom-item">
            <a href="#">????????? ?????? <span class="count">(200)</span>
            </a>
        </li>
    </ul>

    <form id="options-filter" method="get">
        <p class="search-box">
            <label class="screen-reader-text" for="option-search-input">?????? ????????????</label>
            <input id="option-search-input" type="search" name="s" value="">
            <input id="search-submit" class="button" type="submit" value="?????? ????????????">
        </p>
        <!-- ?????? ?????? -->

        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-top">?????? ?????? ????????????</label>
                <select id="bulk-action-selector-top" name="action">
                    <option value="-1">?????? ??????</option>
                    <option class="hide-if-no-js" value="edit">????????????</option>
                    <option value="trash">??????????????? ????????????</option>
                </select>
                <input id="doaction" class="button action" type="submit" value="????????????">
            </div>
            <div class="alignleft actions prefix-filter-setup">
                <input id="prefix-setup-top" class="alignleft button action" type="button" value="?????? ??????...">
                <label class="screen-reader-text" for="filter-by-autoload">???????????? ??????</label>
                <select id="filter-by-autoload" name="autoload">
                    <option selected="selected" value="">??????</option>
                    <option value="yes">Yes ???</option>
                    <option value="no">No ???</option>
                </select>
                <input id="option-autoload-submit" class="button" type="submit" name="option_filter_submit"
                       value="Filter">
                <span class="horizontal-spacer"></span>
                <input id="backup-option-1" class="button action" type="button" value="?????? ??????">
                <input id="restore-option-1" class="button action" type="button" value="?????? ??????">
                <input id="sql-file-upload" type="file" accept=".sql.gz,.sql" style="display: none;">
            </div>
            <div class="tablenav-pages"> <!-- one-page ???????????? ?????????????????? ???????????? ?????? -->
                <span class="displaying-num" title="??? ??????: 81234580">??? ?????? 8.2MB, 10?????? ??????</span>
                <span class="pagination-links">
                    <span aria-hidden="true" class="tablenav-pages-navspan button">&laquo;</span>
                    <span aria-hidden="true" class="tablenav-pages-navspan button">&lsaquo;</span>
                    <span class="paging-input">
                        <span class="total-pages">1</span> ???
                        <label for="current-page-selector" class="screen-reader-text">?????? ?????????</label>
                        <input class="current-page"
                               id="current-page-selector"
                               type="text"
                               name="paged"
                               value="1"
                               size="1"
                               aria-describedby="table-paging">
                        <span class="tablenav-paging-text"></span>
                    </span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&rsaquo;</span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&raquo;</span>
                </span>
            </div> <!-- .tablenav-pages -->
            <div class="alignright total-option-size">

            </div>
            <br class="clear">
        </div> <!-- .tablenav.top -->

        <ul id="prefix-filter">
            <li>
                <input type="checkbox"
                       id="noe-check-all-prefixes" value="yes">
                <label for="noe-check-all-prefixes">?????? ??????</label>
            </li>
            <li>
                <input type="checkbox"
                       id="noe-prefix-foo_" value="yes" checked>
                <label for="noe-prefix-foo_">foo_</label>
                <span class="remove">&times;</span>
            </li>
            <li>
                <input type="checkbox"
                       id="noe-prefix-bar_" value="yes" checked>
                <label for="noe-prefix-bar_">bar_</label>
                <span class="remove">&times;</span>
            </li>
        </ul>

        <h2 class="screen-reader-text">?????? ??????</h2>
        <table class="wp-list-table widefat fixed striped table-view-list options">
            <thead>
            <tr>
                <td id="cb"
                    class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">?????? ????????????</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th id="option-id"
                    class="manage-column column-option-id sortable asc" scope="col">
                    <a href="#">
                        <span>ID</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th id="option-name"
                    class="manage-column column-option-name column-primary sortable desc" scope="col">
                    <a href="#">
                        <span>??????</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th id="option-value"
                    class="manage-column column-option-value" scope="col">
                    ???
                </th>
                <th id="option-desc"
                    class="manage-column column-option-desc" scope="col">
                    ??????
                </th>
                <th id="autoload"
                    class="manage-column column-autoload" scope="col">
                    ????????????

                </th>
                <th id="option-size"
                    class="manage-column column-option-size sortable desc" scope="col">
                    <a href="#">
                        <span>?????????</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <tr id="option-1" class="iedit level-0 option-1 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-1">
                        {?????? ??????} ????????????
                    </label>
                    <input id="cb-select-1" type="checkbox" name="option[]" value="1">
                </th>
                <td class="option-id column-option-id"
                    data-colname="ID">
                    1
                </td>
                <td class="option-name column-option-name column-primary"
                    data-colname="??????">
                    <strong>
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (????????????)">
                            siteurl
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (????????????)">????????????</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} ??????">??????</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="???">
                    https://naran.dev.site
                </td>
                <td class="option-desc column-option-desc" data-colname="??????">
                    ?????? ??????. ????????? ?????? URL.
                    <a href="#" class="edit-description">[??????...]</a>
                </td>
                <td class="autoload column-autoload" data-colname="????????????">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="?????????">
                    22
                </td>
            </tr>
            <tr id="option-2" class="iedit level-0 option-2 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-2">
                        {?????? ??????} ????????????
                    </label>
                    <input id="cb-select-2" type="checkbox" name="option[]" value="2">
                </th>
                <td class="option-id column-option-id"
                    data-colname="ID">
                    2
                </td>
                <td class="option-name column-option-name column-primary"
                    data-colname="??????">
                    <strong>
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (????????????)">
                            home
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (????????????)">????????????</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} ??????">??????</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="???">
                    https://naran.dev.site
                </td>
                <td class="option-desc column-option-desc" data-colname="??????">
                    ?????? ??????. ??? ?????? URL.
                    <a href="#" class="edit-description">[??????...]</a>
                </td>
                <td class="autoload column-autoload" data-colname="????????????">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="?????????">
                    22
                </td>
            </tr>
            <tr id="option-3" class="iedit level-0 option-3 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-3">
                        {?????? ??????} ????????????
                    </label>
                    <input id="cb-select-3" type="checkbox" name="option[]" value="3">
                </th>
                <td class="option-id column-option-id"
                    data-colname="ID">
                    3
                </td>
                <td class="option-name column-option-name column-primary"
                    data-colname="??????">
                    <strong>
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (????????????)">
                            blogname
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (????????????)">????????????</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} ??????">??????</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="???">
                    ?????? ?????? ?????????
                </td>
                <td class="option-desc column-option-desc" data-colname="??????">
                    ?????? ??????. ??? ???????????? ??????
                    <a href="#" class="edit-description">[??????...]</a>
                </td>
                <td class="autoload column-autoload" data-colname="????????????">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="?????????">
                    23
                </td>
            </tr>
            </tbody>
        </table>

        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom"
                       class="screen-reader-text">?????? ?????? ????????????</label>
                <select name="action2" id="bulk-action-selector-bottom">
                    <option value="-1">?????? ??????</option>
                    <option value="edit" class="hide-if-no-js">????????????</option>
                    <option value="trash">??????????????? ????????????</option>
                </select>
                <input type="submit" id="doaction2" class="button action" value="????????????">
            </div>
            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page"> <!-- one-page ???????????? ?????????????????? ????????? ????????? -->
                <span class="displaying-num">3?????? ??????</span>
                <span class="pagination-links">
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&laquo;</span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&lsaquo;</span>
                    <span class="screen-reader-text">?????? ?????????</span>
                    <span id="table-paging" class="paging-input">
                        <span class="tablenav-paging-text">
                            <span class="total-pages">1</span> ??? 1
                        </span>
                    </span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">???</span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">??</span>
                </span>
            </div>
            <br class="clear">
        </div> <!-- .tablenav.bottom -->
    </form>
    <div id="ajax-response"></div>
    <div class="clear"></div>
</div>

<button id="mockup-only-open-bulk-edit-desc" class="button">(?????? ??????) ?????? ?????? ?????? ??????...</button>