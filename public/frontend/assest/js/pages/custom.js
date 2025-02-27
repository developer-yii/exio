function clearErrorOnInput(containerSelector) {
    $(containerSelector).on('keyup change', 'input, textarea, select, option', function () {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass('is-invalid')
            $(this).closest('.form-group').find('.error').html('');
        }
    });
}

$('.togglePassword').on('click', function () {
    const passwordField = $(this).closest('.passwordShow').find('.password');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);

    $(this).toggleClass('bi-eye-slash bi-eye-fill');
});
$("input[name='password'], input[name='password_confirmation']").keypress(function(e) {
    if (e.which === 32) {
        return false;
    }
});