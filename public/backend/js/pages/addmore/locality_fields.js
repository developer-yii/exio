$(document).ready(function () {
    let localityIndex = 0;

    function addNewRow(data = null) {
        let newRow = `
            <div class="col-md-4 mb-3 locality-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="locality[${localityIndex}][id]" value="${
            data ? data.id : ""
        }">

                            <!-- Locality -->
                            <div class="form-group mb-3">
                                <label class="form-label">Select Locality</label>
                                <select name="locality[${localityIndex}][locality_id]"
                                    class="form-control locality_${localityIndex}_locality_id select2"
                                    data-toggle="select2">
                                    <option value="">Select Locality</option>
                                </select>
                                <span class="error"></span>
                            </div>

                            <!-- Distance -->
                            <div class="form-group mb-3">
                                <label class="form-label">Distance</label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="number"
                                            name="locality[${localityIndex}][distance]"
                                            class="form-control locality_${localityIndex}_distance"
                                            placeholder="Enter Distance"
                                            step="1"
                                            min="0"
                                            value="${
                                                data ? data.distance : ""
                                            }">
                                        <span class="error"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="locality[${localityIndex}][distance_unit]"
                                            class="form-control form-select locality_${localityIndex}_distance_unit">
                                            <option value="km" ${
                                                data &&
                                                data.distance_unit === "km"
                                                    ? "selected"
                                                    : ""
                                            }>Kilometer</option>
                                            <option value="m" ${
                                                data &&
                                                data.distance_unit === "m"
                                                    ? "selected"
                                                    : ""
                                            }>Meter</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Time to Reach -->
                            <div class="form-group mb-3">
                                <label class="form-label">Time to Reach</label>
                                <div class="input-group">
                                    <input type="number"
                                        name="locality[${localityIndex}][time_to_reach]"
                                        class="form-control locality_${localityIndex}_time_to_reach"
                                        placeholder="Enter Time to Reach"
                                        step="1"
                                        min="0"
                                        value="${
                                            data ? data.time_to_reach : ""
                                        }">
                                    <span class="input-group-text">Min</span>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger remove-locality float-end">
                                    <i class="uil uil-minus"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert before the add button
        $(".add-more-locality").closest(".col-md-4").before(newRow);

        // Initialize select2 for the new locality dropdown
        $(`.locality_${localityIndex}_locality_id`).select2({
            dropdownParent: $(`.locality_${localityIndex}_locality_id`).closest(
                ".card-body"
            ),
            placeholder: "Select Locality",
        });

        // Load localities in dropdown
        loadLocalities(localityIndex, data ? data.locality_id : "");

        localityIndex++;
    }

    function loadLocalities(index, selectedValue = "") {
        let options = '<option value="">Select Locality</option>';
        $.each(locality, function (id, name) {
            options += `<option value="${id}" ${
                selectedValue == id ? "selected" : ""
            }>${name}</option>`;
        });
        $(`.locality_${index}_locality_id`).html(options);
    }

    // Initialize existing localities if any
    if (
        typeof existingLocalities !== "undefined" &&
        existingLocalities.length > 0
    ) {
        existingLocalities.forEach(function (locality) {
            addNewRow(locality);
        });
    } else {
        // Add one empty row by default
        addNewRow();
    }

    $(document).on("click", ".add-more-locality", function () {
        addNewRow();
    });

    $(document).on("click", ".remove-locality", function () {
        $(this).closest(".locality-item").remove();
    });
});
