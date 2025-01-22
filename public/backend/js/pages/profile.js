$(document).ready(function () {

    let formId = '#form-profile';

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
            url: profileupdateUrl,
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
                    setTimeout(function () {
                        showToastMessage("success", result.message);
                    }, 100);

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

    let formIdPass = '#form-password';

    $(formIdPass).on('keyup change', 'input, textarea, select', function (event) {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').find('.error').html('');
        }
    });

    $(formIdPass).submit(function (event) {
        event.preventDefault();
        var $this = $(this);
        var formData = new FormData(this);

        $(formIdPass).find('.error').html("");
        $(formIdPass).find(".is-invalid").removeClass('is-invalid');

        $.ajax({
            url: updatepasswordUrl,
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
                    setTimeout(function () {
                        showToastMessage("success", result.message);
                    }, 100);

                } else if (result.status == false && result.message) {
                    showToastMessage("error", result.message);
                } else {
                    if (result.errors) {
                        first_input = "";
                        $.each(result.errors, function (key) {
                            if (first_input == "") first_input = key;
                            $(formIdPass).find("." + key).addClass('is-invalid');
                            $(formIdPass + ' .' + key).closest('.form-group').find('.error').html(result.errors[key]);
                        });
                        $(formIdPass).find("." + first_input).focus();
                    }
                }
            },
            error: function (error) {
                alert('Something went wrong!');
                location.reload();
            }
        });
    });

    $('body').on('keypress', "input[name='mobile'], input[name='current_password'], input[name='new_password'], input[name='confirm_password']", function (event) {
        if (event.which === 32) {
            return false;
        }
    });
});