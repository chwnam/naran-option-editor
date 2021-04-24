<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'NOE_MAIN' ) ) {
	exit;
}

?>
<style>
    .submitdelete,
    .submitdelete:hover {
        color: #b32d2e;
        margin-left: 2em;
    }

    .search-option-name:not(.search-option-name:last-of-type) {
        margin-bottom: 20px;
    }

    .search-option-name > h4 {
        margin-top: 0;
        margin-bottom: 6px;
    }

    #search-span-setup > ul {
        margin: 3px;
    }

    #search-span-setup > ul > li {
        float: left;
        padding: 4px 0;
        margin-right: 10px;
    }

    #search-span-setup:after {
        clear: both;
    }

    #search-total-num:before {
        content: ': ';
    }

    #search-total-num {
        font-weight: normal;
    }

    #search-result > pre {
        border: 1px solid #8a8a8a;
        border-radius: 4px;
        padding: 5px 8px;
        overflow: auto;
        max-height: 100px
    }

    @media screen and (max-width: 782px) {
        .search-option-name > h4 {
            margin-top: 8px;
            font-weight: normal;
        }

        #search-span-setup > ul > li {
            padding: 10px 0;
        }
    }
</style>

<div class="wrap">
    <h1 class="wp-heading-inline">NOE 옵션 수정</h1>
    <a href="<?php echo esc_url( add_query_arg( 'noe', 'single-new' ) ); ?>"
       class="page-title-action">새 옵션 추가</a>
    <hr class="wp-header-end">
    <div class="notice notice-success"><p>성공</p></div>
    <form method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="option-name">이름</label>
                </th>
                <td>
                    <input type="text"
                           name="option_name"
                           class="text large-text"
                           id="option-name"
                           value="siteurl">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-value">값</label>
                </th>
                <td>
                <textarea id="option-value"
                          name="option_value"
                          class="large-text"
                          rows="3"
                          cols="40">https://naran.dev.site</textarea>
                    <p class="description">
                        <a href="https://sciactive.com/phpserialeditor.php" target="_blank">직렬화된 값 편집</a>
                        |
                        옵션 길이: <span id="option-size">22</span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="option-desc">설명</label>
                </th>
                <td>
                <textarea id="option-desc"
                          name="option_desc"
                          class="large-text"
                          rows="3"
                          cols="40">코어 옵션. 사이트 대표 URL.</textarea>
                    <p class="description">
                        이 옵션에 대한 설명을 작성할 수 있습니다.
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="autoload">자동 로드</label>
                </th>
                <td>
                    <input type="checkbox"
                           id="autoload"
                           name="autoload"
                           checked="checked">
                    <label for="autoload">자동 로드되는 옵션입니다.</label>
                </td>
            </tr>
            <tr>
                <th scope="row">옵션 이름 검색</th>
                <td>
                    <div id="search-span-setup"
                         class="search-option-name">
                        <h4>검색 범위 지정</h4>
                        <ul>
                            <li>
                                <input type="checkbox" id="search-wp-core" checked="checked">
                                <label for="search-wp-core">WP 코어</label>
                            </li>
                            <li>
                                <input type="checkbox" id="search-themes" checked="checked">
                                <label for="search-themes">테마</label>
                            </li>
                            <li>
                                <input type="checkbox" id="search-plugins" checked="checked">
                                <label for="search-plugins">플러그인</label>
                            </li>
                        </ul>
                        <button type="button" class="button">검색</button>
                    </div>
                    <div id="search-result"
                         class="search-option-name">
                        <h4>검색 결과<span id="search-total-num">8건</span></h4>
                        <pre>wp-login.php:409:	if ( get_option( 'siteurl' ) !== $url ) {
wp-login.php:410:		update_option( 'siteurl', $url );
1
2
3
4
5
7
8
9
10
11
12
13
14
15
16
17
18
19
20</pre>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="옵션 저장">
            <a href="<?php echo esc_url( remove_query_arg( 'noe' ) ); ?>"
               class="button button-secondary">목록으로</a>
            <a href="#"
               class="submitdelete">이 옵션 삭제</a>
        </p>
    </form>
</div>
