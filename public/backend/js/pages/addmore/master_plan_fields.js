$(document).ready(function () {
    let masterPlanIndex = 0;

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
            <div class="col-md-4 mb-3 master-plan-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="master_plan[${masterPlanIndex}][id]" value="${
            data ? data.id : ""
        }">

                            <!-- Name -->
                            <div class="form-group mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="master_plan[${masterPlanIndex}][name]"
                                    class="form-control master_plan_${masterPlanIndex}_name"
                                    placeholder="Enter Title"
                                    value="${data ? data.name : ""}">
                                <span class="error"></span>
                            </div>

                            <!-- 2D Image -->
                            <div class="form-group mb-3">
                                <label class="form-label">2D Image</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" name="master_plan[${masterPlanIndex}][2d_image]"
                                            class="form-control master_plan_${masterPlanIndex}_2d_image"
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
                                                      "storage/master_plan/2d_image/" +
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
                                        <input type="file" name="master_plan[${masterPlanIndex}][3d_image]"
                                            class="form-control master_plan_${masterPlanIndex}_3d_image"
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
                                                      "storage/master_plan/3d_image/" +
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
                                <button type="button" class="btn btn-danger remove-master-plan float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert before the add button
        $(".add-more-master-plan").closest(".col-md-4").before(newRow);

        // Add event listeners for image preview
        $(`.master_plan_${masterPlanIndex}_2d_image`).on("change", function () {
            previewImage(
                this,
                $(this).closest(".form-group").find(".image-preview img")
            );
            $(this).closest(".form-group").find(".image-preview").show();
        });

        $(`.master_plan_${masterPlanIndex}_3d_image`).on("change", function () {
            previewImage(
                this,
                $(this).closest(".form-group").find(".image-preview img")
            );
            $(this).closest(".form-group").find(".image-preview").show();
        });

        masterPlanIndex++;
    }

    // Initialize existing master plans if any
    if (
        typeof existingMasterPlans !== "undefined" &&
        existingMasterPlans.length > 0
    ) {
        existingMasterPlans.forEach(function (masterPlan) {
            addNewRow(masterPlan);
        });
    } else {
        // Add one empty row by default
        addNewRow();
    }

    $(document).on("click", ".add-more-master-plan", function () {
        addNewRow();
    });

    $(document).on("click", ".remove-master-plan", function () {
        $(this).closest(".master-plan-item").remove();
    });
});
