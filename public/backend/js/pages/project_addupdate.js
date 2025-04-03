$('#area_id').select2({
    placeholder: 'Select Area',
    allowClear: true
});

// $('#property_sub_types').select2({
//     placeholder: 'Select Property Sub Type',
//     allowClear: true,
//     // multiple: true,
//     // dropdownAutoWidth: true,
//     // width: '100%',
//     // closeOnSelect: false // Ensure dropdown does not close on selection
// });

$('#city_id').change(function () {
    var cityId = $(this).val();
    $('#area_id').empty().append('<option value="">Select Area</option>'); // Reset area dropdown

    if (cityId) {
        $.ajax({
            url: getAreaUrl,
            type: "GET",
            data: { city_id: cityId },
            success: function (response) {
                $.each(response, function (key, value) {
                    $('#area_id').append('<option value="' + key + '">' + value + '</option>');
                });

                // Select the existing area in edit mode
                if (selectedArea) {
                    $('#area_id').val(selectedArea).trigger('change');
                }

                $('#area_id').select2(); // Re-initialize Select2
            }
        });
    }
});

$(document).ready(function () {  
    
    if (selectedCity) {
        $('#city_id').trigger('change');
    }    
    
    initializeCKEditor("project_about", 300, "project_images");

    let formId = "#add-form";
    let addFormBtnId = "#addorUpdateBtn";
    let propertyTypeValue = $("input[name='property_type']:checked").val();
    let propertySubTypes = [];

    $(formId).submit(function (event) {
        event.preventDefault();
        var $this = $(this);

        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        var formData = new FormData(this);

        $(formId).find(".error").html("");
        $(formId).find(".is-invalid").removeClass("is-invalid");

        // if (quill) {
        //     formData.append("project_about", quill.root.innerHTML);
        // }

        $.ajax({
            url: addUpdateUrl,
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $($this).find('button[type="submit"]').prop("disabled", true);
                $($this).find('button[type="submit"]').html("Saving...");
            },
            success: function (result) {
                $($this).find('button[type="submit"]').prop("disabled", false);
                $($this).find('button[type="submit"]').html("Save");

                if (result.status == true) {
                    showToastMessage("success", result.message);
                    setTimeout(function () {
                        window.location.href = projectUrl;
                    }, 1000);
                } else if (result.status == false && result.message) {
                    showToastMessage("error", result.message);
                } else {
                    if (result.errors) {
                        first_input = "";
                        $.each(result.errors, function (key, value) {
                            // Handle nested validation keys (for arrays)
                            if (key.includes(".")) {
                                let parts = key.split(".");
                                key = `${parts[0]}_${parts[1]}_${parts[2]}`;
                            }

                            if (first_input == "") first_input = key;

                            // Add invalid class
                            $(formId)
                                .find("." + key)
                                .addClass("is-invalid");

                            // Show error message
                            let errorContainer = $(formId)
                                .find("." + key)
                                .closest(".form-group")
                                .find(".error");
                            errorContainer.html(value[0]); // Display first error message
                        });

                        // Focus first error field
                        if (first_input) {
                            $(formId)
                                .find("." + first_input)
                                .focus();
                        }
                    }
                }
            },
            error: function (error) {
                alert("Something went wrong!");
                // location.reload();
            },
        });
    });

    $(document).on("change", "input[name='property_type']", function () {
        getPropertySubTypes($(this).val());
        getPropertyAmenities($(this).val());
    });

    $(document).on("change", "input[name='appraisal_property']", function () {
        if ($(this).val() == "yes") {
            $("#appraisal_property_yes").prop("checked", true);
            $("#appraisal_property_no").prop("checked", false);
        } else {
            $("#appraisal_property_yes").prop("checked", false);
            $("#appraisal_property_no").prop("checked", true);
        }
    });

    if (propertyTypeValue && propertyTypeValue != "") {
        // console.log(selectedAmenities);
        getPropertySubTypes(propertyTypeValue, selectedPropertySubTypes);
        getPropertyAmenities(propertyTypeValue, selectedAmenities);
    }

    function getPropertySubTypes(property_type, selectedPropertySubTypes = "") {
        $.ajax({
            url: getPropertySubTypesUrl,
            type: "GET",
            data: { property_type: property_type },
            success: function (result) {
                if (result.status == true) {
                    var propertySubTypes = result.data;
                    var html = "";
    
                    // Convert to array if stored as a string
                    selectedPropertySubTypes = (typeof selectedPropertySubTypes === "string") 
                    ? selectedPropertySubTypes.split(",").map(item => item.trim()) 
                    : selectedPropertySubTypes;

                    let options = Object.entries(propertySubTypes).map(([key, value]) => 
                        `<option value="${key}" ${selectedPropertySubTypes.includes(key) ? "selected" : ""}>${value}</option>`
                    ).join("");

                    $("#property_sub_types").html(options).select2().trigger("change");
                }
            },
            error: function () {
                alert("Something went wrong!");
            },
        });
    }


    function getPropertyAmenities(property_type, selectedAmenities = "") {
        $.ajax({
            url: getAmenitiesUrl,
            type: "GET",
            data: { property_type: property_type },
            success: function (result) {
                if (result.status == true) {
                    var propertySubTypes = result.data;
                    var html = "";
    
                    // Convert to array if stored as a string
                    if (typeof selectedAmenities === "string") {
                        selectedAmenities = selectedAmenities.split(",").map(item => item.trim());
                    }
    
                    $.each(propertySubTypes, function (key, value) {
                        let selected = selectedAmenities.includes(key) ? "selected" : "";
                        html += `<option value="${key}" ${selected}>${value}</option>`;
                    });
    
                    $("#amenities").html(html);
                    $("#amenities").select2(); 
    
                    setTimeout(function () {
                        $("#amenities").html(html).trigger("change"); 
                        // $("#property_sub_types").val(selectedAmenities).trigger('change');
                    }, 500);
                }
            },
            error: function () {
                alert("Something went wrong!");
            },
        });
    }
    
    // $("#calculateBtn").click(function () {
    //     let sections = {
    //         "section-a": ".amenities_percentage",
    //         "section-b": ".project_plan_percentage",
    //         "section-c": ".locality_percentage",
    //         "section-d": ".return_of_investment_percentage"
    //     };
    
    //     Object.keys(sections).forEach(function (section) {
    //         let totalPercentage = 0;
    
    //         $(`input[data-section="${section}"]`).each(function () {
    //             let point = parseFloat($(this).val());
    //             let weightage = parseFloat($(this).data("weightage")) || 0;
    //             let errorSpan = $(this).siblings(".error");
    
    //             // Reset error message
    //             errorSpan.text("");
    
    //             // Validate input (0-100)
    //             if (isNaN(point) || point < 0 || point > 100) {
    //                 errorSpan.text("Point must be between 0 and 100.");
    //                 $(this).val(""); // Clear invalid input
    //                 return;
    //             }
    
    //             // Calculate percentage (point * weightage / 100)
    //             let percentage = (point * weightage) / 100;
    //             totalPercentage += percentage;
    
    //             // Update UI
    //             $(this).closest(".form-group").find(".percentage").text(percentage.toFixed(2));
    //         });
    
    //         // Round total percentage
    //         let roundedPercentage = Math.round(totalPercentage);
    
    //         // Update total percentage per section
    //         $(`.total-percentage[data-section="${section}"]`).text(totalPercentage.toFixed(2));
    
    //         // Update related input field
    //         let inputField = $(sections[section]);
    //         inputField.val(roundedPercentage);
    
    //         // Update range slider properly
    //         if (inputField.attr("data-plugin") === "range-slider") {
    //             console.log(`Updating slider: ${sections[section]} to ${roundedPercentage}`);
            
    //             inputField.val(roundedPercentage); // Set value
            
    //             // Manually trigger events for the slider to update UI
    //             inputField.trigger("input").trigger("change");
    //             inputField[0].dispatchEvent(new Event("input", { bubbles: true }));
    //             inputField[0].dispatchEvent(new Event("change", { bubbles: true }));
    //         }
            
    //     });
    // });   
    
});

$("#calculateBtn").click(function () {
    let sections = {
        "section-a": ".amenities_percentage",
        "section-b": ".project_plan_percentage",
        "section-c": ".locality_percentage",
        "section-d": ".return_of_investment_percentage"
    };

    let grandTotalPercentage = 0; // ✅ Track total percentage of all sections

    Object.keys(sections).forEach(function (section) {
        let totalPercentage = 0;

        $(`input[data-section="${section}"]`).each(function () {
            let point = parseFloat($(this).val());
            let weightage = parseFloat($(this).data("weightage")) || 0;
            let errorSpan = $(this).siblings(".error");

            // Reset error message
            errorSpan.text("");

            // Validate input (0-100)
            if (isNaN(point) || point < 0 || point > 100) {
                errorSpan.text("Point must be between 0 and 100.");
                $(this).val(""); // Clear invalid input
                return;
            }

            // Calculate percentage (point * weightage / 100)
            let percentage = (point * weightage) / 100;
            totalPercentage += percentage;
            
        });

        // Round total percentage
        let roundedPercentage = Math.round(totalPercentage);

        // Add to grand total
        grandTotalPercentage += roundedPercentage;

        // Update total percentage per section
        $(`.total-percentage[data-section="${section}"]`).text(totalPercentage.toFixed(2));

        // Update related input field
        let inputField = $(sections[section]);
        inputField.val(roundedPercentage);

        // Update the Ion.RangeSlider properly
        let sliderInstance = inputField.data("ionRangeSlider");
        if (sliderInstance) {
            sliderInstance.update({ from: roundedPercentage });
        }
    });

    // Update Exio Suggest (%) with grand total
    let exioField = $(".exio_suggest_percentage");
    var exioPer = grandTotalPercentage/4;
    exioField.val(exioPer);

    // Update Exio Suggest (%) slider
    let exioSlider = exioField.data("ionRangeSlider");
    if (exioSlider) {
        exioSlider.update({ from: exioPer });
    }    
});
