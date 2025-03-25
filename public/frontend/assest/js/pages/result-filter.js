$(document).ready(function () {
    let sliderMax = $('#slider-max').val();
    let sliderMin = $('#slider-min').val();

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
    if (htmlsub) {
        $('#property-sub-type').html(htmlsub);
        $('.show-sub-type').removeClass('d-none');
    }

    $('#slider-min-value').text(formatBudget(sliderMin));
    $('#slider-max-value').text(formatBudget(sliderMax));

    $('#moreAmenities').click(function () {
        loadMoreAmenities();
    });

    $('#lessAmenities').click(function () {
        loadLessAmenities();
    });

    $('#slider-min').on('input', function () {
        let min = $(this).val();
        $('#slider-min-value').text(formatBudget(min));
    });

    $('#slider-max').on('input', function () {
        let max = $(this).val();
        $('#slider-max-value').text(formatBudget(max));
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
        if (html) {
            $('#property-sub-type').html(html);
            $('.show-sub-type').removeClass('d-none');
        }

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
});

modelOpacityAdd('share_property', 'propertyModal');
modelOpacityRemove('share_property', 'propertyModal');

function loadMoreAmenities() {
    // console.log('loadMoreAmenities');
    $('#moreAmenities').addClass('d-none');
    $('#lessAmenities').removeClass('d-none');
    // console.log($('.hidden-amenity'));
    $('.hidden-amenity').removeClass('d-none');
}

function loadLessAmenities() {
    // console.log('loadLessAmenities');
    $('#moreAmenities').removeClass('d-none');
    $('#lessAmenities').addClass('d-none');
    // console.log($('.hidden-amenity'));
    $('.hidden-amenity').addClass('d-none');
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


$('.clickList').on('input', function() {    
    if ($(this).val() === '') {
        $('.search-key').addClass('d-none');
    }
});


$(".clickList").on("keyup", function() {
    handleSearch($(this), '.searchKeyup');
});

function handleSearch($input, parentClass) {
    var value = $input.val().toLowerCase();
    var $searchKey = $input.closest(parentClass).find('.search-key');

    if (value === "") {
        $searchKey.addClass('d-none');
        $searchKey.find('li, h6').show();
    } else {
        $searchKey.removeClass('d-none');
        $searchKey.find('li').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        $searchKey.find('h6').each(function() {
            var $heading = $(this);
            var $lis = $heading.nextUntil('h6', 'li');
            if ($lis.filter(':visible').length === 0) {
                $heading.hide();
            } else {
                $heading.show();
            }
        });
        if ($searchKey.find('li:visible').length === 0) {
            $searchKey.addClass('d-none');
        }
    }
}

$('.search-key a').click(function() {
    const type = $(this).data('type');
    const id = $(this).data('id');
    const name = $(this).data('name');
    const isMobile = $(this).closest('.mobileFilterHero').length > 0;

    if (isMobile) {
        $('.clickListMobile').val(name);
    } else {
        $('.clickList').val(name);
    }

    $(this).closest('.search-key').addClass('d-none');
});

// $('.filter_search').on('input', function() {    
//     if ($(this).val() === '') {
//         $('.search-key').addClass('d-none');
//     }
// });

// $(".filter_search").on("keyup", function() {
//     handleSearch($(this), '.searchBar');
// });

// function handleSearch($input, parentClass) {
//     var value = $input.val().toLowerCase();
//     var $searchKey = $input.closest(parentClass).find('.search-key');

//     if (value === "") {
//         $searchKey.addClass('d-none');
//         $searchKey.find('li, h6').show();
//     } else {
//         $searchKey.removeClass('d-none');
//         $searchKey.find('li').each(function() {
//             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
//         });
//         $searchKey.find('h6').each(function() {
//             var $heading = $(this);
//             var $lis = $heading.nextUntil('h6', 'li');
//             if ($lis.filter(':visible').length === 0) {
//                 $heading.hide();
//             } else {
//                 $heading.show();
//             }
//         });
//         if ($searchKey.find('li:visible').length === 0) {
//             $searchKey.addClass('d-none');
//         }
//     }
// }

// $('.search-key a').click(function() {
//     // const type = $(this).data('type');
//     // const id = $(this).data('id');
//     const name = $(this).data('name');
//     // const isMobile = $(this).closest('.mobileFilterHero').length > 0;

//     // if (isMobile) {
//     //     $('.clickListMobile').val(name);
//     // } 
//     // else {
//         $('.filter_search').val(name);
//     // }

//     $(this).closest('.search-key').addClass('d-none');
// });