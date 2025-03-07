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

    // Handle checkbox selection
    $(".compareBoxOpen").click(function (event) {
        let checkbox = $(this).find(".checkbox");

        if (!$(event.target).hasClass('checkbox')) {
            checkbox.prop("checked", !checkbox.prop("checked"));
        }

        let selectedCheckboxes = $(".checkbox:checked");
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
                        $(".comparePorjectModal").removeClass("show");
                    }
                }
            });
        } else {
            $(".comparePorjectModal").removeClass("show");
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
            $(".comparePorjectModal").removeClass("show");
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

    $(".propertyCard").click(function (event) {

        $("#coverImage").attr("src", ""); // Clear image
        $('#property_price, #property_name, #custom_type, #location, #carpet_area, #total_floor, #total_tower, #age_of_construction, #property_type, #description').text("");

        $(".overViewBox").find(".overBox").filter(function () {
            return $(this).find("span").text().trim() === "Project Size";
        }).remove();
        $(".multyimg").html("");

        let image = $(this).data("image");
        let projectName = $(this).data("project-name");
        let price = $(this).data("price");
        let customType = $(this).data("custom-type");
        let location = $(this).data("location");
        let area = $(this).data("area");
        let floors = $(this).data("floors");
        let towers = $(this).data("towers");
        let age = $(this).data("age");
        let propertyType = $(this).data("property-type");
        let description = $(this).data("description");
        let whatsApp = $(this).data("whatsapp-number");

        // // Update modal content dynamically
        $("#coverImage").attr("src", image);
        $('#property_price').text(price);
        $('#property_name').text(projectName);
        $('#custom_type').text(customType);
        $('#location').text(location);
        $('#carpet_area').text(area);
        $('#total_floor').text(floors);
        $('#total_tower').text(towers);
        $('#age_of_construction').text(age);
        $('#property_type').text(propertyType);
        $('#description').html(description);

        let sizeData = $(this).data("size");
        if (Array.isArray(sizeData) && sizeData.length > 0) {
            let htmlContent = '';

            sizeData.forEach(item => {
                htmlContent += `
                    <div class="overBox">
                        <span>Project Size</span>
                        <h6>${item.value}</h6>
                    </div>
                `;
            });
            $(".overViewBox").append(htmlContent);
        }

        let multiImgs = $(this).data("multi-image");
        if (Array.isArray(multiImgs) && multiImgs.length > 0) {
            let htmlContent = '';

            multiImgs.forEach(item => {
                htmlContent += `
                    <div class="box comImg">
                        <img src="${item.imgurl}">
                    </div>
                `;
            });
            $(".multyimg").append(htmlContent);
        }

        let slug = $(this).data("slug");
        if (slug) {
            let propertyUrl = getPropertyDetailsUrl.replace("_slug_", slug);
            $("#more-details").attr("href", propertyUrl);
            $("#whatsapplink").attr("href", `https://wa.me/${whatsApp}?text=${encodeURIComponent(propertyUrl)}`);
        }

        // Show the modal
        if(!$(event.target).hasClass('checkbox') && !$(event.target).hasClass('compareBoxOpen'))
        {
            $("#propertyModal").modal("show");
        }
    });

});
