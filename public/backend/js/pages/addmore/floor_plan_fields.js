$(document).ready(function () {
    let floorPlanIndex = 0;

    function previewImage(input, previewElement) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewElement.attr("src", e.target.result);
                previewElement.parent().show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addNewRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 floor-plan-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="floor_plan[${floorPlanIndex}][id]" value="${
            data ? data.id : ""
        }">

                            <!-- Carpet Area -->
                            <div class="form-group mb-3">
                                <label class="form-label">Carpet Area (sqft)</label>
                                <input type="text" name="floor_plan[${floorPlanIndex}][carpet_area]"
                                    class="form-control floor_plan_${floorPlanIndex}_carpet_area"
                                    placeholder="Enter Carpet Area"
                                    value="${data ? data.carpet_area : ""}">
                                <span class="error"></span>
                            </div>

                            <!-- Type -->
                            <div class="form-group mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" name="floor_plan[${floorPlanIndex}][type]"
                                    class="form-control floor_plan_${floorPlanIndex}_type"
                                    placeholder="i.e. 1BHK, 2BHK, etc."
                                    value="${data ? data.type : ""}">
                                <span class="error"></span>
                            </div>

                            <!-- 2D Image -->
                            <div class="form-group mb-3">
                                <label class="form-label">2D Image</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" name="floor_plan[${floorPlanIndex}][2d_image]"
                                            class="form-control floor_plan_${floorPlanIndex}_2d_image"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="image-preview" style="display: ${
                                            data && data["2d_image"]
                                                ? "block"
                                                : "none"
                                        }">
                                            <img src="${
                                                data
                                                    ? assetUrl +
                                                      "storage/floor_plan/2d_image/" +
                                                      data["2d_image"]
                                                    : ""
                                            }"
                                                class="img-fluid hover-image" style="max-height: 38px; max-width: 100%;" alt="2D Preview">
                                        </div>
                                    </div>
                                </div>
                                <span class="error"></span>
                            </div>

                            <!-- 3D Image -->
                            <div class="form-group mb-3">
                                <label class="form-label">3D Image</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" name="floor_plan[${floorPlanIndex}][3d_image]"
                                            class="form-control floor_plan_${floorPlanIndex}_3d_image"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="image-preview" style="display: ${
                                            data && data["3d_image"]
                                                ? "block"
                                                : "none"
                                        }">
                                            <img src="${
                                                data
                                                    ? assetUrl +
                                                      "storage/floor_plan/3d_image/" +
                                                      data["3d_image"]
                                                    : ""
                                            }"
                                                class="img-fluid hover-image" style="max-height: 38px; max-width: 100%;" alt="3D Preview">
                                        </div>
                                    </div>
                                </div>
                                <span class="error"></span>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-floor-plan float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert before the add button
        $(".add-more-floor-plan").closest(".col-md-4").before(newRow);

        // Add event listeners for image preview
        $(`.floor_plan_${floorPlanIndex}_2d_image`).on("change", function () {
            previewImage(
                this,
                $(this).closest(".form-group").find(".image-preview img")
            );
            $(this).closest(".form-group").find(".image-preview").show();
        });

        $(`.floor_plan_${floorPlanIndex}_3d_image`).on("change", function () {
            previewImage(
                this,
                $(this).closest(".form-group").find(".image-preview img")
            );
            $(this).closest(".form-group").find(".image-preview").show();
        });

        floorPlanIndex++;
    }

    // Initialize existing floor plans if any
    if (
        typeof existingFloorPlans !== "undefined" &&
        existingFloorPlans.length > 0
    ) {
        existingFloorPlans.forEach(function (floorPlan) {
            addNewRow(floorPlan);
        });
    } else {
        // Add one empty row by default
        addNewRow();
    }

    $(document).on("click", ".add-more-floor-plan", function () {
        addNewRow();
    });

    $(document).on("click", ".remove-floor-plan", function () {
        $(this).closest(".floor-plan-item").remove();
    });
});
