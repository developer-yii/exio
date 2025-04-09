$(document).ready(function () {
    let reraDetailIndex = 0;

    function addNewRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 rera-detail-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="rera_details[${reraDetailIndex}][id]" value="${
            data ? data.id : ""
        }">
                            <div class="form-group mb-3">
                                <label class="form-label" for="rera_details_${reraDetailIndex}_title">Title*</label>
                                <input type="text" id="rera_details_${reraDetailIndex}_title" name="rera_details[${reraDetailIndex}][title]" class="form-control rera_details_${reraDetailIndex}_title" placeholder="Enter Title" value="${
            data ? data.title : ""
        }" aria-required="true">
                                <span class="error" aria-live="polite"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="rera_details_${reraDetailIndex}_document">Upload Document</label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" id="rera_details_${reraDetailIndex}_document" name="rera_details[${reraDetailIndex}][document]" class="form-control property_document custom-cursor-on-hover" accept=".pdf" data-toggle="tooltip" data-placement="top" title="Upload document" autocomplete="off" aria-required="true">
                                        <div class="form-text">Allowed formats: PDF</div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-secondary download-rera-document" type="button" data-document="${
                                            data ? data.document : ""
                                        }" aria-label="Download Document">
                                            <i class="uil uil-download"></i> Download
                                        </button>
                                    </div>
                                </div>
                                <span class="error" aria-live="polite"></span>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-rera-detail float-end" aria-label="Remove RERA Detail">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $(".add-more-rera-details").closest(".col-md-4").before(newRow);
        reraDetailIndex++;
    }

    // Initialize existing project details if any
    if (
        typeof existingReraDetails !== "undefined" &&
        existingReraDetails.length > 0
    ) {
        existingReraDetails.forEach(function (reraDetail) {
            addNewRow(reraDetail);
        });
    } else {
        // Add one empty row by default
        addNewRow();
    }

    $(document).on("click", ".add-more-rera-details", function () {
        addNewRow();
    });

    $(document).on("click", ".remove-rera-detail", function () {
        $(this).closest(".rera-detail-item").remove();
    });

    // New event listener for download button
    $(document).on("click", ".download-rera-document", function () {
        console.log("download-rera-document");
        let documentPath = $(this).data("document");
        console.log(assetUrl + "storage/rera_documents/" + documentPath);
        if (documentPath) {
            window.open(
                assetUrl + "storage/rera_documents/" + documentPath,
                "_blank"
            );
        } else {
            alert("No document available for download.");
        }
    });
});
