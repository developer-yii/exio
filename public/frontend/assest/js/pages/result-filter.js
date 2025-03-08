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
});

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
