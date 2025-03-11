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

function updateShareLinks() {
    // Get current page URL
    const currentPageURL = window.location.href;

    const subject = encodeURIComponent("Check out this property!");
    const body = encodeURIComponent("I found this property and thought you might be interested:\n\n" + currentPageURL);

    document.getElementById('whatsapp-link').setAttribute('data-href', `https://api.whatsapp.com/send?text=${encodeURIComponent(currentPageURL)}`);
    document.getElementById('facebook-link').setAttribute('data-href', `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentPageURL)}`);
    document.getElementById('twitter-link').setAttribute('data-href', `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentPageURL)}`);
    document.getElementById('linkedin-link').setAttribute('data-href', `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentPageURL)}`);
    // document.getElementById('email-link').setAttribute('data-href', `mailto:?subject=${subject}&body=${body}`);
    // document.getElementById('pinterest-link').href = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(currentPageURL)}`;

    // Set the copy link value
    document.getElementById('copy-link').value = currentPageURL;
}

$('body').on('click','.social_media_share',function(event){
    var url = $(this).attr('data-href');
    var left = (screen.width - 600) / 2;
    var top = (screen.height - 400) / 2;
    window.open(url, '_blank', 'width=600,height=400,left=' + left + ',top=' + top);
});

function copyToClipboard() {
    var copyText = document.getElementById('copy-link');
    copyText.removeAttribute("disabled");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand('copy');
    if (typeof toastr !== 'undefined') {
        toastr.success('Link copied!');
        copyText.setAttribute("disabled", "true");
    } else {
        alert('Link copied!');
        copyText.setAttribute("disabled", "true");
    }
}