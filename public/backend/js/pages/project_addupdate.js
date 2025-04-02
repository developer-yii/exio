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
        console.log(selectedAmenities);
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
    
    
    
    

   
});
