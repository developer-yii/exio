$(document).ready(function () {
    let masterPlanIndex = 0;

    $(document).on("click", ".add-more-master-plan", function () {
        masterPlanIndex++;
        let newRow = `
            <div class="row mb-2 master-plan-row">
                <div class="col-md-5">
                    <input type="text" name="master_plan[${masterPlanIndex}][name]" class="form-control master_plan_${masterPlanIndex}_name" placeholder="Enter Title">
                    <span class="error"></span>
                </div>
                <div class="col-md-3">
                    <input type="file" name="master_plan[${masterPlanIndex}][2d_image]" class="form-control master_plan_${masterPlanIndex}_2d_image" accept="image/*">
                    <span class="error"></span>
                </div>
                <div class="col-md-3">
                    <input type="file" name="master_plan[${masterPlanIndex}][3d_image]" class="form-control master_plan_${masterPlanIndex}_3d_image" accept="image/*">
                    <span class="error"></span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-master-plan">
                        <i class="uil uil-minus"></i>
                    </button>
                </div>
            </div>
        `;
        $("#master_plan_fields").append(newRow);
    });

    $(document).on("click", ".remove-master-plan", function () {
        $(this).closest(".master-plan-row").remove();
    });

    if (
        typeof existingMasterPlans !== "undefined" &&
        existingMasterPlans.length > 0
    ) {
        existingMasterPlans.forEach(function (plan, index) {
            if (index === 0) {
                var firstRow = $(".master-plan-row").first();
                firstRow
                    .find('input[name="master_plan[0][name]"]')
                    .val(plan.name);
            } else {
                var newRow = `
                    <div class="row mb-2 master-plan-row">
                        <div class="col-md-5">
                            <input type="text" name="master_plan[${index}][name]" class="form-control master_plan_${index}_name"
                                placeholder="Enter Title" value="${plan.name}">
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="master_plan[${index}][2d_image]" class="form-control master_plan_${index}_2d_image"
                                accept="image/*" placeholder="2D Image">
                            ${
                                plan.image_2d
                                    ? `<div class="mt-1"><small>Current: ${plan.image_2d}</small></div>`
                                    : ""
                            }
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="master_plan[${index}][3d_image]" class="form-control master_plan_${index}_3d_image"
                                accept="image/*" placeholder="3D Image">
                            ${
                                plan.image_3d
                                    ? `<div class="mt-1"><small>Current: ${plan.image_3d}</small></div>`
                                    : ""
                            }
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-master-plan">
                                <i class="uil uil-minus"></i>
                            </button>
                        </div>
                    </div>
                `;
                $("#master_plan_fields").append(newRow);
            }
        });
    }
});
