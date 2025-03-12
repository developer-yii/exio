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

    // Function to update the cookie when properties are selected
    function updateSelectedPropertiesCookie() {
        let selectedIds = $(".checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        setCookie("selectedProperties", JSON.stringify(selectedIds), 7); // Save for 7 days
    }

    // Handle Close Modal
    function closeModal(){
        $(".comparePorjectModal").removeClass("show");
    }
    $(".closeModal").click(function (event) {
        closeModal();
    });

    $(".compareBoxOpen").click(function (event) {
        let checkbox = $(this).find(".checkbox");

        if (!$(event.target).hasClass('checkbox')) {
            checkbox.prop("checked", !checkbox.prop("checked"));
        }

        // Extract the data-type values of the selected checkboxes
        let selectedCheckboxes = $(".checkbox:checked");
        let selectedTypes = [];
        selectedCheckboxes.each(function () {
            let propertyType = $(this).closest(".propertyCard").data("type");
            if (propertyType) {
                selectedTypes.push(propertyType.toLowerCase()); // Convert to lowercase for uniformity
            }
        });

        if (selectedTypes.length === 2) {
            let [type1, type2] = selectedTypes;

            if ((type1 !== type2) && type1 !== "both" && type2 !== "both") {
                checkbox.prop("checked", false); // Uncheck the latest selection
                toastr.warning(`You cannot compare ${type1} property with ${type2} property`);
            }
        }

        if (selectedCheckboxes.length > 2) {
            checkbox.prop("checked", false);
            toastr.warning('You can compare a maximum of 2 properties');
        }

        updateSelectedPropertiesCookie(); // Save to cookie

        let ids = $(".checkbox:checked").map(function () {
            return $(this).val();
        }).get();

        if (ids.length > 0) {
            $.ajax({
                type: "GET",
                url: getComparePropertyUrl,
                data: { ids: ids },
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        updateCompareModal(response.data);
                        if(ids.length == 2){
                            let encodedIds = ids.map(id => btoa(id)).join(",");
                            let compareUrl = comparePropertytUrl + "?property=" + encodedIds;
                            $('.btnCompare').attr('href', compareUrl);
                            $('.btnCompare').removeClass('cursor-default');
                        }else{
                            $('.btnCompare').attr('href', 'javascript:void(0)');
                        }

                    } else {
                        closeModal();
                    }
                }
            });
        } else {
            closeModal();
        }
    });

    // Function to restore selections from cookies on page load
    $(document).ready(function () {
        let storedIds = getCookie("selectedProperties");
        if (storedIds) {
            let idsArray = JSON.parse(storedIds);

            idsArray.forEach(id => {
                $(".checkbox[value='" + id + "']").prop("checked", true);
            });

            // Update the compare button URL without opening the modal
            if (idsArray.length === 2) {
                let encodedIds = idsArray.map(id => btoa(id)).join(",");
                let compareUrl = comparePropertytUrl + "?property=" + encodedIds;
                $('.btnCompare').attr('href', compareUrl);
                $('.btnCompare').removeClass('cursor-default');
            } else {
                $('.btnCompare').attr('href', 'javascript:void(0)');
            }
        }
    });

    // Remove property from modal and cookie
    $(document).on("click", ".removeCompare", function () {
        let propertyId = $(this).data("id");

        // Remove from modal
        $(".detailmainSec[data-id='" + propertyId + "']").remove();

        // Uncheck the corresponding checkbox
        $(".checkbox[value='" + propertyId + "']").prop("checked", false);

        // Update the cookie by removing the ID
        let storedIds = getCookie("selectedProperties");
        if (storedIds) {
            let idsArray = JSON.parse(storedIds);
            let updatedIds = idsArray.filter(id => id != propertyId);
            setCookie("selectedProperties", JSON.stringify(updatedIds), 7);
            $('.btnCompare').attr('href', 'javascript:void(0)');
            $('.btnCompare').addClass('cursor-default');
        }

        // Hide modal if empty
        if ($(".detailmainSec").length === 0) {
            closeModal();
        }
    });

    function updateCompareModal(properties) {
        $(".comparePorjectModal").addClass("show");
        let modalContent = $(".comparePorjectCard");
        modalContent.empty();

        if (properties.length === 0) {
            $(".comparePorjectModal").hide();
            return;
        }

        properties.forEach(property => {

            let propertyHtml = `
                <div class="detailmainSec" data-id="${property.id}">
                    <div class="detailsTextSec">
                        <div class="siteDetails">
                            <div class="logoMain">
                                <img src="${property.cover_image}" alt="${property.project_name}">
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

    modelOpacityAdd('share_property', 'propertyModal');
    modelOpacityRemove('share_property', 'propertyModal');

});
