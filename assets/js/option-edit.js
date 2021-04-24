(function ($) {
    var size = $('#option-size'),
        value = $('#option-value');

    function getByteLength(s, b, i, c) {
        for (b = i = 0; c = s.charCodeAt(i++); b += c >> 11 ? 3 : c >> 7 ? 2 : 1) ;
        return b;
    }

    value.on('keyup', function () {
        size.text(getByteLength(value.val()));
    });

    $('.submitdelete').on('click', function (e) {
        if (!confirm('Are you sure you want to remove this option?')) {
            e.preventDefault();
            return false;
        }
    });
})(jQuery);

/* Code searching */
(function ($) {
    var button = $('#search-button'),
        span = $('.search-span'),
        totalNum = $('#search-total-num'),
        resultCode = $('#search-result-code'),
        optionName = $('#option-name').data('option_name'),
        nonce = $('input#_noe_nonce').val();

    if (!optionName.length) {
        console.error('The option name is blank. Code searching is disabled.');
        return;
    }

    button.on('click', function () {
        var searchSpan = span
                .filter(':checked')
                .map(function (idx, elem) {
                    return elem.value;
                })
                .toArray(),
            buttonTextBackup;

        if (!searchSpan.length) {
            alert('Please check at least one code search span.')
            return;
        }

        wp.ajax.send('noe_option_name_search', {
            method: 'get',
            data: {
                nonce: nonce,
                option_name: optionName,
                span: searchSpan,
            },
            beforeSend: function () {
                buttonTextBackup = button.text();
                button.text('Searching...');
                button.prop('disabled', 1);
            },
            success: function (data) {
                var result = data.result || [];
                totalNum.text(result.length);
                resultCode.text(result.join('\n'));
            },
            error: function (data) {
                if ($.isPlainObject(data)) {
                    alert(data.status + ' ' + data.statusText + ': ' + data.responseText);
                } else if ($.isArray(data)) {
                    var buffer = $.map(data, function (elem) {
                        var code = elem.code || null, message = elem.message || null;
                        if (code && message) {
                            return '- [' + code + '] ' + message
                        } else {
                            return '- ' + elem.toString();
                        }
                    });
                    alert(buffer.join('\n'));
                } else {
                    console.error(data);
                }
            },
            complete: function () {
                button.text(buttonTextBackup);
                buttonTextBackup = '';
                button.prop('disabled', 0);
            }
        });
    });
    console.log('loaded');
})(jQuery);