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

$(".addressBox").each(function () {
    var para = $(this).find("p");
    var moreBtn = $(this).find(".more-locality");

    if (para[0].scrollHeight > para[0].clientHeight) {
        moreBtn.show();
    } else {
        moreBtn.hide();
    }
});

$(".more-locality").click(function (event) {
    event.preventDefault(); // Prevent redirection
    event.stopPropagation();

    var parentBox = $(this).closest(".addressBox");
    parentBox.toggleClass("expanded");

    var para = parentBox.find("p");
    $(this).text(parentBox.hasClass("expanded") ? "less" : "more");

    // Expand or collapse the text
    if (parentBox.hasClass("expanded")) {
        para.css({
            "-webkit-line-clamp": "unset",
            "display": "block",
            "white-space": "normal"
        });
    } else {
        para.css({
            "-webkit-line-clamp": "1",
            "display": "-webkit-box",
            "white-space": "nowrap"
        });
    }
});

$('.heartIconFill, .save-property').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let propertyId = $(this).data('id'); // Get property ID from data-id
    let heartIcon = $(this).find('i').length ? $(this).find('i') : $(this);
    $.ajax({
        url: propertyLikeUrl,
        type: "POST",
        data: { property_id: propertyId },
        success: function (response) {
            if (response.status === "liked") {
                heartIcon.removeClass('fa-regular').addClass('fa-solid');
            } else {
                heartIcon.removeClass('fa-solid').addClass('fa-regular');
            }
            // toastr.success(response.message);
        },
        error: function () {
            alert("Something went wrong. Please try again.");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".showMoreLocality").forEach(button => {
        button.addEventListener("click", function() {
            const localityWrapper = this.closest(".localityItem").parentElement; // Find the parent container
            localityWrapper.querySelectorAll(".localityItem.d-none").forEach(el => el.classList.remove("d-none"));
            this.closest(".localityItem").style.display = "none"; // Hide the clicked "More" button
        });
    });

    document.querySelectorAll(".showMoreAmenity").forEach(button => {
        button.addEventListener("click", function() {
            const amenityWrapper = this.closest(".more").parentElement; // Find the parent container
            amenityWrapper.querySelectorAll(".itemsBox.d-none").forEach(el => el.classList.remove("d-none"));
            this.closest(".more").style.display = "none"; // Hide the clicked "More" button
        });
    });
});