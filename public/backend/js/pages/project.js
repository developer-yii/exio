$(document).ready(function () {
    let tableId = "#dataTableMain",
        formId = "#add-form",
        modalId = "#addModal",
        viewModalId = "#viewModal",
        addFormBtnId = "#add-new-btn",
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
                name: "project_name",
                data: "project_name",
                sortable: true,
                render: function (_, _, full) {
                    return full["project_name"];
                },
            },
            {
                name: "slug",
                data: "slug",
                sortable: true,
                render: function (_, _, full) {
                    return full["slug"];
                },
            },
            {
                name: "project_about",
                data: "project_about",
                sortable: true,
                render: function (_, _, full) {
                    return full["project_about"];
                },
            },
            {
                name: "project_badge",
                data: "project_badge",
                sortable: true,
                render: function (_, _, full) {
                    return full["project_badge"];
                },
            },
            {
                name: "builder_name",
                data: "builder_name",
                sortable: true,
                render: function (_, _, full) {
                    return full["builder_name"];
                },
            },
            {
                name: "city_name",
                data: "city_name",
                sortable: true,
                render: function (_, _, full) {
                    return full["city_name"];
                },
            },
            {
                name: "location_name",
                data: "location_name",
                sortable: true,
                render: function (_, _, full) {
                    return full["location_name"];
                },
            },
            {
                name: "property_type",
                sortable: true,
                render: function (_, _, full) {
                    return full["property_type"];
                },
            },
            {
                name: "property_sub_types",
                sortable: true,
                render: function (_, _, full) {
                    return full["property_sub_types"];
                },
            },
            {
                name: "possession_by",
                sortable: true,
                render: function (_, _, full) {
                    return full["possession_by"];
                },
            },
            {
                name: "rera_number",
                sortable: true,
                render: function (_, _, full) {
                    return full["rera_number"];
                },
            },
            {
                name: "rera_progress",
                sortable: true,
                render: function (_, _, full) {
                    return full["rera_progress"];
                },
            },
            {
                name: "actual_progress",
                sortable: true,
                render: function (_, _, full) {
                    return full["actual_progress"];
                },
            },
            {
                name: "price_from",
                sortable: true,
                render: function (_, _, full) {
                    return full["price_from"];
                },
            },
            {
                name: "price_to",
                sortable: true,
                render: function (_, _, full) {
                    return full["price_to"];
                },
            },
            {
                name: "status",
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
                        let editUrl = window.editUrl.replace(":id", contactId);
                        let viewUrl = window.viewUrl.replace(":id", contactId);
                        actions = "";
                        actions +=
                            ' <a href="' +
                            viewUrl +
                            '" class="btn-sm btn-warning view-record"><i class="uil-eye"></i></a>';
                        actions +=
                            ' <a href="' +
                            editUrl +
                            '" class="btn-sm btn-info edit-record"><i class="uil-edit-alt"></i></a>';
                        if(isSuperAdmin){
                            actions +=
                                ' <a href="javascript:void(0)" data-id="' +
                                contactId +
                                '" class="btn-sm btn-danger delete-record"><i class="uil-trash-alt"></i></a>';
                        }
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
