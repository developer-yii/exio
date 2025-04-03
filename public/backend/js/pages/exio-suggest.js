$(document).ready(function () {

    let tableId = "#dataTableMain",
        formId = "#add-form",
        modalId = "#addModal",
        viewModalId = "#viewModal",
        addFormBtnId = "#add-new-btn",
        addOrUpdateBtnId = "#addorUpdateBtn",
        editFormBtnId = ".edit-form",
        deleteBtnId = ".delete-form",
        viewBtnId = ".view-record";

    $(addFormBtnId).click(function (event) {
        $(modalId + " .modal-title span").html("Add");
        $(formId).find(".error").html("");
        $(formId).find(".is-invalid").removeClass("is-invalid");
        $(formId + " .add_required").show();
        $(formId)[0].reset();
        $("#id").val(0);
    });

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
        
    $('body').on('click','.edit-form',function(){
        var id = $(this).data('id');  
        $(".id").val(id); 

        $.ajax({
            type : "GET",
            url : detailUrl,
            data : {id : id},
            dataType : 'json',
            success : function(result){
                $(modalId).modal('show');
                $(formId).find(".type").val(result.type);
                $(formId).find(".title").val(result.title);
                $(formId).find(".weightage").val(result.weightage);
                $(modalId).find('button[type="submit"]').html("Update");
                $(modalId + " .modal-title span").html("Edit");
            }
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
                    url: deleteUrl,
                    data : {id : id},
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
   
    var dataTableMain = $(tableId).DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: apiUrl,
        },
        order : [0, 'desc'],
        columns: [
            { data: 'id', name: 'id', visible : false },
            { data: 'type', name: 'type' },
            { data: 'title', name: 'title' },
            { data: 'weightage', name: 'weightage' },                      
            { data: 'action', name: 'action' }, 
        ],
    });

    $(formId).on("keyup change", "input, textarea, select", function (event) {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass("is-invalid");
            $(this).closest(".form-group").find(".error").html("");
        }
    });

    $(document).on('hidden.bs.modal', '#addModal', function () {
        let form = document.getElementById("add-form");
        if (form) {
            form.reset(); // Reset the form fields
            $('.error').html(""); // Clear any validation error messages
        }
    });
    
    
});
