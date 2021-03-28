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