$(document).ready(function () {
    let sliderMin = $('#slider-min').val();
    let sliderMax = $('#slider-max').val();
    let propertyType = $('[name="property_type"]:checked').val();
    let subTypes_o = propertySubTypes[propertyType];

    let htmlsub = '';
    for (let key in subTypes_o) {
        htmlsub += `<div class="clickTo">
                        <label class="checkbox">
                            <input class="checkbox__input" type="checkbox" name="property_sub_types[]" class="keyword-checkbox" value="${key}">
                            <span class="checkbox__label">${subTypes_o[key]}</span>
                        </label>
                    </div>`;
    }
    $('#property-sub-type').html(htmlsub);

    $('#slider-min-value').text(sliderMin);
    $('#slider-max-value').text(sliderMax);

    $('#moreAmenities').click(function () {
        loadMoreAmenities();
    });

    $('#lessAmenities').click(function () {
        loadLessAmenities();
    });

    $('#slider-min').on('input', function () {
        let min = $(this).val();
        $('#slider-min-value').text(min);
    });

    $('#slider-max').on('input', function () {
        let max = $(this).val();
        $('#slider-max-value').text(max);
    });

    $('[name="property_type"]').change(function () {
        let propertyType = $(this).val();
        let subTypes = propertySubTypes[propertyType];
        let html = '';
        for (let key in subTypes) {
            html += `<div class="clickTo">
                        <label class="checkbox">
                            <input class="checkbox__input" type="checkbox" name="property_sub_types[]" class="keyword-checkbox" value="${key}">
                            <span class="checkbox__label">${subTypes[key]}</span>
                        </label>
                    </div>`;
        }
        $('#property-sub-type').html(html);

        $('#bhk-filter').addClass('d-none');
    });

    $(document).on('change', '[name="property_sub_types[]"]', function () {
        let allowed = ['flat', 'house', 'bungalow', 'villa'];
        let checkedBoxes = $('[name="property_sub_types[]"]:checked');
        let showBhk = false;

        checkedBoxes.each(function () {
            if (allowed.includes($(this).val())) {
                showBhk = true;
            }
        });

        if (showBhk) {
            $('#bhk-filter').removeClass('d-none');
        } else {
            $('#bhk-filter').addClass('d-none');
        }
    });

    $(document).on('click', '.propertyCard', function () {
        if ($(this).hasClass('isOnMap')) {
            return;
        }

        let id = $(this).data('id');
        $.ajax({
            url: singleProjectUrl,
            type: 'GET',
            data: { id: id },
            success: function (response) {
                showPropertyDetails(response.data);
                $('#propertyModal').modal('show');
            }
        });
    });
});

modelOpacityAdd('share_property', 'propertyModal');
modelOpacityRemove('share_property', 'propertyModal');

function loadMoreAmenities() {
    console.log('loadMoreAmenities');
    $('#moreAmenities').addClass('d-none');
    $('#lessAmenities').removeClass('d-none');
    console.log($('.hidden-amenity'));
    $('.hidden-amenity').removeClass('d-none');
}

function loadLessAmenities() {
    console.log('loadLessAmenities');
    $('#moreAmenities').removeClass('d-none');
    $('#lessAmenities').addClass('d-none');
    console.log($('.hidden-amenity'));
    $('.hidden-amenity').addClass('d-none');
}

function showPropertyDetails(project) {
    console.log(project);
    let whatsApp = $('#whatsapplink').data('whatsapp-number');

    $('.show_price_from_to').text(
        project.price_from && project.price_to
            ? `₹${project.price_from}L - ${project.price_to}Cr`
            : 'Unknown'
    );

    $('.show_project_name').text(project.project_name || 'Unknown');

    $('.show_custom_property_type').text(project.custom_property_type || 'Unknown');

    $('.show_location').text(
        project.city?.city_name && project.location?.location_name
            ? `${project.city.city_name}, ${project.location.location_name}`
            : 'Unknown'
    );

    $('.show_description').text(
        project.project_about
            ? project.project_about.replace(/(<([^>]+)>)/gi, "").trim()
            : 'No description available'
    );

    $('.show_total_floors').text(
        project.total_floors
            ? `${project.total_floors} Floors`
            : 'Unknown'
    );

    $('.show_total_tower').text(project.total_tower || 'Unknown');

    $('.show_age_of_construction').text(project.age_of_construction || 'Unknown');

    $('.show_property_type').text(project.property_type || 'Unknown');

    let html = '';
    project.project_details.forEach(function (detail) {
        html += `<div class="overBox">
                    <span>${detail.name}</span>
                    <h6 class="show_${detail.name}">${detail.value}</h6>
                </div>`;
    });

    $('#overViewBox').append(html);

    $('#heartIconFill').attr('data-id', project.id);

    if (project.is_wishlisted) {
        $('#heartIconFill').removeClass('fa-regular');
        $('#heartIconFill').addClass('fa-solid');
    } else {
        $('#heartIconFill').removeClass('fa-solid');
        $('#heartIconFill').addClass('fa-regular');
    }
    $('.moredetails').attr({
        'href': propertyDetailsUrl.replace(':slug', project.slug),
        'target': '_blank'
    });

    $('#whatsapplink').attr({
        'href': `https://wa.me/${whatsApp}?text=${encodeURIComponent(propertyDetailsUrl.replace(':slug', project.slug))}`
    });

    updateShareLinks(propertyDetailsUrl.replace(':slug', project.slug));

    $('.show_video_url').attr('src', project.video || '');
    let galleryHtml = '';
    if (project.project_images && project.project_images.length > 0) {
        project.project_images.forEach((image, index) => {
            galleryHtml += `<div class="box comImg">
                                <img src="${image.image}" alt="boxImg${index + 1}">
                            </div>`;
        });
    }

    $('.show_gallery_images').html(galleryHtml);
}

// function updateShareLinks(url) {
//     // Get current page URL
//     const currentPageURL = url;

//     const subject = encodeURIComponent("Check out this property!");
//     const body = encodeURIComponent("I found this property and thought you might be interested:\n\n" + currentPageURL);

//     document.getElementById('whatsapp-link').setAttribute('data-href', `https://api.whatsapp.com/send?text=${encodeURIComponent(currentPageURL)}`);
//     document.getElementById('facebook-link').setAttribute('data-href', `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentPageURL)}`);
//     document.getElementById('twitter-link').setAttribute('data-href', `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentPageURL)}`);
//     document.getElementById('linkedin-link').setAttribute('data-href', `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentPageURL)}`);
//     // document.getElementById('email-link').setAttribute('data-href', `mailto:?subject=${subject}&body=${body}`);
//     // document.getElementById('pinterest-link').href = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(currentPageURL)}`;

//     // Set the copy link value
//     document.getElementById('copy-link').value = currentPageURL;
// }

// $('body').on('click', '.social_media_share', function (event) {
//     var url = $(this).attr('data-href');
//     var left = (screen.width - 600) / 2;
//     var top = (screen.height - 400) / 2;
//     window.open(url, '_blank', 'width=600,height=400,left=' + left + ',top=' + top);
// });


// function copyToClipboard() {
//     var copyText = document.getElementById('copy-link');
//     copyText.removeAttribute("disabled");
//     copyText.select();
//     copyText.setSelectionRange(0, 99999);
//     document.execCommand('copy');
//     if (typeof toastr !== 'undefined') {
//         toastr.success('Link copied!');
//         copyText.setAttribute("disabled", "true");
//     } else {
//         alert('Link copied!');
//         copyText.setAttribute("disabled", "true");
//     }
// }
