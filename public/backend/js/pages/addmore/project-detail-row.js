$(document).ready(function () {
    let projectDetailIndex = -1;

    // Function to add new row
    function addNewRow(data = null) {
        projectDetailIndex++;
        let newRow = `
            <div class="row mb-2 project-detail-row">
                <input type="hidden" name="project_detail[${projectDetailIndex}][id]" value="${
            data ? data.id : ""
        }">
                <div class="col-md-6 form-group">
                    <input type="text" name="project_detail[${projectDetailIndex}][name]"
                           class="form-control project_detail_${projectDetailIndex}_name"
                           placeholder="Enter Name"
                           value="${data ? data.name : ""}">
                    <span class="error"></span>
                </div>
                <div class="col-md-5 form-group">
                    <input type="text" name="project_detail[${projectDetailIndex}][value]"
                           class="form-control project_detail_${projectDetailIndex}_value"
                           placeholder="Enter Value"
                           value="${data ? data.value : ""}">
                    <span class="error"></span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn ${
                        projectDetailIndex === 0
                            ? "btn-primary add-more-field"
                            : "btn-danger remove-field"
                    }">
                        <i class="uil ${
                            projectDetailIndex === 0
                                ? "uil-plus"
                                : "uil-trash-alt"
                        }"></i>
                    </button>
                </div>
            </div>
        `;
        $("#project_detail_fields").append(newRow);
    }

    // Initialize with empty row if no existing data
    if (
        typeof existingProjectDetails === "undefined" ||
        existingProjectDetails.length === 0
    ) {
        addNewRow();
    } else {
        $("#project_detail_fields").empty();
        existingProjectDetails.forEach((detail) => {
            addNewRow(detail);
        });
    }

    // Handle add more button click
    $(document).on("click", ".add-more-field", function () {
        addNewRow();
    });

    // Handle remove field
    $(document).on("click", ".remove-field", function () {
        if ($(".project-detail-row").length > 1) {
            $(this).closest(".project-detail-row").remove();
            reindexProjectDetails();
        }
    });

    // Reindex function to maintain proper indexing after removal
    function reindexProjectDetails() {
        $(".project-detail-row").each(function (index) {
            $(this)
                .find('input[type="hidden"]')
                .attr("name", `project_detail[${index}][id]`);
            $(this)
                .find('input[type="text"]')
                .first()
                .attr("name", `project_detail[${index}][name]`);
            $(this)
                .find('input[type="text"]')
                .last()
                .attr("name", `project_detail[${index}][value]`);
        });
    }
});
