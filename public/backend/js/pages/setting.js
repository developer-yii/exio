$(document).ready(function () {

    let tableId = '#dataTableMain',
        formId = '#add-form',
        modalId = '#addModal',
        viewModalId = '#viewModal',
        addFormBtnId = '#add-new-btn',
        addOrUpdateBtnId = '#addorUpdateBtn',
        editFormBtnId = '.edit-record',
        viewBtnId = '.view-record';

    $(addFormBtnId).click(function (event) {
        $(modalId + ' .modal-title span').html('Add');
        $(formId).find('.error').html("");
        $(formId).find(".is-invalid").removeClass('is-invalid');
        $(formId + ' .add_required').show();
        $(formId)[0].reset();
        $('#id').val(0);
    });

    $(formId).on('keyup change', 'input, textarea, select', function (event) {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').find('.error').html('');
        }
    });

    $(formId).submit(function (event) {
        event.preventDefault();
        var $this = $(this);
        var formData = new FormData(this);

        $(formId).find('.error').html("");
        $(formId).find(".is-invalid").removeClass('is-invalid');

        $.ajax({
            url: addUpdateUrl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $($this).find('button[type="submit"]').prop('disabled', true);
                $($this).find('button[type="submit"]').html('Saving...');
            },
            success: function (result) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                $($this).find('button[type="submit"]').html('Save');

                if (result.status == true) {
                    $this[0].reset();
                    $(tableId).DataTable().ajax.reload();
                    setTimeout(function () {
                        $(modalId).modal('hide');
                        showToastMessage("success", result.message);
                    }, 100);
                    $('#id').val(0);

                } else if (result.status == false && result.message) {
                    showToastMessage("error", result.message);
                } else {
                    if (result.errors) {
                        first_input = "";
                        $.each(result.errors, function (key) {
                            if (first_input == "") first_input = key;
                            $(formId).find("." + key).addClass('is-invalid');
                            $(formId + ' .' + key).closest('.form-group').find('.error').html(result.errors[key]);
                        });
                        $(formId).find("." + first_input).focus();
                    }
                }
            },
            error: function (error) {
                alert('Something went wrong!');
                location.reload();
            }
        });
    });

    $('body').on('click', editFormBtnId, function (event) {
        var id = $(this).attr('data-id');
        $.ajax({
            url: detailUrl + '?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                if (result.status) {
                    $(formId).find('.error').html('');
                    $(formId).find(".is-invalid").removeClass('is-invalid');
                    $(modalId + ' .modal-title span').html('Edit');
                    $(formId + ' .add_required').hide();
                    $(formId)[0].reset();
                    if (result.data.setting_key === 'check_match_video') {
                        $(formId).find('.video').closest('.col-md-12').removeClass('d-none');
                        $(formId).find('.setting_value').closest('.col-md-12').addClass('d-none');
                    } else {
                        $(formId).find('.setting_value').closest('.col-md-12').removeClass('d-none');
                        $(formId).find('.video').closest('.col-md-12').addClass('d-none');
                    }
                    $(modalId).modal('show');

                    $(formId).find('#id').val(id);
                    $(formId).find('#setting_key').val(result.data.setting_key);
                    $(formId).find('.setting_label').val(result.data.setting_label);
                    $(formId).find('.description').val(result.data.description);
                    $(formId).find('.setting_value').val(result.data.setting_value);
                    $(formId).find('.status').val(result.data.status);
                } else {
                    if (result.message) {
                        showToastMessage("error", result.message);
                    }
                }
            },
            error: function (error) {
                alert('Something went wrong!');
                location.reload();
            }
        });
    });

    let listTable = $(tableId).DataTable({
        searching: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[4, 'DESC']],
        ajax: {
            type: 'GET',
            url: apiUrl,
            data: function (d) {
                d.filter_date = $('#filter_date').val(),
                d.filter_status = $('#filter_status').val()
            },
        },
        language: { paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" } },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        },
        columns: [
            {
                name: 'setting_label',
                data: 'setting_label',
                sortable: true,
                render: function (_, _, full) {
                    return full['setting_label'];
                },
            },
            {
                name: 'setting_value',
                data: 'setting_value',
                sortable: true,
                render: function (_, _, full) {
                    return full['setting_value'];
                },
            },
            {
                name: 'description',
                data: 'description',
                sortable: true,
                render: function (_, _, full) {
                    return full['description'];
                },
            },
            {
                name: 'created_at',
                data: 'created_at',
                sortable: true,
                render: function (_, _, full) {
                    return full['created_at'];
                },
            },
            {
                name: 'updated_by',
                data: 'updated_by',
                sortable: true,
                render: function (_, _, full) {
                    return full['updated_by'];
                },
            },
            {
                sortable: false,
                render: function (_, _, full) {
                    var contactId = full['id'];
                    if (contactId) {
                        actions = "";
                        actions += ' <a href="javascript:void(0)" data-id="' + contactId + '" class="btn-sm btn-warning view-record"><i class="uil-eye"></i></a>';
                        actions += ' <a href="javascript:void(0)" data-id="' + contactId + '" class="btn-sm btn-info edit-record"><i class="uil-edit-alt"></i></a>';
                        return actions;
                    }

                    return '';
                },
            },
        ],
    });

    $('body').on("keyup change", "#table_search, #filter_date, #filter_status", function (e) {
        listTable.draw();
    });
});