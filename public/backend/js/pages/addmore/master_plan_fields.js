$(document).ready(function () {
    let masterPlanIndex = 0;

    function previewImage(input, previewElement) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.attr('src', e.target.result);
                previewElement.parent().show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addNewRow(data = null) {
        let newRow = `
            <div class="col-md-6 mb-3 master-plan-item">
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
                                <input type="file" name="master_plan[${masterPlanIndex}][2d_image]"
                                    class="form-control master_plan_${masterPlanIndex}_2d_image"
                                    accept="image/*">
                                <div class="mt-2 image-preview" style="display: ${data && data['2d_image'] ? 'block' : 'none'}">
                                    <img src="${data ? '/storage/master_plan/2d_image/' + data['2d_image'] : ''}"
                                         class="img-fluid" style="max-height: 150px" alt="2D Preview">
                                </div>
                                <span class="error"></span>
                            </div>

                            <!-- 3D Image -->
                            <div class="form-group mb-3">
                                <label class="form-label">3D Image</label>
                                <input type="file" name="master_plan[${masterPlanIndex}][3d_image]"
                                    class="form-control master_plan_${masterPlanIndex}_3d_image"
                                    accept="image/*">
                                <div class="mt-2 image-preview" style="display: ${data && data['3d_image'] ? 'block' : 'none'}">
                                    <img src="${data ? '/storage/master_plan/3d_image/' + data['3d_image'] : ''}"
                                         class="img-fluid" style="max-height: 150px" alt="3D Preview">
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
        $(".add-more-master-plan").closest(".col-md-6").before(newRow);

        // Add event listeners for image preview
        $(`.master_plan_${masterPlanIndex}_2d_image`).on('change', function() {
            previewImage(this, $(this).siblings('.image-preview').find('img'));
        });

        $(`.master_plan_${masterPlanIndex}_3d_image`).on('change', function() {
            previewImage(this, $(this).siblings('.image-preview').find('img'));
        });

        masterPlanIndex++;
    }

    // Initialize existing master plans if any
    if (typeof existingMasterPlans !== "undefined" && existingMasterPlans.length > 0) {
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
        reindexMasterPlans();
    });

    function reindexMasterPlans() {
        $(".master-plan-item").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `master_plan[${index}][id]`);
            $(this)
                .find('input[type="text"]')
                .attr("name", `master_plan[${index}][name]`)
                .removeClass()
                .addClass(`form-control master_plan_${index}_name`);
            $(this)
                .find('input[type="file"]')
                .first()
                .attr("name", `master_plan[${index}][2d_image]`)
                .removeClass()
                .addClass(`form-control master_plan_${index}_2d_image`);
            $(this)
                .find('input[type="file"]')
                .last()
                .attr("name", `master_plan[${index}][3d_image]`)
                .removeClass()
                .addClass(`form-control master_plan_${index}_3d_image`);
        });
    }
});
