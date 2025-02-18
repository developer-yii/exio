$(document).ready(function () {
    let projectDetailIndex = 0;

    $(document).on("click", ".add-more-field", function () {
        projectDetailIndex++;
        let newRow = `
            <div class="row mb-2 project-detail-row">
                <div class="col-md-6">
                    <input type="text" name="project_detail[${projectDetailIndex}][name]" class="form-control project_detail_${projectDetailIndex}_name" placeholder="Enter Name">
                    <span class="error"></span>
                </div>
                <div class="col-md-5">
                    <input type="text" name="project_detail[${projectDetailIndex}][value]" class="form-control project_detail_${projectDetailIndex}_value" placeholder="Enter Value">
                    <span class="error"></span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-field">
                        <i class="uil uil-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        $("#project_detail_fields").append(newRow);
    });

    // Handle remove field
    $(document).on("click", ".remove-field", function () {
        $(this).closest(".project-detail-row").remove();
    });

    // If there are existing values, populate them
    if (
        typeof existingProjectDetails !== "undefined" &&
        existingProjectDetails.length > 0
    ) {
        existingProjectDetails.forEach(function (detail, index) {
            if (index === 0) {
                // Update first row
                var firstRow = $(".project-detail-row").first();
                firstRow
                    .find('input[name="project_detail[0][name"]')
                    .val(detail.name);
                firstRow
                    .find('input[name="project_detail[0][value"]')
                    .val(detail.value);
            } else {
                // Add new rows for remaining details
                var newRow = `
                    <div class="row mb-2 project-detail-row">
                        <div class="col-md-6">
                            <input type="text" name="project_detail[${index}][name]" class="form-control project_detail_${index}_name" placeholder="Enter Name" value="${detail.name}">
                            <span class="error"></span>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="project_detail[${index}][value]" class="form-control project_detail_${index}_value" placeholder="Enter Value" value="${detail.value}">
                            <span class="error"></span>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-field">
                                <i class="uil uil-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                $("#project_detail_fields").append(newRow);
            }
        });
    }
});
