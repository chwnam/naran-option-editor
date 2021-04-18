(function ($) {
    $(document).ready(function () {
        var opts = window.hasOwnProperty('noeOptionTable') ? window.noeOptionTable : {
            ajaxUrl: '',
            nonce: '',
            textAdd: 'Add',
            textCancel: 'Cancel',
            textCheckedIsZeroLength: 'Check one or more options.',
            textConfirmDeleteOption: 'Are you sure you want to delete this option?',
            textConfirmRemoveAllFilters: 'Are you sure you want to remove all prefixes?',
            textPrefixAlreadyExists: 'The prefix is already added. Please choose another one.',
            textRestoreOptionAlert: 'Are you sure you want to restore option table with the file?',
            textRestoreComplete: 'The option table is restored.',
            textSubmit: 'Submit',
        };

        var prefixManager = {
            _callAjax: function (data, callback) {
                $.ajax(opts.ajaxUrl, {
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
                    nonce: opts.nonce,
                    prefix: prefix,
                }, callback);
            },
            remove: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_remove_prefix',
                    nonce: opts.nonce,
                    prefix: prefix,
                }, callback);
            },
            clear: function (callback) {
                this._callAjax({
                    action: 'noe_clear_prefixes',
                    nonce: opts.nonce,
                }, callback);
            },
            enable: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_enable_prefix',
                    nonce: opts.nonce,
                    prefix: prefix,
                }, callback);
            },
            enableAll: function (callback) {
                this._callAjax({
                    action: 'noe_enable_all_prefixes',
                    nonce: opts.nonce,
                }, callback);
            },
            disable: function (prefix, callback) {
                this._callAjax({
                    action: 'noe_disable_prefix',
                    nonce: opts.nonce,
                    prefix: prefix
                }, callback);
            },
            disableAll: function (callback) {
                this._callAjax({
                    action: 'noe_disable_all_prefixes',
                    nonce: opts.nonce,
                }, callback);
            },
        };

        var theList = $('#prefix-filter'),
            toggleAll = $('#noe-toggle-all-prefixes'),
            toggleAllWrap = $('#noe-toggle-all-prefixes-wrap'),
            tmpl = wp.template('filter-item');

        // Prefix filer dialog.
        var prefixDialog = null;

        // Open the prefix filter dialog.
        $('#prefix-setup-top, #prefix-setup-bottom').on('click', function () {
            if (!prefixDialog) {
                var prefixDialogOpts = {
                    autoOpen: false,
                    buttons: {},
                    draggable: false,
                    modal: true,
                    resizable: false,
                };

                prefixDialogOpts.buttons[opts.textAdd] = function () {
                    var newPrefix = $('#new-prefix'),
                        newValue = newPrefix.val().trim();
                    if (newValue.length) {
                        if (hasPrefix(newValue)) {
                            alert(opts.textPrefixAlreadyExists);
                        } else {
                            prefixManager.add(newValue, function () {
                                theList.trigger('prefixAdded', newValue);
                                newPrefix.val('');
                            });
                            prefixDialog.dialog('close');
                        }
                    }
                };

                prefixDialogOpts.buttons[opts.textCancel] = function () {
                    prefixDialog.dialog('close');
                };

                prefixDialog = $('#prefix-filter-dialog').dialog(prefixDialogOpts);
            }

            prefixDialog.dialog('open');
        });

        // Edit desc dialog.
        var editDescDialog = null;

        // Open edit-desc-dialog
        $('.edit-option_desc').on('click', function (e) {
            var tr,
                optionId,
                optionName,
                optionValue,
                optionDesc,
                self = $(e.currentTarget);

            e.preventDefault();

            if (!editDescDialog) {
                var editDescDialogOpts = {
                    autoOpen: false,
                    buttons: {},
                    draggable: false,
                    modal: true,
                    resizable: false,
                }

                // Submit edit desc.
                editDescDialogOpts.buttons[opts.textSubmit] = function () {
                    var optionId = $('#edit-desc-option_id').val(),
                        optionDesc = $('#edit-desc-textarea').val();

                    $.ajax(opts.ajaxUrl, {
                        method: 'post',
                        data: {
                            action: 'noe_edit_option_desc',
                            nonce: opts.nonce,
                            option_id: optionId,
                            option_desc: optionDesc,
                        },
                        beforeSend: function () {
                            var anchor = editDescDialog.data('noeCurrentEdit'),
                                columnText = anchor.siblings('.option-desc-text');
                            columnText.text(optionDesc);
                            editDescDialog.dialog('close');
                        },
                        success: function (response) {
                            if (!response.success && response.hasOwnProperty('data') && Array.isArray(response.data)) {
                                alert('[' + response.data[0].code + '] ' + response.data[0].message);
                            } else {
                                console.error('Error', response);
                            }
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            alert(jqXhr.status + ': ' + errorThrown);
                        }
                    });
                };

                editDescDialogOpts.buttons[opts.textCancel] = function () {
                    editDescDialog.dialog('close');
                };

                editDescDialog = $('#edit-desc-dialog').dialog(editDescDialogOpts);
            }

            tr = self.closest('tr');
            optionId = tr.find('td.column-option_id').text().trim();
            optionName = tr.find('td.column-option_name a.row-title').attr('title');
            optionValue = tr.find('td.column-option_value').text().trim();
            optionDesc = tr.find('.option-desc-text').text().trim();

            // Dialog text update.
            editDescDialog.find('#edit-desc-option_id').val(optionId);
            editDescDialog.find('#edit-desc-option_name').text(optionName);
            editDescDialog.find('#edit-desc-option_value').text(optionValue);
            editDescDialog.find('#edit-desc-textarea').val(optionDesc);
            editDescDialog.data('noeCurrentEdit', self);
            editDescDialog.dialog('open');
        });

        // Bulk edit desc dialog.
        var bulkEditDescDialog = null,
            bulkEditDescTmpl = null;

        // Open the bulk desc edit dialog
        $('#doaction').on('click', function (e) {
            if ('bulk_edit_desc' === $('#bulk-action-selector-top').val()) {
                e.preventDefault();

                if (!bulkEditDescDialog) {
                    var bulkEditDescDialogOpts = {
                        autoOpen: false,
                        buttons: {},
                        draggable: false,
                        modal: true,
                        resizable: false,
                    }

                    // Submit bulk option desc.
                    bulkEditDescDialogOpts.buttons[opts.textSubmit] = function () {
                        var optionIds = [],
                            optionDesc = $('#bulk-edit-desc-textarea');

                        $('#bulk-edit-desc-option-names')
                            .find('input[type="hidden"][name="bulk_edit_option_id\\[\\]"]')
                            .each(function (idx, elem) {
                                optionIds.push(elem.value);
                            });

                        $.ajax(opts.ajaxUrl, {
                            method: 'post',
                            data: {
                                action: 'noe_bulk_edit_option_desc',
                                nonce: opts.nonce,
                                option_ids: optionIds,
                                option_desc: optionDesc.val().trim()
                            },
                            beforeSend: function () {
                                var currentEdit = bulkEditDescDialog.data('noeCurrentEdit'),
                                    text = optionDesc.val();
                                $(currentEdit).each(function (idx, elem) {
                                    $(elem).text(text);
                                });
                                optionDesc.val('');
                                bulkEditDescDialog.dialog('close');
                            },
                            success: function (response) {
                                if (!response.success && response.hasOwnProperty('data') && Array.isArray(response.data)) {
                                    alert('[' + response.data[0].code + '] ' + response.data[0].message);
                                } else {
                                    console.error('Error', response);
                                }
                            },
                            error: function (jqXhr, textStatus, errorThrown) {
                                alert(jqXhr.status + ': ' + errorThrown);
                            }
                        });
                    };

                    bulkEditDescDialogOpts.buttons[opts.textCancel] = function () {
                        bulkEditDescDialog.dialog('close');
                    }

                    bulkEditDescDialog = $('#bulk-edit-desc-dialog').dialog(bulkEditDescDialogOpts);
                    bulkEditDescTmpl = wp.template('bulk-edit-option-name');
                }

                var checked = $('input[type="checkbox"][name="option\\[\\]"]:checked'),
                    items = [],
                    currentEdit = [];

                if (!checked.length) {
                    alert(opts.textCheckedZeroLength);
                }

                items = $(checked).map(function (idx, elem) {
                    var tr = $(elem).closest('tr'),
                        optionId = tr.find('.column-option_id').text().trim(),
                        optionName = tr.find('.column-option_name a.row-title').attr('title'),
                        optionDesc = tr.find('.column-option_desc');

                    currentEdit.push(optionDesc);

                    return {
                        option_id: optionId,
                        option_name: optionName
                    }
                });

                // fill values.
                $('#bulk-edit-desc-option-names').html(bulkEditDescTmpl(items));
                bulkEditDescDialog.data('noeCurrentEdit', currentEdit);
                bulkEditDescDialog.dialog('open');
            }
        });

        // Delete option.
        $('a.submitdelete').on('click', function (e) {
            if (!confirm(opts.textConfirmDeleteOption)) {
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
            if (confirm(opts.textConfirmRemoveAllFilters)) {
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
            toggleAllWrap.delay(500).fadeOut();
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

        // Option table restoration
        $('#restore-option-top').on('click', function () {
            $('#backup-file-upload-top').trigger('click');
        });

        $('#backup-file-upload-top').on('change', function (e) {
            var formData = new FormData(),
                button = $('#restore-option-top');

            e.preventDefault();

            if (!confirm(opts.textRestoreOptionAlert)) {
                e.currentTarget.value = '';
                return false;
            }

            formData.append('action', 'noe_restore_options');
            formData.append('_noe_nonce', opts.nonce);
            formData.append('backup_file', e.currentTarget.files[0]);

            $.ajax(opts.ajaxUrl, {
                method: 'post',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    button.attr('disabled', 'disabled');
                },
                success: function (response) {
                    if (response.success) {
                        alert(opts.textRestoreComplete);
                        location.reload();
                    } else if (response.hasOwnProperty('data') && Array.isArray(response.data)) {
                        var message = [];
                        response.data.forEach(function (error) {
                            message.push('[' + error.code + '] ' + error.message);
                        });
                        alert(message.join('\n'));
                    } else {
                        console.error('Error', response);
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    alert(jqXhr.status + ': ' + errorThrown);
                },
                complete: function () {
                    button.removeAttr('disabled');
                }
            });
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
