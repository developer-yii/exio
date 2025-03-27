$(document).ready(function () {
    let sliderMax = $('#slider-max').val();
    let sliderMin = $('#slider-min').val();
    let urlSearch = getUrlParam('search'); 

    if(urlSearch){
        $('#filter_search').val(decodeURIComponent(urlSearch));
    }
    
    let propertyType = $('[name="property_type"]:checked').val();
    let subTypes_o = propertySubTypes[propertyType];

    const amenities = document.querySelectorAll(".amenity-item");
    filterAmenities(propertyType);
    function filterAmenities(selectedType) {
        amenities.forEach(amenity => {
            let amenityType = amenity.dataset.propertyType;

            // Show only amenities that match the selected property type
            if (amenityType === "both" || amenityType === selectedType) {
                amenity.style.display = "block";
            } else {
                amenity.style.display = "none";
            }
        });
    }


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

        filterAmenities(propertyType);
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
    const name = $(this).data('name');    
    // $('.clickList').val(name);
    $('#filter_search').val(name); 
    $(this).closest('.search-key').addClass('d-none');
});

