$(document).ready(function () {
    let masterPlanIndex = -1;

    // Function to add new row
    function addNewRow(data = null) {
        masterPlanIndex++;
        let newRow = `
            <div class="row mb-2 master-plan-row">
                <input type="hidden" name="master_plan[${masterPlanIndex}][id]" value="${
            data ? data.id : ""
        }">
                <div class="col-md-5 form-group">
                    <input type="text" name="master_plan[${masterPlanIndex}][name]"
                           class="form-control master_plan_${masterPlanIndex}_name"
                           placeholder="Enter Title"
                           value="${data ? data.name : ""}">
                    <span class="error"></span>
                </div>
                <div class="col-md-3 form-group">
                    <input type="file" name="master_plan[${masterPlanIndex}][2d_image]"
                           class="form-control master_plan_${masterPlanIndex}_2d_image"
                           accept="image/*">
                    <span class="error"></span>
                </div>
                <div class="col-md-3 form-group">
                    <input type="file" name="master_plan[${masterPlanIndex}][3d_image]"
                           class="form-control master_plan_${masterPlanIndex}_3d_image"
                           accept="image/*">
                    <span class="error"></span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn ${
                        masterPlanIndex === 0
                            ? "btn-primary add-more-master-plan"
                            : "btn-danger remove-master-plan"
                    }">
                        <i class="uil ${
                            masterPlanIndex === 0 ? "uil-plus" : "uil-trash-alt"
                        }"></i>
                    </button>
                </div>
            </div>
        `;
        $("#master_plan_fields").append(newRow);
    }

    // Initialize with empty row if no existing data
    if (
        typeof existingMasterPlans === "undefined" ||
        existingMasterPlans.length === 0
    ) {
        addNewRow();
    } else {
        $("#master_plan_fields").empty();
        existingMasterPlans.forEach((plan) => {
            addNewRow(plan);
        });
    }

    // Handle add more button click
    $(document).on("click", ".add-more-master-plan", function () {
        addNewRow();
    });

    // Handle remove field
    $(document).on("click", ".remove-master-plan", function () {
        if ($(".master-plan-row").length > 1) {
            $(this).closest(".master-plan-row").remove();
            reindexMasterPlans();
        }
    });

    // Reindex function to maintain proper indexing after removal
    function reindexMasterPlans() {
        $(".master-plan-row").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `master_plan[${index}][id]`);
            $(this)
                .find('input[type="text"]')
                .attr("name", `master_plan[${index}][name]`);
            $(this)
                .find('input[type="file"]')
                .first()
                .attr("name", `master_plan[${index}][2d_image]`);
            $(this)
                .find('input[type="file"]')
                .last()
                .attr("name", `master_plan[${index}][3d_image]`);
        });
    }
});
