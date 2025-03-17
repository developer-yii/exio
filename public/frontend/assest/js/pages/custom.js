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

// $('.heartIconFill, .save-property').on('click', function (e) {
// $(document).on('click', '.heartIconFill, .save-property', function (e) {
$('body').on('click', '.heartIconFill, .save-property', function (e) {
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
            // setTimeout(function () {
            //     window.location.reload();
            // }, 500);
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


function updateShareLinks(url = window.location.href) {
    const currentPageURL = url || window.location.href; // Ensures fallback to current URL

    const encodedURL = encodeURIComponent(currentPageURL);
    const subject = encodeURIComponent("Check out this property!");
    const body = encodeURIComponent(`I found this property and thought you might be interested:\n\n${currentPageURL}`);

    const links = {
        'whatsapp-link': `https://api.whatsapp.com/send?text=${encodedURL}`,
        'facebook-link': `https://www.facebook.com/sharer/sharer.php?u=${encodedURL}`,
        'twitter-link': `https://twitter.com/intent/tweet?url=${encodedURL}`,
        'linkedin-link': `https://www.linkedin.com/sharing/share-offsite/?url=${encodedURL}`,
        // 'email-link': `mailto:?subject=${subject}&body=${body}`,
        // 'pinterest-link': `https://pinterest.com/pin/create/button/?url=${encodedURL}`,
    };

    Object.keys(links).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.setAttribute('data-href', links[id]);
        }
    });

    // Set copy link value if the input exists
    const copyInput = document.getElementById('copy-link');
    if (copyInput) {
        copyInput.value = currentPageURL;
    }
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

function modelOpacityAdd(btnClass, model){
    $(document).on('click', '.'+btnClass, function () {
        $('#'+model).addClass('disabled-modal');
    });
}

function modelOpacityRemove(hideModalId, showModalId){

    $(document).on('#'+hideModalId).on('hidden.bs.modal', function () {
        $('#'+showModalId).removeClass('disabled-modal');
    });
}

$(".propertyCardModal").click(function (event) {
    $("#coverImage").attr("src", ""); // Clear image
    $('#property_price, #property_name, #custom_type, #location, #carpet_area, #total_floor, #total_tower, #age_of_construction, #property_type, #description').text("");

    $(".overViewBox").find(".overBox").filter(function () {
        return $(this).find("span").text().trim() === "Project Size";
    }).remove();
    $(".multyimg").html("");

    let id = $(this).data("id");
    let image = $(this).data("image");
    let projectName = $(this).data("project-name");
    let price = $(this).data("price");
    let customType = $(this).data("custom-type");
    let location = $(this).data("location");
    let area = $(this).data("area");
    let floors = $(this).data("floors");
    let towers = $(this).data("towers");
    let age = $(this).data("age");
    let propertyType = $(this).data("property-type");
    let description = $(this).data("description");
    let whatsApp = $(this).data("whatsapp-number");
    let faClass = $(this).data("like-class");
    let url = $(this).data("url");

    // // Update modal content dynamically
    $("#coverImage").attr("src", image);
    $('#property_price').text(price);
    $('#property_name').text(projectName).attr('title', projectName);
    $('#custom_type').text(customType).attr('title', customType);
    $('#location').text(location).attr('title', location);
    $('#carpet_area').text(area);
    $('#total_floor').text(floors).attr('title', floors);
    $('#total_tower').text(towers).attr('title', towers);
    $('#age_of_construction').text(age);
    $('#property_type').text(propertyType).attr('title', propertyType);
    $('#description').html(description).attr('title', description);
    $(".heartIconFill").addClass(faClass);
    $(".heartIconFill").attr("data-id", id);

    let sizeData = $(this).data("size");
    if (Array.isArray(sizeData) && sizeData.length > 0) {
        let htmlContent = '';

        sizeData.forEach(item => {
            htmlContent += `
                <div class="overBox">
                    <span>Project Size</span>
                    <h6 class="one-line-text" title="${item.value}">${item.value}</h6>
                </div>
            `;
        });
        $(".overViewBox").append(htmlContent);
    }

    let multiImgs = $(this).data("multi-image");
    if (Array.isArray(multiImgs) && multiImgs.length > 0) {
        let htmlContent = '';

        multiImgs.forEach(item => {
            htmlContent += `
                <div class="box comImg">
                    <img src="${item.imgurl}">
                </div>
            `;
        });
        $(".multyimg").append(htmlContent);
    }

    let slug = $(this).data("slug");
    if (slug) {
        let propertyUrl = getPropertyDetailsUrl.replace("_slug_", slug);
        $('#more-details').attr({
            'href': propertyUrl,
            'target': '_blank'
        });

        $("#whatsapplink").attr("href", `https://wa.me/${whatsApp}?text=${encodeURIComponent(propertyUrl)}`);

        metaUpdate(projectName, projectName, image, url)
        updateShareLinks(propertyUrl);
    }

    // Show the modal
    if(!$(event.target).hasClass('checkbox') && !$(event.target).hasClass('compareBoxOpen') && !$(event.target).hasClass('heartIconFill'))
    {
        $("#propertyModal").modal("show");
    }

});

$('#subscribe').submit(function (event) {
    event.preventDefault();
    $('.error').html("");


    let form = $(this); // Reference to the form
    const $submitButton = form.find('button[type="submit"]');
    let formData = form.serialize(); // Serialize form data

    $.ajax({

        type: "POST",
        url: subscribeUrl,
        data: formData,
        beforeSend: function () {
            $submitButton.prop('disabled', true);
        },
        success: function (result) {
            $submitButton.prop('disabled', false);
            form[0].reset();
            toastr.success(result.message);

        },
        error: function (xhr) {
            $submitButton.prop('disabled', false);

            if (xhr.status === 422) { // Laravel validation error
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, messages) {
                    toastr.error(messages[0]); // Show the first error message for each field
                });
            } else {
                toastr.error('Something went wrong!');
            }
        }
    });
});

$('.downloadInsightReportPdf').click(function (event){
    event.preventDefault();
    let id = $(this).data("id");
    $.ajax({

        type: "POST",
        url: downloadInsightsRrportUrl,
        data: { id : id},
        xhrFields: {
            responseType: 'blob' // Handle binary data
        },
        success: function (data, status, xhr) {
            let blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = $.trim($('.projectTitle').data('title'))+" Insight Report.pdf";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        error: function (xhr) {
            if (xhr.status === 404) {
                toastr.error('File not found.');
            } else if (xhr.status === 401) {
                toastr.error('Please login first to download the report.');
            } else if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, messages) {
                    toastr.error(messages[0]);
                });
            } else {
                toastr.error('Something went wrong!');
            }
        }
    });
});

function metaUpdate(title, desc, image, url){
    document.querySelector('meta[property="og:title"]').setAttribute("content", title);
    document.querySelector('meta[property="og:description"]').setAttribute("content", desc);
    document.querySelector('meta[property="og:image"]').setAttribute("content", image);
    document.querySelector('meta[property="og:url"]').setAttribute("content", url);

    document.querySelector('meta[name="twitter:title"]').setAttribute("content", title);
    document.querySelector('meta[name="twitter:description"]').setAttribute("content", desc);
    document.querySelector('meta[name="twitter:image"]').setAttribute("content", image);

}

