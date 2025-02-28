$(document).ready(function () {
    let tableId = "#dataTableMain",
        deleteBtnId = ".delete-record";

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
                name: "image",
                data: "image",
                sortable: true,
                render: function (_, _, full) {
                    return full["image"];
                },
            },
            {
                name: "title",
                data: "title",
                sortable: true,
                render: function (_, _, full) {
                    return full["title"];
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
                name: "description",
                data: "description",
                sortable: true,
                render: function (_, _, full) {
                    return full["description"];
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
                        let editNewsUrl = editUrl.replace(
                            ":news_id",
                            contactId
                        );
                        actions = "";
                        actions +=
                            ' <a href="javascript:void(0)" data-id="' +
                            contactId +
                            '" class="btn-sm btn-warning view-record"><i class="uil-eye"></i></a>';
                        actions +=
                            ' <a href="' +
                            editNewsUrl +
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
