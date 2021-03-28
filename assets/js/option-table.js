(function ($) {
    $(document).ready(function () {
        var opt = window.hasOwnProperty('noeOptionTable') ? window.noeOptionTable : {
            ajaxUrl: '',
            nonce: {}
        };

        var theList = $('#prefix-filter'),
            tmpl = wp.template('filter-item');

        $('a.submitdelete').on('click', function (e) {
            if (!confirm('Are you sure you want to delete this option?')) {
                e.preventDefault();
                return false;
            }
        });

        $('#prefix-filter-dialog').dialog({
            modal: true,
            buttons: {
                'Add': function () {
                    var newPrefix = $('#new-prefix'),
                        newValue = newPrefix.val().trim();
                    if (newValue.length) {
                        theList.append(tmpl({value: newValue}));
                        newPrefix.val('');
                        $(this).dialog('close');
                    }
                },
                'Cancel': function () {
                    $(this).dialog('close');
                },
            },
            autoOpen: false,
        });

        $('#prefix-setup-top, #prefix-setup-bottom').on('click', function () {
            $('#prefix-filter-dialog').dialog('open');
        });

        theList.on('click', '.remove', function (e) {
            $(e.currentTarget).closest('li').remove();
        });

        $('#remove-all-filters').on('click', function (e) {
            if (confirm('Are you sure you want to remove all prefixes?')) {
                theList.html('');
            }
        });
    });
})(jQuery)
