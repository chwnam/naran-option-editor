<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'NOE_MAIN' ) ) {
	exit;
}

?>

<style>
    #min_count {
        margin-right: 0.5em;
        width: 3.5em;
    }

    .tablenav {
        max-width: 1024px;
    }

    .tablenav.top .tablenav-pages {
        padding-top: 6px;
    }

    #inspection-table {
        max-width: 1024px;
    }

    #inspection-table .column-prefix {
        width: 200px;
    }

    #inspection-table .column-option-count {
        width: 80px;
    }

    #inspection-table .column-option-size {
        width: 120px;
    }

    #inspection-table .column-action {
        width: 624px;
    }

    #inspection-table .message {
        transition: 1s all;
        opacity: 0;
    }

    #inspection-table .message.show {
        display: inline-block;
        margin-left: 12px;
        opacity: 1;
    }

    #inspection-table .message.show:before {
        content: "["
    }

    #inspection-table .message.show:after {
        content: "]"
    }

    .submitdelete,
    .submitdelete:hover {
        color: #b32d2e;
    }

    .hidden {
        display: none;
    }

    .nav-tab-wrapper {
        margin-bottom: 10px;
    }

    @media screen and (max-width: 782px) {
        .tablenav-pages {
            margin: 0 0 5px !important;
            text-align: right !important;
        }

        .tablenav.top {
            margin-top: 0;
        }

        .tablenav.top .displaying-num {
            display: block !important;
            font-size: 14px;
        }

        .tablenav.top .actions {
            display: block !important;
            margin: 5px 0;
        }

        .tablenav.top .actions > * {
            margin-bottom: 5px;
        }
    }
</style>

<div class="wrap">
    <h1 class="wp-heading-inline">Prefix Inspector</h1>

    <hr class="wp-header-end">

    <nav class="nav-tab-wrapper wp-clearfix" aria-label="2차 메뉴">
        <a class="nav-tab"
           href="<?php echo esc_url( add_query_arg( 'tab', 'option-editor' ) ); ?>">옵션 편집기</a>
        <a class="nav-tab nav-tab-active"
           href="<?php echo esc_url( add_query_arg( 'tab', 'prefix-inspector' ) ); ?>"
           aria-current="page">접두어 분석기</a>
    </nav>

    <form id="prefix-inspector" method="get">
        <div class="tablenav top">
            <div class="alignleft actions">
                <label for="delimiter" class="screen-reader-text">구분자 선택</label>
                <select id="delimiter" name="delimiter" autocomplete="off">
                    <option value="_" selected>구분자: _ (언더스코어)</option>
                    <option value="-">구분자: - (히이픈)</option>
                </select>

                <label for="autoload" class="screen-reader-text">Autoload 포함</label>
                <select id="autoload" name="autoload" autocomplete="off">
                    <option value="yes" selected>Autoload: yes</option>
                    <option value="no">Autoload: no</option>
                    <option value="all">Autoload: all</option>
                </select>

                <label for="core" class="screen-reader-text">코어 옵션 포함</label>
                <select id="core" name="core" autocomplete="off">
                    <option value="include" selected>코어 옵션 포함</option>
                    <option value="exclude">코어 옵션 제외</option>
                </select>

                <label for="orderby" class="screen-reader-text">정렬 방법</label>
                <select id="orderby" name="orderby" autocomplete="off">
                    <option value="prefix_asc">접두어 올림차순</option>
                    <option value="prefix_desc">접두어 내림차순</option>
                    <option value="count_asc">옵션 수 올림차순</option>
                    <option value="count_desc" selected>옵션 수 내림차순</option>
                    <option value="size_asc">크기 올림차순</option>
                    <option value="size_desc">크기 내림차순</option>
                </select>
            </div>

            <div class="alignleft actions">
                <label for="min_count">최소 옵션 수</label>:
                <input id="min_count"
                       name="min_count"
                       type="number"
                       value="5"
                       min="1">
                <input type="submit" class="button" value="다시 조사">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num">10개의 항목</span>
            </div>
            <br class="clear">
        </div>

        <h2 class="screen-reader-text">접두사 목록</h2>
        <table class="wp-list-table widefat striped table-view-list"
               id="inspection-table">
            <thead>
            <tr>
                <th class="column-prefix">접두어</th>
                <th class="column-option-count">옵션 수</th>
                <th class="column-option-size">크기</th>
                <th class="column-action">동작</th>
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
                    <div class="add">
                        <a href="#">필터 등록</a>
                        <span class="message show">등록되었습니다.</span>
                    </div>
                    <div class="remove hidden">
                        <a href="#" class="submitdelete">필터 해제</a>
                        <span class="message show">해제되었습니다.</span>
                    </div>
                </td>
            </tr>
            <tr>
                <th class="column-prefix" scope="row">admin_</th>
                <td class="column-option-count">2</td>
                <td class="column-option-size">14</td>
                <td class="column-action">
                    <div class="add hidden">
                        <a href="#">필터 등록</a>
                        <span class="message show">등록되었습니다.</span>
                    </div>
                    <div class="remove">
                        <a href="#" class="submitdelete">필터 해제</a>
                        <span class="message show">해제되었습니다.</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="clear"></div>
</div>
