$(document).ready(function () {
    $('#contactform').submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: contactUrl,
            type: 'POST',
            data: $('#contactform').serialize(),
            dataType: 'json',
            // beforeSend: function () {
            //     $($this).find('button[type="submit"]').prop('disabled', true);
            // },
            beforeSend: function(){
                $('.block_model').block({
                    message: '<h3>Please Wait...</h3>',
                    css: {
                        border: '1px solid #fff'
                    }
                });
            },
            success: function (response) {
                // $($this).find('button[type="submit"]').prop('disabled', false);
                $('.block_model').unblock();
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
                $('.block_model').unblock();
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });
});

clearErrorOnInput('#contactform');