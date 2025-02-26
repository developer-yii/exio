$(document).ready(function () {
    let projectImageIndex = 0;

    function previewImage(input, previewElement) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewElement.attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }

    function addNewImageRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 project-image-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="project_images[${projectImageIndex}][id]" value="${
            data ? data.id : ""
        }">
                            <div class="form-group mb-3">
                                <label class="form-label">Image</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" name="project_images[${projectImageIndex}][image]" class="form-control project_images_${projectImageIndex}_image" accept="image/*">
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
                                                      "storage/project_images/" +
                                                      data.image
                                                    : ""
                                            }" class="img-fluid hover-image" style="max-height: 38px; max-width: 100%;" alt="Image Preview">
                                        </div>
                                    </div>
                                </div>
                                <span class="error"></span>
                            </div>
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input type="checkbox"
                                        class="form-check-input cover-image-checkbox"
                                        name="project_images[${projectImageIndex}][is_cover]"
                                        id="coverImage_${projectImageIndex}"
                                        ${
                                            data && data.is_cover == 1
                                                ? "checked"
                                                : ""
                                        }>
                                    <label class="form-check-label" for="coverImage_${projectImageIndex}">
                                        Set as Cover Image
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-project-image float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $(".add-more-project-image").closest(".col-md-4").before(newRow);

        $(`.project_images_${projectImageIndex}_image`).on(
            "change",
            function () {
                previewImage(
                    this,
                    $(this).closest(".form-group").find(".image-preview img")
                );
                $(this).closest(".form-group").find(".image-preview").show();
            }
        );

        $(`#coverImage_${projectImageIndex}`).on("change", function () {
            if ($(this).is(":checked")) {
                $(".cover-image-checkbox").not(this).prop("checked", false);
            }
        });
        projectImageIndex++;
    }

    if (
        typeof existingProjectImages !== "undefined" &&
        existingProjectImages.length > 0
    ) {
        existingProjectImages.forEach(function (projectImage) {
            addNewImageRow(projectImage);
        });
    } else {
        addNewImageRow();
    }

    $(document).on("click", ".add-more-project-image", function () {
        addNewImageRow();
    });

    $(document).on("click", ".remove-project-image", function () {
        $(this).closest(".project-image-item").remove();
        reindexProjectImages();
    });

    function reindexProjectImages() {
        $(".project-image-item").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `project_images[${index}][id]`);
            $(this)
                .find('input[type="file"]')
                .attr("name", `project_images[${index}][image]`)
                .removeClass()
                .addClass(`form-control project_images_${index}_image`);
            $(this)
                .find(".cover-image-checkbox")
                .attr("name", `project_images[${index}][is_cover]`)
                .attr("id", `coverImage_${index}`);
            $(this)
                .find(".form-check-label")
                .attr("for", `coverImage_${index}`);
        });
    }
});
