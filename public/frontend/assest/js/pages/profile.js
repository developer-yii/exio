$(document).ready(function () {
    let formId = '#updateProfile';
    $(formId).submit(function (event) {
        event.preventDefault();
        var $this = $(this);
        var formData = new FormData(this);

        $(formId).find('.error').html("");
        $(formId).find(".is-invalid").removeClass('is-invalid');

        $.ajax({
            url: updateProfileUrl,
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
                $($this).find('button[type="submit"]').html('Update');

                if (result.status == true) {
                    setTimeout(function () {
                        toastr.success(result.message)
                    }, 100);

                } else if (result.status == false && result.message) {
                    toastr.error(result.message)
                } else {
                    if (result.errors) {
                        first_input = "";
                        $.each(result.errors, function (key) {
                            if (first_input == "") first_input = key;
                            $('#' + key).closest('.form-group').find('.error').html(result.errors[key]);
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
});