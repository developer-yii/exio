$(document).ready(function () {
    let projectDetailIndex = 0;

    function addNewRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 project-detail-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="project_detail[${projectDetailIndex}][id]" value="${
            data ? data.id : ""
        }">

                            <!-- Name -->
                            <div class="form-group mb-3">
                                <label class="form-label">Name*</label>
                                <input type="text" name="project_detail[${projectDetailIndex}][name]"
                                    class="form-control project_detail_${projectDetailIndex}_name"
                                    placeholder="Enter Name"
                                    value="${data ? data.name : ""}">
                                <span class="error"></span>
                            </div>

                            <!-- Value -->
                            <div class="form-group mb-3">
                                <label class="form-label">Value*</label>
                                <input type="text" name="project_detail[${projectDetailIndex}][value]"
                                    class="form-control project_detail_${projectDetailIndex}_value"
                                    placeholder="Enter Value"
                                    value="${data ? data.value : ""}">
                                <span class="error"></span>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-project-detail float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert before the add button
        $(".add-more-project-detail").closest(".col-md-4").before(newRow);
        projectDetailIndex++;
    }

    // Initialize existing project details if any
    if (
        typeof existingProjectDetails !== "undefined" &&
        existingProjectDetails.length > 0
    ) {
        existingProjectDetails.forEach(function (projectDetail) {
            addNewRow(projectDetail);
        });
    } else {
        // Add one empty row by default
        addNewRow();
    }

    $(document).on("click", ".add-more-project-detail", function () {
        addNewRow();
    });

    $(document).on("click", ".remove-project-detail", function () {
        $(this).closest(".project-detail-item").remove();
        reindexProjectDetails();
    });

    function reindexProjectDetails() {
        $(".project-detail-item").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `project_detail[${index}][id]`);
            $(this)
                .find('input[type="text"]')
                .first()
                .attr("name", `project_detail[${index}][name]`)
                .removeClass()
                .addClass(`form-control project_detail_${index}_name`);
            $(this)
                .find('input[type="text"]')
                .last()
                .attr("name", `project_detail[${index}][value]`)
                .removeClass()
                .addClass(`form-control project_detail_${index}_value`);
        });
    }
});
