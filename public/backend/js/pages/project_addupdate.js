$(document).ready(function () {
    let quill = "";
    if ($("#project_about").length > 0) {
        quill = new Quill("#project_about", {
            theme: "snow",
            modules: {
                imageResize: {
                    displaySize: true,
                },
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ color: [] }, { background: [] }],
                    [{ script: "super" }, { script: "sub" }],
                    [
                        { header: [!1, 1, 2, 3, 4, 5, 6] },
                        "blockquote",
                        "code-block",
                    ],
                    [
                        { list: "ordered" },
                        { list: "bullet" },
                        { indent: "-1" },
                        { indent: "+1" },
                    ],
                    ["direction", { align: [] }],
                    ["link", "image"],
                    ["clean"],
                ],
            },
        });

        quill.getModule("toolbar").addHandler("image", () => {
            const input = document.createElement("input");
            input.setAttribute("type", "file");
            input.setAttribute("accept", "image/*");
            input.click();

            input.onchange = () => {
                const file = input.files[0];
                // Validate file
                if (!file.type.match(/^image\/(jpeg|png|gif)$/)) {
                    alert("Invalid file type. Please select an image file.");
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    // 2MB limit
                    alert("File is too large. Maximum size is 2MB.");
                    return;
                }
                const reader = new FileReader();
                reader.onload = () => {
                    const base64Image = reader.result;
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, "image", base64Image);
                };
                reader.readAsDataURL(file);
            };
        });
    }

    let formId = "#add-form";
    let addFormBtnId = "#addorUpdateBtn";
    let propertyType = $("input[name='property_type']");
    let propertyTypeValue = propertyType.val();
    let propertySubTypes = [];

    $(formId).submit(function (event) {
        console.log("Project Add Update");
        event.preventDefault();
        var $this = $(this);
        var formData = new FormData(this);

        $(formId).find(".error").html("");
        $(formId).find(".is-invalid").removeClass("is-invalid");

        if (quill) {
            formData.append("project_about", quill.root.innerHTML);
        }

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
    });

    if (propertyTypeValue && propertyTypeValue != "") {
        getPropertySubTypes(propertyTypeValue);
    }

    function getPropertySubTypes(property_type) {
        $.ajax({
            url: getPropertySubTypesUrl,
            type: "GET",
            data: { property_type: property_type },
            success: function (result) {
                if (result.status == true) {
                    var propertySubTypes = result.data;
                    var html =
                        "<option value=''>Select Property Sub Type</option>";
                    $.each(propertySubTypes, function (index, propertySubType) {
                        let isSelected =
                            typeof selectedPropertySubTypes !== "undefined" &&
                            selectedPropertySubTypes.includes(index);
                        let selected = isSelected ? "selected" : "";

                        html += `<option value="${index}" ${selected}>${propertySubType}</option>`;
                    });
                    $("#property_sub_types").html(html);
                }
            },
            error: function (error) {
                alert("Something went wrong!");
                // location.reload();
            },
        });
    }
});
