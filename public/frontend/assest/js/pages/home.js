

loadProperty();

function loadProperty(currentPage = null) {

    city = $("#city_header").val();

    $.ajax({
        url: getPropertyByCityUrl,
        type: "GET",
        data: {
            city: city,
            page: currentPage
        },
        success: function(response) {
            // Check that the request was successful and that projects exist.
            let container = $('.propertyList');
            if (response.status && response.data && response.data.data.length > 0) {
                let projects = response.data.data;

                if (deviceType === "desktop") {
                    projects.forEach(function(project) {
                        container.append(renderProperty(project));
                    });

                } else {

                    projects.forEach(function(project) {
                        container.trigger('add.owl.carousel', [$(
                            renderProperty(project))]).trigger(
                            'refresh.owl.carousel');
                    });
                }

                if (response.data.current_page >= response.data.last_page) {
                    lastPageReached = true;
                    $('.exploreMore').hide();
                }else{
                    $('.exploreMore').show();
                }
            } else {
                lastPageReached = true;
                $('.exploreMore').hide();
                container.html('<p class="not-found">No Property Found</p>');
            }
        },
        error: function() {
            console.error("Error loading more properties.");
        }
    });

}

function renderProperty(property) {
    let propertyUrl = getPropertyDetailsUrl.replace("_slug_", property.slug);
    let imageUrl = property.cover_image;

    return `
    <div class="col-xl-4 col-md-6">
        <a href="${propertyUrl}">
            <div class="propertySec">
                <div class="imgBox">
                    <img src="${imageUrl}" alt="property-img" loading="lazy">
                </div>
                <div class="propertyName">
                    <h5 class="one-line-text" title="${property.project_name}">${property.project_name}</h5>
                </div>
                <div class="locationProperty">
                    <div class="homeBox comBox">
                        <img src="${baseUrl}assest/images/Home.png" alt="Home">
                        <p class="one-line-text" title="${property.custom_property_type ? property.custom_property_type : ''}">${property.custom_property_type ? property.custom_property_type : ''}</p>
                    </div>
                    <div class="location comBox">
                        <img src="${baseUrl}assest/images/Location.png" alt="Location">
                        <p class="one-line-text" title="${property.location.location_name}, ${property.city.city_name}">${property.location.location_name}, ${property.city.city_name}</p>
                    </div>
                </div>
                <div class="suggestBox">
                    <div class="leftBtn">
                        <img src="${baseUrl}assest/images/x-btn.png" alt="x-btn">
                    </div>
                    <div class="rightBar">
                        <h5>${property.exio_suggest_percentage}%</h5>
                        <div class="progress">
                            <div class="progress-bar" style="width: ${property.exio_suggest_percentage}%"
                                 role="progressbar" aria-valuenow="${property.exio_suggest_percentage}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
`;
}

$('#exploreMoreDesktop').click(function(e) {
    e.preventDefault();

    if (lastPageReached) return;

    currentPage++;
    loadProperty(currentPage);
});