$(document).ready(function () {
    let quill = "";
    if ($('#editor_content').length > 0) {
        quill = new Quill("#editor_content", {
            theme: "snow",
            modules: {
                imageResize: {
                    displaySize: true
                },
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ color: [] }, { background: [] }],
                    [{ script: "super" }, { script: "sub" }],
                    [{ header: [!1, 1, 2, 3, 4, 5, 6] }, "blockquote", "code-block"],
                    [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
                    ["direction", { align: [] }],
                    ["link", "image"],
                    ["clean"],
                ],
            },
        });

        quill.getModule('toolbar').addHandler('image', () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = () => {
                const file = input.files[0];
                // Validate file
                if (!file.type.match(/^image\/(jpeg|png|gif)$/)) {
                    alert('Invalid file type. Please select an image file.');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    alert('File is too large. Maximum size is 2MB.');
                    return;
                }
                const reader = new FileReader();
                reader.onload = () => {
                    const base64Image = reader.result;
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', base64Image);
                };
                reader.readAsDataURL(file);
            };
        });
    }

    let tableId = '#dataTableMain',
        formId = '#add-form';

    $(formId).on('keyup change', 'input, textarea, select', function (event) {
        if ($.trim($(this).val()) && $(this).val().length > 0) {
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').find('.error').html('');
        }
    });

    $(formId).submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        var content = quill.root.innerHTML;
        if (content) {
            $(formId).find('#content').val(content);
        }

        var formData = new FormData(this);

        $(formId).find('.error').html("");
        $(formId).find(".is-invalid").removeClass('is-invalid');

        $.ajax({
            url: addUpdateUrl,
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
                    $(tableId).DataTable().ajax.reload();
                    setTimeout(function () {
                        showToastMessage("success", result.message);
                    }, 100);

                    pageReload();

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
                //location.reload();
            }
        });
    });
});