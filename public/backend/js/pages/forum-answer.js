$(document).ready(function () {

    var forumId = window.location.pathname.split('/').pop(); // Get forum ID from URL
    getAnswersUrl = getAnswersUrl.replace('_id_', forumId);

    $('#dataTableMain').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: getAnswersUrl,
            type: "GET"
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { 
                data: 'answer', 
                name: 'answer',
                render: function (data, type, row) {
                    return `<div style="white-space: pre-wrap;">${data}</div>`;
                }
            },
            {
                name: "status",
                sortable: true,
                render: function (_, _, full) {
                    if (full["status"] == 1) {
                        return '<span class="badge badge-success-lighten">Approved</span>';
                    } else {
                        return '<span class="badge badge-danger-lighten">Pending</span>';
                    }
                },
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

let formId = "#edit-form";

$(document).on('click', '.edit-forum', function () {
    var id = $(this).data('id');
    var answer = $(this).data('answer');
    var status = $(this).data('status');

    // Populate modal fields
    $('#edit-form #id').val(id);
    $('#edit-form #answer').val(answer);
    $('#edit-form #status_id').val(status);

    // Open the modal
    $('#editModal').modal('show');
});

// Handle form submission (AJAX)
$('#edit-form').submit(function (e) {
    e.preventDefault();
    var $this = $(this);

    var formData = new FormData(this);

    $.ajax({
        url: forumUpdateUrl,
        type: "POST",
        data: formData,
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
                $('#editModal').modal('hide');
                $('#dataTableMain').DataTable().ajax.reload(); 
                showToastMessage("success", result.message);                
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


$("body").on("click", '.delete-forum', function (event) {
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
                url: forumDeleteUrl,
                data: {id : id},
                type: "POST",
                dataType: "json",
                success: function (result) {
                    swal.close();
                    if (result.status) {
                        showToastMessage("success", result.message);
                        $('#dataTableMain').DataTable().ajax.reload(); 
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
