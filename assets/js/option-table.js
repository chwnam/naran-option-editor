(function ($) {
    $(document).ready(function () {
        var opt = window.hasOwnProperty('noeOptionTable') ? window.noeOptionTable : {
            ajaxUrl: '',
            nonce: '',
            textPrefixAlreadyExists: 'The prefix is already added. Please choose another one.',
        };

        var prefixManager = {
            _callAjax: function (data, callback) {
                $.ajax(opt.ajaxUrl, {
                    method: 'post',
                    data: data,
                    beforeSend: function () {
                        if ('function' === typeof callback) {
                            callback();
                        }
                    }
                });
            },
            add: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_add_prefix',
                    nonce: opt.nonce,
                    prefix: prefix,
                }, callback);
            },
            remove: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_remove_prefix',
                    nonce: opt.nonce,
                    prefix: prefix,
                }, callback);
            },
            clear: function (callback) {
                this._callAjax({
                    action: 'noe_clear_prefixes',
                    nonce: opt.nonce,
                }, callback);
            },
            enable: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_enable_prefix',
                    nonce: opt.nonce,
                    prefix: prefix,
                }, callback);
            },
            enableAll: function (callback) {
                this._callAjax({
                    action: 'noe_enable_all_prefixes',
                    nonce: opt.nonce,
                }, callback);
            },
            disable: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_disable_prefix',
                    nonce: opt.nonce,
                    prefix: prefix
                }, callback);
            },
            disableAll: function (callback) {
                this._callAjax({
                    action: 'noe_disable_all_prefixes',
                    nonce: opt.nonce,
                }, callback);
            },
        };

        var theList = $('#prefix-filter'),
            toggleAll = $('#noe-toggle-all-prefixes'),
            toggleAllWrap = $('#noe-toggle-all-prefixes-wrap'),
            tmpl = wp.template('filter-item');

        // Dialog declaration
        $('#prefix-filter-dialog').dialog({
            modal: true,
            buttons: {
                'Add': function () {
                    var newPrefix = $('#new-prefix'),
                        newValue = newPrefix.val().trim();

                    if (newValue.length) {
                        if (hasPrefix(newValue)) {
                            alert(opt.textPrefixAlreadyExists);
                        } else {
                            prefixManager.add(newValue, function () {
                                theList.trigger('prefixAdded', newValue);
                                newPrefix.val('');
                            });
                            $(this).dialog('close');
                        }
                    }
                },
                'Cancel': function () {
                    $(this).dialog('close');
                },
            },
            autoOpen: false,
        });

        // Open the prefix filter dialog.
        $('#prefix-setup-top, #prefix-setup-bottom').on('click', function () {
            $('#prefix-filter-dialog').dialog('open');
        });

        // Delete option.
        $('a.submitdelete').on('click', function (e) {
            if (!confirm('Are you sure you want to delete this option?')) {
                e.preventDefault();
                return false;
            }
        });

        // Remove each prefix item.
        theList.on('click', '.remove', function (e) {
            var prefix = $(e.currentTarget).siblings('input[type="checkbox"]').val();
            if (prefix) {
                prefixManager.remove(prefix, function () {
                    theList.trigger('prefixRemoved', prefix);
                });
            }
        });

        // Clear all prefixes.
        $('#remove-all-filters').on('click', function (e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to remove all prefixes?')) {
                prefixManager.clear(function () {
                    theList.trigger('removeAllPrefixes');
                });
            }
        });

        // Enable/disable prefix
        theList.on('change', 'input[type="checkbox"][name="pf\\[\\]"]', function (e) {
            var $this = $(e.currentTarget);

            if ($this.is(':checked')) {
                prefixManager.enable($this.val());
            } else {
                prefixManager.disable($this.val());
            }

            if (countPrefixes() === countCheckedPrefixes()) {
                toggleAll.prop('checked', 'checked');
            } else {
                toggleAll.removeAttr('checked', 'checked');
            }
        });

        // Enable/disable all prefixes
        toggleAll.on('change', function () {
            if (toggleAll.is(':checked')) {
                prefixManager.enableAll(function () {
                    theList.trigger('enableAllPrefixes');
                });
            } else {
                prefixManager.disableAll(function () {
                    theList.trigger('disableAllPrefixes');
                });
            }
        });

        // theList event handler
        theList.on('enableAllPrefixes', function () {
            getPrefixWraps().each(function (idx, elem) {
                $(elem).find('input[type="checkbox"]').prop('checked', 'checked');
            });
        }).on('disableAllPrefixes', function (e) {
            getPrefixWraps().each(function (idx, elem) {
                $(elem).find('input[type="checkbox"]').removeAttr('checked');
            });
        }).on('removeAllPrefixes', function () {
            getPrefixWraps().remove();
            toggleAllWrap.delay(500).fadeIn();
        }).on('prefixAdded', function (e, prefix) {
            theList.append(tmpl({prefix: prefix}));
            if (countPrefixes() > 1) {
                toggleAllWrap.delay(500).fadeIn();
            }
        }).on('prefixRemoved', function (e, prefix) {
            var item = getPrefixWrap(prefix);
            if (item.length) {
                item.closest('li').remove();
            }
            if (countPrefixes() < 2) {
                toggleAllWrap.delay(500).fadeOut();
            }
        });

        function getPrefixWraps() {
            return theList.find('li:not(#noe-toggle-all-prefixes-wrap)');
        }

        function countPrefixes() {
            return getPrefixWraps().length;
        }

        function countCheckedPrefixes() {
            return theList.find('input:not(#noe-toggle-all-prefixes):checked').length;
        }

        function getPrefixWrap(prefix) {
            return theList.find('input[type="checkbox"][value="' + prefix + '"]');
        }

        function hasPrefix(prefix) {
            return getPrefixWrap(prefix).length;
        }
    });
})(jQuery)
