$(document).ready(function () {
    $('#contactform').submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: contactUrl,
            type: 'POST',
            data: $('#contactform').serialize(),
            dataType: 'json',
            beforeSend: function(){
                $("#siteLoader").fadeIn();
            },
            success: function (response) {
                $("#siteLoader").fadeOut();
                if (response.status == true) {
                    toastr.success(response.message);
                    window.location.reload();
                    $('.error').html("");
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(response.errors, function (key) {
                        if (first_input == "") first_input = key;
                        if (key == "g-recaptcha-response") {
                            $('.' + key).closest('.form-group').find('.error').html(response.errors[key]);

                        } else {
                            $('#' + key).closest('.form-group').find('.error').html(response.errors[key]);
                        }
                    });
                }
            },
            error: function (error) {
                $("#siteLoader").fadeOut();
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });
});

clearErrorOnInput('#contactform');