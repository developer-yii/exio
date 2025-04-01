$('body').on('click', '.askQuestionBtn', function () {
    toastr.error('Please log in first to ask a question or answer');
});

document.addEventListener("DOMContentLoaded", function () {
    const questionInput = document.getElementById("question");
    const questionCharCount = document.getElementById("questionCharCount");

    if(questionInput){
        questionInput.addEventListener("input", function () {
            let remaining = 250 - this.value.length;
            questionCharCount.textContent = `${remaining}/250`;

            if (this.value.length > 250) {
                this.value = this.value.substring(0, 250);
                questionCharCount.textContent = "0/250";
            }
        });
    }

    const answerInput = document.getElementById("answer");
    const answerCharCount = document.getElementById("answerCharCount");
    if(answerInput){
        answerInput.addEventListener("input", function () {
            let remaining = 1000 - this.value.length;
            answerCharCount.textContent = `${remaining}/1000`;

            if (this.value.length > 1000) {
                this.value = this.value.substring(0, 1000);
                answerCharCount.textContent = "0/1000";
            }
        });
    }
});
$(document).ready(function () {
    $('#questionForm').submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: qtySubmitUrl,
            type: 'POST',
            data: $('#questionForm').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function (response) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                $('.error').html("");
                if (response.status == true) {
                    $('#questionForm')[0].reset(); // Reset form
                    grecaptcha.reset();
                    toastr.success(response.message);
                    $('.askModal').modal('hide');
                } else {
                    first_input = "";
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
                console.log(error);
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });

    $('body').on('click', '.btnAnsAdd', function () {
        var id = $(this).data('id');
        $(".forum-id").val(id);
    });

    $('#answerForm').submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        $.ajax({
            url: ansSubmitUrl,
            type: 'POST',
            data: $('#answerForm').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $($this).find('button[type="submit"]').prop('disabled', true);
            },
            success: function (response) {
                $($this).find('button[type="submit"]').prop('disabled', false);
                $('.error').html("");
                if (response.status == true) {
                    $('#answerForm')[0].reset(); // Reset form
                    grecaptcha.reset();
                    toastr.success(response.message);
                    $('.askModal').modal('hide');
                } else {
                    first_input = "";
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
                $($this).find('button[type="submit"]').prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });
});

clearErrorOnInput('#questionForm');
clearErrorOnInput('answerForm');

$(document).on('#questionForm').on('hidden.bs.modal', function () {
    let form = document.getElementById("questionForm");
    if (form) {
        form.reset();
        grecaptcha.reset();
        $('.error').html("");
    }
});

$(document).on('answerForm').on('hidden.bs.modal', function () {
    let form = document.getElementById("answerForm");
    if (form) {
        form.reset();
        grecaptcha.reset();
        $('.error').html("");
    }
});


// $(document).on('#questionForm').on('hidden.bs.modal', function () {
//     $('#questionForm')[0].reset();
//     grecaptcha.reset();
//     $('.error').html("");
// });

// $(document).on('answerForm').on('hidden.bs.modal', function () {
//     $('#answerForm')[0].reset();
//     grecaptcha.reset();
//     $('.error').html("");
// });