$(document).ready(function () {
    let actualProgressImageIndex = 0;

    function previewImage(input, previewElement) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewElement.attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }

    function addNewImageRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 actual-progress-image-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="actual_progress_images[${actualProgressImageIndex}][id]" value="${
            data ? data.id : ""
        }">
                            <div class="form-group mb-3">
                                <label class="form-label">Image</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" name="actual_progress_images[${actualProgressImageIndex}][image]" class="form-control actual_progress_images_${actualProgressImageIndex}_image" accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="image-preview" style="display: ${
                                            data && data.image
                                                ? "block"
                                                : "none"
                                        }">
                                            <img src="${
                                                data
                                                    ? assetUrl +
                                                      "storage/actual_progress_images/" +
                                                      data.image
                                                    : ""
                                            }" class="img-fluid hover-image" style="max-height: 38px; max-width: 100%;" alt="Image Preview">
                                        </div>
                                    </div>
                                </div>
                                <span class="error"></span>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-actual-progress-image float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $(".add-more-actual-progress-image")
            .closest(".col-md-4")
            .before(newRow);

        // Add event listener for image preview
        $(`.actual_progress_images_${actualProgressImageIndex}_image`).on(
            "change",
            function () {
                previewImage(
                    this,
                    $(this).closest(".form-group").find(".image-preview img")
                );
                $(this).closest(".form-group").find(".image-preview").show();
            }
        );

        actualProgressImageIndex++;
    }

    // AJAX call to load existing images
    function loadExistingImages(actualProgressId) {
        $.ajax({
            url: getImagesUrl + "?actual_progress_id=" + actualProgressId,
            dataType: "json",
            method: "GET",
            success: function (response) {
                if (response.status && response.data.length > 0) {
                    // Clear existing rows
                    $(".actual-progress-image-item").remove();
                    response.data.forEach(function (image) {
                        addNewImageRow({
                            id: image.id,
                            image: image.image,
                        });
                    });
                } else {
                    addNewImageRow();
                }
            },
            error: function (xhr) {
                console.error("Error loading images:", xhr);
                addNewImageRow();
            },
        });
    }

    $(document).on("click", ".edit-record", function () {
        let actualProgressId = $(this).data("id");
        loadExistingImages(actualProgressId);
    });

    $(document).on("click", "#add-new-btn", function () {
        $(".actual-progress-image-item").remove();
        addNewImageRow();
    });

    $(document).on("click", ".add-more-actual-progress-image", function () {
        addNewImageRow();
    });

    $(document).on("click", ".remove-actual-progress-image", function () {
        $(this).closest(".actual-progress-image-item").remove();
        reindexActualProgressImages();
    });

    function reindexActualProgressImages() {
        $(".actual-progress-image-item").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `actual_progress_images[${index}][id]`);
            $(this)
                .find('input[type="file"]')
                .attr("name", `actual_progress_images[${index}][image]`)
                .removeClass()
                .addClass(`form-control actual_progress_images_${index}_image`);
        });
    }
});
