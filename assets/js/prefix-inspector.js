(function ($) {
    var opt = window.hasOwnProperty('noePrefixInspector') ? window.noePrefixInspector : {
        ajaxUrl: '',
        nonce: '',
    };

    console.dir('opt', opt);

    function toggleAddRemove(toShow, toHide) {
        var showMessage = toShow.find('.message');
        toHide.addClass('hidden');
        toShow.removeClass('hidden');
        setTimeout(function () {
            showMessage.addClass('show');
            setTimeout(function () {
                showMessage.removeClass('show');
            }, 5000);
        }, 25);
    }

    function sendAjax(data, anchor, callback) {
        $.ajax(opt.ajaxUrl, {
            method: 'post',
            data: data,
            beforeSend: function () {
            },
            success: function (response) {
                if (response.success && 'function' === typeof callback) {
                    callback();
                }
            }
        });
    }

    $(document).ready(function () {
        $('.column-action .add a').on('click', function (e) {
            var $this = $(e.currentTarget),
                add = $this.parent();
            e.preventDefault();
            sendAjax({
                action: 'noe_add_prefix',
                prefix: $this.data('prefix'),
                nonce: opt.nonce
            }, $this, function () {
                toggleAddRemove(add.siblings('.remove'), add);
            });
        });

        $('.column-action .remove a').on('click', function (e) {
            var $this = $(e.currentTarget),
                remove = $this.parent();
            e.preventDefault();
            sendAjax({
                action: 'noe_remove_prefix',
                prefix: $this.data('prefix'),
                nonce: opt.nonce
            }, $this, function () {
                toggleAddRemove(remove.siblings('.add'), remove);
            });
        });
    });
})(jQuery);