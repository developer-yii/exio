$(document).ready(function () {
    let tableId = "#dataTableMain",
        formId = "#add-form",
        modalId = "#addModal",
        viewModalId = "#viewModal",
        addFormBtnId = "#add-new-btn",
        addOrUpdateBtnId = "#addorUpdateBtn",
        editFormBtnId = ".edit-record",
        deleteBtnId = ".delete-record",
        viewBtnId = ".view-record";

    $(addFormBtnId).click(function (event) {
        $(modalId + " .modal-title span").html("Add");
        $(formId).find(".error").html("");
        $(formId).find(".is-invalid").removeClass("is-invalid");
        $(formId + " .add_required").show();
        $(formId)[0].reset();
        $("#id").val(0);
    });

    $(formId).on("keyup change", "input, textarea, select", function (event) {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass("is-invalid");
            $(this).closest(".form-group").find(".error").html("");
        }
    });

    $("body").on(
        "keypress",
        "input[name='mobile'], input[name='password'], input[name='confirm_password']",
        function (event) {
            if (event.which === 32) {
                return false;
            }
        }
    );

    $(formId).submit(function (event) {
        event.preventDefault();
        var $this = $(this);
        var formData = new FormData(this);

        $(formId).find(".error").html("");
        $(formId).find(".is-invalid").removeClass("is-invalid");

        $.ajax({
            url: addUpdateUrl,
            type: "POST",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $($this).find('button[type="submit"]').prop("disabled", true);
                $($this).find('button[type="submit"]').html("Saving...");
            },
            success: function (result) {
                $($this).find('button[type="submit"]').prop("disabled", false);
                $($this).find('button[type="submit"]').html("Save");

                if (result.status == true) {
                    $this[0].reset();
                    $(tableId).DataTable().ajax.reload();
                    setTimeout(function () {
                        $(modalId).modal("hide");
                        showToastMessage("success", result.message);
                    }, 100);
                    $("#id").val(0);
                } else if (result.status == false && result.message) {
                    showToastMessage("error", result.message);
                } else {
                    if (result.errors) {
                        first_input = "";
                        $.each(result.errors, function (key) {
                            if (first_input == "") first_input = key;
                            $(formId)
                                .find("." + key)
                                .addClass("is-invalid");
                            $(formId + " ." + key)
                                .closest(".form-group")
                                .find(".error")
                                .html(result.errors[key]);
                        });
                        $(formId)
                            .find("." + first_input)
                            .focus();
                    }
                }
            },
            error: function (error) {
                alert("Something went wrong!");
                location.reload();
            },
        });
    });

    $("body").on("click", editFormBtnId, function (event) {
        var id = $(this).attr("data-id");
        $.ajax({
            url: detailUrl + "?id=" + id,
            type: "GET",
            dataType: "json",
            success: function (result) {
                if (result.status) {
                    $(formId).find(".error").html("");
                    $(formId).find(".is-invalid").removeClass("is-invalid");
                    $(modalId + " .modal-title span").html("Edit");
                    $(formId + " .add_required").hide();
                    $(formId)[0].reset();

                    $(modalId).modal("show");

                    $(formId).find("#id").val(id);
                    $(formId).find(".name").val(result.data.name);
                    $(formId).find(".email").val(result.data.email);
                    $(formId).find(".mobile").val(result.data.mobile);
                    $(formId).find(".status").val(result.data.status);
                } else {
                    if (result.message) {
                        showToastMessage("error", result.message);
                    }
                }
            },
            error: function (error) {
                alert("Something went wrong!");
                location.reload();
            },
        });
    });

    $("body").on("click", viewBtnId, function (event) {
        var id = $(this).attr("data-id");
        $.ajax({
            url: detailUrl + "?id=" + id,
            type: "GET",
            dataType: "json",
            success: function (result) {
                if (result.status) {
                    $(viewModalId).modal("show");
                    $(viewModalId).find(".name").html(result.data.name);
                    $(viewModalId).find(".email").html(result.data.email);
                    $(viewModalId).find(".mobile").html(result.data.mobile);
                    $(viewModalId)
                        .find(".status")
                        .html(result.data.status_text);
                    $(viewModalId)
                        .find(".role_type")
                        .html(result.data.role_type_text);
                    $(viewModalId)
                        .find(".email_verified_at")
                        .html(result.data.email_verified_at_view);
                    $(viewModalId)
                        .find(".created_at")
                        .html(result.data.created_at_view);
                    $(viewModalId)
                        .find(".updated_at")
                        .html(result.data.updated_at_view);
                } else {
                    if (result.message) {
                        showToastMessage("error", result.message);
                    }
                }
            },
            error: function (error) {
                alert("Something went wrong!");
                location.reload();
            },
        });
    });

    $("body").on("click", deleteBtnId, function (event) {
        event.preventDefault();
        var id = $(this).attr("data-id");
        swal(
            {
                title: "Are you sure want to delete?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
            },
            function () {
                $.ajax({
                    url: deleteUrl + "?id=" + id,
                    type: "POST",
                    dataType: "json",
                    success: function (result) {
                        swal.close();
                        if (result.status) {
                            showToastMessage("success", result.message);
                            $(tableId).DataTable().ajax.reload();
                        } else {
                            showToastMessage("error", result.message);
                        }
                    },
                    error: function (error) {
                        swal.close();
                        console.log("Error:", error);
                    },
                });
            }
        );
    });

    let listTable = $(tableId).DataTable({
        searching: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[4, "DESC"]],
        ajax: {
            type: "GET",
            url: apiUrl,
            data: function (d) {
                (d.filter_date = $("#filter_date").val()),
                    (d.filter_status = $("#filter_status").val());
            },
        },
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>",
            },
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass(
                "pagination-rounded"
            );
        },
        columns: [
            {
                name: "name",
                data: "name",
                sortable: true,
                render: function (_, _, full) {
                    return full["name"];
                },
            },
            {
                name: "email",
                data: "email",
                sortable: true,
                render: function (_, _, full) {
                    return full["email"];
                },
            },
            {
                name: "mobile",
                data: "mobile",
                sortable: true,
                render: function (_, _, full) {
                    return full["mobile"];
                },
            },
            {
                name: "status",
                data: "status_text",
                sortable: true,
                render: function (_, _, full) {
                    if (full["status"] == 1) {
                        return (
                            '<span class="badge badge-success-lighten">' +
                            full["status_text"] +
                            "</span>"
                        );
                    } else {
                        return (
                            '<span class="badge badge-danger-lighten">' +
                            full["status_text"] +
                            "</span>"
                        );
                    }
                },
            },
            {
                name: "created_at",
                data: "created_at",
                sortable: true,
                render: function (_, _, full) {
                    return full["created_at"];
                },
            },
            {
                sortable: false,
                render: function (_, _, full) {
                    var contactId = full["id"];
                    if (contactId) {
                        actions = "";
                        actions +=
                            ' <a href="javascript:void(0)" data-id="' +
                            contactId +
                            '" class="btn-sm btn-warning view-record"><i class="uil-eye"></i></a>';
                        actions +=
                            ' <a href="javascript:void(0)" data-id="' +
                            contactId +
                            '" class="btn-sm btn-info edit-record"><i class="uil-edit-alt"></i></a>';
                        actions +=
                            ' <a href="javascript:void(0)" data-id="' +
                            contactId +
                            '" class="btn-sm btn-danger delete-record"><i class="uil-trash-alt"></i></a>';
                        return actions;
                    }

                    return "";
                },
            },
        ],
    });

    $("body").on(
        "keyup change",
        "#table_search, #filter_date, #filter_status",
        function (e) {
            listTable.draw();
        }
    );
});
