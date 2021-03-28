<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'NOE_MAIN' ) ) {
	exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">NOE 새 옵션</h1>
    <hr class="wp-header-end">
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
                           value="">
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
                          cols="40"></textarea>
                    <p class="description">
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
                          cols="40"></textarea>
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
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="새 옵션 저장">
            <a href="<?php echo esc_url( remove_query_arg( 'noe' ) ); ?>"
               class="button button-secondary">목록으로</a>
        </p>
    </form>
</div>