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

    #prefix-filter > li {
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

    #prefix-filter-dialog label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    #prefix-filter-dialog input {
        width: 100%;
    }

    #remove-all-filters {
        margin: 1.5em 0 0;
    }

    .horizontal-spacer {
        display: inline-block;
        width: 0.85em;
    }
</style>

<script>
    jQuery(function ($) {
        $('#prefix-filter-dialog').dialog({
            modal: true,
            buttons: {
                "추가": function () {
                    $(this).dialog('close');
                },
                "취소": function () {
                    $(this).dialog('close');
                },
            },
            autoOpen: false,
        });

        $('#prefix-setup-top').on('click', function () {
            $('#prefix-filter-dialog').dialog('open');
        });
    });
</script>

<div id="prefix-filter-dialog"
     title="접두어 필터"
     style="display: none;">
    <form>
        <fieldset>
            <label for="new-prefix">접두사</label>
            <input type="text" id="new-prefix" value="" name="new_prefix">
        </fieldset>
    </form>
    <p id="remove-all-filters">
        <a href="#">현재 모든 접두어 제거</a>
    </p>
</div>

<div class="wrap">
    <h1 class="wp-heading-inline">NOE</h1>
    <a href="<?php echo esc_url( add_query_arg( 'noe', 'single-new' ) ); ?>"
       class="page-title-action">새 옵션 추가</a>
    <hr class="wp-header-end">
    <h2 class="screen-reader-text">옵션 목록 필터하기</h2>
    <ul class="subsubsub">
        <li class="all">
            <a class="current"
               href="#"
               aria-current="page">모두 <span class="count">(255)</span>
            </a>
            |
        </li>
        <li class="core-item">
            <a href="#">코어 옵션 <span class="count">(55)</span>
            </a>
            |
        </li>
        <li class="custom-item">
            <a href="#">커스텀 옵션 <span class="count">(200)</span>
            </a>
        </li>
    </ul>

    <form id="options-filter" method="get">
        <p class="search-box">
            <label class="screen-reader-text" for="option-search-input">옵션 검색하기</label>
            <input id="option-search-input" type="search" name="s" value="">
            <input id="search-submit" class="button" type="submit" value="옵션 검색하기">
        </p>
        <!-- 히든 필드 -->

        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label class="screen-reader-text" for="bulk-action-selector-top">일괄 작업 선택하기</label>
                <select id="bulk-action-selector-top" name="action">
                    <option value="-1">일괄 동작</option>
                    <option class="hide-if-no-js" value="edit">편집하기</option>
                    <option value="trash">휴지통으로 이동하기</option>
                </select>
                <input id="doaction" class="button action" type="submit" value="적용하기">
            </div>
            <div class="alignleft actions">
                <input id="prefix-setup-top" class="alignleft button action" type="button" value="접두 필터...">
                <label class="screen-reader-text" for="filter-by-autoload">자동로드 필터</label>
                <select id="filter-by-autoload" name="autoload">
                    <option selected="selected" value="">모두</option>
                    <option value="yes">Yes 만</option>
                    <option value="no">No 만</option>
                </select>
                <input id="option-autoload-submit" class="button" type="submit" name="option_filter_submit" value="Filter">
                <span class="horizontal-spacer"></span>
                <input id="backup-option-1" class="button action" type="button" value="옵션 백업">
            </div>
            <div class="tablenav-pages"> <!-- one-page 추가하면 페이지네이션 출력되지 않음 -->
                <span class="displaying-num">10개의 항목</span>
                <span class="pagination-links">
                    <span aria-hidden="true" class="tablenav-pages-navspan button">&laquo;</span>
                    <span aria-hidden="true" class="tablenav-pages-navspan button">&lsaquo;</span>
                    <span class="paging-input">
                        <span class="total-pages">1</span> 중
                        <label for="current-page-selector" class="screen-reader-text">현재 페이지</label>
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
            <br class="clear">
        </div> <!-- .tablenav.top -->

        <ul id="prefix-filter">
            <li>
                <input type="checkbox"
                       id="noe-check-all-prefixes" value="yes">
                <label for="noe-check-all-prefixes">모두 선택</label>
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

        <h2 class="screen-reader-text">옵션 목록</h2>
        <table class="wp-list-table widefat fixed striped table-view-list options">
            <thead>
            <tr>
                <td id="cb"
                    class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">모두 선택하기</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th id="option-name"
                    class="manage-column column-option-name column-primary sortable desc" scope="col">
                    <a href="#">
                        <span>이름</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th id="option-value"
                    class="manage-column column-option-value" scope="col">
                    값
                </th>
                <th id="option-desc"
                    class="manage-column column-option-desc" scope="col">
                    설명
                </th>
                <th id="autoload"
                    class="manage-column column-autoload" scope="col">
                    자동로드
                </th>
                <th id="option-size"
                    class="manage-column column-option-size sortable desc" scope="col">
                    <a href="#">
                        <span>사이즈</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <tr id="option-1" class="iedit level-0 option-1 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-1">
                        {옵션 이름} 선택하기
                    </label>
                    <input id="cb-select-1" type="checkbox" name="option[]" value="1">
                </th>
                <td class="option-name column-option-name column-primary"
                    data-colname="이름">
                    <strong>
                        [#1]
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (편집하기)">
                            siteurl
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (편집하기)">편집하기</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} 삭제">삭제</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="값">
                    https://naran.dev.site
                </td>
                <td class="option-desc column-option-desc" data-colname="설명">
                    코어 옵션. 사이트 대표 URL.
                </td>
                <td class="autoload column-autoload" data-colname="자동로드">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="사이즈">
                    22
                </td>
            </tr>
            <tr id="option-2" class="iedit level-0 option-2 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-2">
                        {옵션 이름} 선택하기
                    </label>
                    <input id="cb-select-2" type="checkbox" name="option[]" value="2">
                </th>
                <td class="option-name column-option-name column-primary"
                    data-colname="이름">
                    <strong>
                        [#2]
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (편집하기)">
                            home
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (편집하기)">편집하기</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} 삭제">삭제</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="값">
                    https://naran.dev.site
                </td>
                <td class="option-desc column-option-desc" data-colname="설명">
                    코어 옵션. 홈 주소 URL.
                </td>
                <td class="autoload column-autoload" data-colname="자동로드">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="사이즈">
                    22
                </td>
            </tr>
            <tr id="option-3" class="iedit level-0 option-3 type-option hentry">
                <th class="check-column" scope="row">
                    <label class="screen-reader-text" for="cb-select-3">
                        {옵션 이름} 선택하기
                    </label>
                    <input id="cb-select-3" type="checkbox" name="option[]" value="3">
                </th>
                <td class="option-name column-option-name column-primary"
                    data-colname="이름">
                    <strong>
                        [#3]
                        <a class="row-title"
                           href="<?php echo esc_url( add_query_arg( 'noe', 'single' ) ); ?>"
                           aria-label="{option name} (편집하기)">
                            blogname
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#" aria-label="{option name} (편집하기)">편집하기</a>
                            |
                        </span>
                        <span class="trash">
                            <a href="#" class="submitdelete"
                               aria-label="{option name} 삭제">삭제</a>
                        </span>
                    </div>
                </td>
                <td class="option-value column-option-value" data-colname="값">
                    나란 개발 사이트
                </td>
                <td class="option-desc column-option-desc" data-colname="설명">
                    코어 옵션. 이 사이트의 제목
                </td>
                <td class="autoload column-autoload" data-colname="자동로드">
                    yes
                </td>
                <td class="option-size column-option-size" data-colname="사이즈">
                    23
                </td>
            </tr>
            </tbody>
        </table>

        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom"
                       class="screen-reader-text">일괄 작업 선택하기</label>
                <select name="action2" id="bulk-action-selector-bottom">
                    <option value="-1">일괄 동작</option>
                    <option value="edit" class="hide-if-no-js">편집하기</option>
                    <option value="trash">휴지통으로 이동하기</option>
                </select>
                <input type="submit" id="doaction2" class="button action" value="적용하기">
            </div>
            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page"> <!-- one-page 클래스는 페이지네이션 노출을 제어함 -->
                <span class="displaying-num">3개의 항목</span>
                <span class="pagination-links">
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&laquo;</span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">&lsaquo;</span>
                    <span class="screen-reader-text">현재 페이지</span>
                    <span id="table-paging" class="paging-input">
                        <span class="tablenav-paging-text">
                            <span class="total-pages">1</span> 중 1
                        </span>
                    </span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button" aria-hidden="true">»</span>
                </span>
            </div>
            <br class="clear">
        </div> <!-- .tablenav.bottom -->
    </form>
    <div id="ajax-response"></div>
    <div class="clear"></div>
</div>
