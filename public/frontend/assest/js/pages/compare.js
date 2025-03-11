$(document).ready(function () {
    // Function to get cookie value by name
    function getCookie(name) {
        let cookies = document.cookie.split("; ");
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i].split("=");
            if (cookie[0] === name) {
                return decodeURIComponent(cookie[1]);
            }
        }
        return "";
    }

    // Function to set cookie
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + encodeURIComponent(value) + "; path=/" + expires;
    }

    // Function to update compare button state
    function updateCompareButtonState(ids) {
        if (ids.length === 2) {
            let encodedIds = ids.map(id => btoa(id)).join(",");
            let compareUrl = comparePropertytUrl + "?property=" + encodedIds;
            $('.btnCompare').attr('href', compareUrl).removeClass('cursor-default').prop('disabled', false);
        } else {
            $('.btnCompare').attr('href', 'javascript:void(0)').addClass('cursor-default').prop('disabled', true);
        }
    }

    // Function to update the cookie when properties are selected
    function updateSelectedPropertiesCookie() {
        let selectedIds = $(".checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        setCookie("selectedProperties", JSON.stringify(selectedIds), 7);
        return selectedIds;
    }

    // Function to fetch and update compare modal
    function fetchCompareProperties(ids) {
        if (ids.length === 0) {
            $(".comparePorjectModal").removeClass("show");
            return;
        }

        $.ajax({
            type: "GET",
            url: getComparePropertyUrl,
            data: { ids: ids },
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    updateCompareModal(response.data);
                    updateCompareButtonState(ids);
                } else {
                    toastr.error(response.message || 'Failed to fetch property details');
                    $(".comparePorjectModal").removeClass("show");
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Failed to fetch property details');
                $(".comparePorjectModal").removeClass("show");
            }
        });
    }

    // Handle compare box click
    $(document).on('click', ".compareBoxOpen", function (event) {
        event.preventDefault();
        let checkbox = $(this).find(".checkbox");

        if (!$(event.target).hasClass('checkbox')) {
            checkbox.prop("checked", !checkbox.prop("checked"));
        }

        let selectedCheckboxes = $(".checkbox:checked");
        if (selectedCheckboxes.length > 2) {
            checkbox.prop("checked", false);
            toastr.warning('You can compare a maximum of 2 properties');
        }

        let selectedIds = updateSelectedPropertiesCookie();
        fetchCompareProperties(selectedIds);
    });

    // Remove property from comparison
    $(document).on("click", ".removeCompare", function () {
        let propertyId = $(this).data("id");

        $(".detailmainSec[data-id='" + propertyId + "']").remove();
        $(".checkbox[value='" + propertyId + "']").prop("checked", false);

        let selectedIds = updateSelectedPropertiesCookie();
        updateCompareButtonState(selectedIds);

        if ($(".detailmainSec").length === 0) {
            $(".comparePorjectModal").removeClass("show");
        }
    });

    // Update compare modal content
    function updateCompareModal(properties) {
        $(".comparePorjectModal").addClass("show");
        let modalContent = $(".comparePorjectCard");
        modalContent.empty();

        if (!Array.isArray(properties) || properties.length === 0) {
            $(".comparePorjectModal").removeClass("show");
            return;
        }

        properties.forEach(property => {
            let propertyHtml = `
                <div class="detailmainSec" data-id="${property.id}">
                    <div class="detailsTextSec">
                        <div class="siteDetails">
                            <div class="logoMain">
                                <img src="${property.cover_image}" alt="${property.project_name}" loading="lazy">
                            </div>
                            <div class="textBox">
                                <h5>${property.project_name}</h5>
                                <span>By ${property.builder.builder_name}</span>
                                <div class="locationProperty">
                                    <div class="homeBox comBox">
                                        <img src="${baseUrl}assest/images/Home.png" alt="Home">
                                        <p title="${property.custom_property_type}">${property.truncatedPropertyType}</p>
                                    </div>
                                    <div class="location comBox">
                                        <img src="${baseUrl}assest/images/Location.png" alt="Location">
                                        <p title="${property.location_city}">${property.truncatedLocation}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="closeMark">
                            <a href="javascript:void(0)" class="removeCompare" data-id="${property.id}">
                                <img src="${baseUrl}assest/images/x-orange.png" alt="Remove">
                            </a>
                        </div>
                    </div>
                    <div class="endText">
                        <p><i class="fa-regular fa-calendar"></i> Possession by ${property.possession_date}</p>
                    </div>
                    <div class="priceShare">
                        <h5><span>${property.price}</span></h5>
                    </div>
                </div>
            `;
            modalContent.append(propertyHtml);
        });
    }

    // Restore selections from cookies on page load
    let storedIds = getCookie("selectedProperties");
    if (storedIds) {
        try {
            let idsArray = JSON.parse(storedIds);
            idsArray.forEach(id => {
                $(".checkbox[value='" + id + "']").prop("checked", true);
            });
            updateCompareButtonState(idsArray);
        } catch (e) {
            console.error('Failed to parse stored properties:', e);
            setCookie("selectedProperties", "[]", 7);
        }
    }
});
