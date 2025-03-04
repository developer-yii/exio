$(document).ready(function () {
    // Cache DOM elements
    const $propertySelectionModal = $("#lookingProperty");
    const $squareFootageModal = $("#feetRequired");
    const $citySelectionModal = $("#cityselect");
    const $budgetsModal = $("#budgets");
    const $amenitiesModal = $("#amenities");

    // Cache buttons
    const $propertyTypeNextBtn = $("#nextLookingPropertyButton");
    const $squareFootageNextBtn = $("#sqftNextButton");
    const $cityNextBtn = $("#cityNextButton");
    const $budgetsNextBtn = $("#budgetsNextButton");
    const $amenitiesNextBtn = $("#amenitiesNextButton");
    const $budgetsPrevBtn = $("#budgetsPrevButton");
    const $amenitiesPrevBtn = $("#amenitiesPrevButton");
    const $cityPrevBtn = $("#cityPrevButton");
    const $squareFootagePrevBtn = $("#sqftPrevButton");

    $propertyTypeNextBtn.click(function () {
        $propertySelectionModal.modal("hide");
        $squareFootageModal.modal("show");
    });

    $squareFootageNextBtn.click(function () {
        $squareFootageModal.modal("hide");
        $citySelectionModal.modal("show");
    });

    $cityNextBtn.click(function () {
        $citySelectionModal.modal("hide");

        const propertyType = $('input[name="property_type"]:checked').val();

        $.ajax({
            url: getAmenitiesUrl,
            type: "GET",
            data: { amenity_type: propertyType },
            success: function (response) {
                console.log(response);
                const amenitiesList = $("#amenitiesList");
                amenitiesList.empty();

                const amenities = response.data;
                const initialAmenities = amenities.slice(0, 7);
                const remainingAmenities = amenities.slice(7);

                // Add initial amenities
                initialAmenities.forEach((amenity) => {
                    amenitiesList.append(`
                        <div class="clickTo">
                            <label class="checkbox">
                                <input class="checkbox__input" type="checkbox" name="amenities[]" id="amenities_${amenity.id}" value="${amenity.id}" />
                                <span class="checkbox__label"> ${amenity.amenity_name}</span>
                            </label>
                        </div>
                    `);
                });

                // Add "Show More" button and remaining amenities if there are more than 7
                if (remainingAmenities.length > 0) {
                    const remainingAmenitiesHtml = remainingAmenities
                        .map(
                            (amenity) => `
                        <div class="clickTo hidden-amenity" style="display: none;">
                            <label class="checkbox">
                                <input class="checkbox__input" type="checkbox" name="amenities[]" value="${amenity.id}" id="amenities_${amenity.id}" />
                                <span class="checkbox__label"> ${amenity.amenity_name}</span>
                            </label>
                        </div>
                    `
                        )
                        .join("");

                    amenitiesList.append(remainingAmenitiesHtml);
                    amenitiesList.append(`
                        <div class="clickTo" id="amenitiesToggleContainer">
                            <a href="javascript:void(0)" id="showMoreAmenities">+ more</a>
                        </div>
                    `);

                    // Improved toggle functionality
                    $(document).on("click", "#showMoreAmenities", function () {
                        $("#amenitiesToggleContainer").html(`
                            <a href="javascript:void(0)" id="showLessAmenities">- less</a>
                        `);
                        $(".hidden-amenity").slideDown(100);
                    });

                    $(document).on("click", "#showLessAmenities", function () {
                        $("#amenitiesToggleContainer").html(`
                            <a href="javascript:void(0)" id="showMoreAmenities">+ more</a>
                        `);
                        $(".hidden-amenity").slideUp(100);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
        });

        $amenitiesModal.modal("show");
    });

    $amenitiesNextBtn.click(function () {
        $amenitiesModal.modal("hide");
        $budgetsModal.modal("show");
    });

    $budgetsNextBtn.click(function () {
        $budgetsModal.modal("hide");
        const propertyType = $('input[name="property_type"]:checked').val();
        const sqft = $('input[name="sqft_options[]"]:checked')
            .map(function () {
                return $(this).val();
            })
            .get();
        const city = $('select[name="city"]').val();
        const location = $('select[name="location"]').val();
        const amenities = $('input[name="amenities[]"]:checked')
            .map(function () {
                return $(this).val();
            })
            .get();
        const budget = $('input[name="budgets[]"]:checked')
            .map(function () {
                return $(this).val();
            })
            .get();

        const budgetString = budget.join(",");
        const sqftString = sqft.join(",");
        const amenitiesString = amenities.join(",");

        window.location.href =
            checkAndMatchPropertyResultUrl +
            `?property_type=${encodeURIComponent(
                propertyType
            )}&sqft=${sqftString}&city=${encodeURIComponent(
                city
            )}&location=${encodeURIComponent(
                location
            )}&amenities=${encodeURIComponent(
                amenitiesString
            )}&budget=${encodeURIComponent(budgetString)}`;
    });

    $squareFootagePrevBtn.click(function () {
        $squareFootageModal.modal("hide");
        $propertySelectionModal.modal("show");
    });

    $cityPrevBtn.click(function () {
        $citySelectionModal.modal("hide");
        $squareFootageModal.modal("show");
    });

    $amenitiesPrevBtn.click(function () {
        $amenitiesModal.modal("hide");
        $citySelectionModal.modal("show");
    });

    $budgetsPrevBtn.click(function () {
        $budgetsModal.modal("hide");
        $amenitiesModal.modal("show");
    });
});
