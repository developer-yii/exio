$(document).ready(function () {
    initAmenityToggles();
});

function renderPropertyCard(project, amenities) {
    // console.log(project);
    // console.log(amenities);
    // Format price
    let priceFormatted = '₹' + project.price_from + formatPriceUnit(project.price_from_unit);
    if (project.price_from != project.price_to || project.price_from_unit != project.price_to_unit) {
        priceFormatted += ' - ' + project.price_to + formatPriceUnit(project.price_to_unit);
    }

    // Format amenities
    const maxChars = 20;
    const amenityList = project.amenities.split(',')
        .filter(id => id && amenities[id])
        .map(id => amenities[id]);

    const amenityListString = amenityList.join(', ');
    const hasMore = amenityListString.length > maxChars;
    const displayText = hasMore ? amenityListString.substring(0, maxChars) + '...' : amenityListString;

    // Generate HTML
    return `
        <div class="col-md-6">
            <div class="propertyCard propertyCardModal cursor-pointer"
                data-id="${project.id}"
                data-slug="${project.slug}"
                data-image="${projectImageUrl}${project.cover_image}"
                data-project-name="${project.project_name}"
                data-builder-name="${project.builder.builder_name || 'N/A'}"
                data-custom-type="${project.custom_property_type || 'N/A'}"
                data-location="${project.location.location_name}, ${project.city.city_name}"
                data-price="${priceFormatted}"
                data-area="${project.carpet_area || 'N/A'} sqft"
                data-floors="${project.total_floors ? project.total_floors + ' Floors' : 'N/A'}"
                data-towers="${project.total_tower || 'N/A'}"
                data-age="${getAgeOfConstruction(project.age_of_construction)}"
                data-type="${project.property_type}"
                data-property-type="${getPropertyType(project.property_type)}"
                data-description="${project.project_about}"
                data-size='${JSON.stringify(project.project_details.map(detail => ({ name: detail.name, value: detail.value })))}'
                data-multi-image='${JSON.stringify(project.project_images.slice(0, 3).map(img => ({ imgurl: img.getProjectImageUrl })))}'
                data-whatsapp-number="${getSettingFromDb}"
                data-like-class="${project.is_wishlisted ? 'fa-solid' : 'fa-regular'}">

                <div class="imgBox">
                    <img src="${projectImageUrl}${project.cover_image}" alt="property-img">
                    <div class="imgheader">
                        ${project.projectBadge ?
            `<span>${project.projectBadge.name}</span>` :
            `<span style="opacity: 0 !important;"></span>`
        }
                        <i data-id="${project.id}"
                           class="${project.is_wishlisted ? 'fa-solid' : 'fa-regular'} fa-heart heartIconFill"></i>
                    </div>
                </div>

                <div class="priceBox">
                    <div class="price">
                        <h5>${priceFormatted}</h5>
                    </div>
                    <div class="boxLogo">
                        <img src="${baseUrl}/assest/images/x-btn.png" alt="x-btn">
                        <span>${project.exio_suggest_percentage}%</span>
                    </div>
                </div>

                <div class="propertyName">
                    <h5>${project.project_name}</h5>
                </div>

                <div class="locationProperty">
                    ${project.custom_property_type ? `
                        <div class="homeBox comBox">
                            <img src="${baseUrl}/assest/images/Home.png" alt="Home">
                            <p>${project.custom_property_type}</p>
                        </div>
                    ` : ''}

                    ${(project.location.location_name || project.city.city_name) ? `
                        <div class="location comBox">
                            <img src="${baseUrl}/assest/images/Location.png" alt="Location">
                            <p>${project.location.location_name}${project.location.location_name && project.city.city_name ? ', ' : ''}${project.city.city_name}</p>
                        </div>
                    ` : ''}
                </div>

                <div class="addressBox">
                    <img src="${baseUrl}/assest/images/Home.png" alt="Home">
                    <p class="amenityText d-flex">
                        ${amenityList.length ? `
                            <span class="amenity-text">${displayText}</span>
                            ${hasMore ? `
                                <span class="more-amenities" style="display: none;">${amenityListString}</span>
                                <a href="javascript:void(0)" class="toggle-amenities">more</a>
                            ` : ''}
                        ` : '-'}
                    </p>
                </div>
            </div>
        </div>
    `;
}

function initAmenityToggles() {
    document.querySelectorAll('.addressBox .toggle-amenities').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.stopPropagation();
            var addressBox = this.closest('.addressBox');
            var amenityText = addressBox.querySelector('.amenity-text');
            var moreAmenities = addressBox.querySelector('.more-amenities');
            var isExpanded = this.textContent === 'less';

            if (isExpanded) {
                amenityText.style.display = 'block';
                moreAmenities.style.display = 'none';
                this.textContent = 'more';
            } else {
                amenityText.style.display = 'none';
                moreAmenities.style.display = 'block';
                this.textContent = 'less';
            }
        });
    });
}

function formatPriceUnit(priceUnit_query) {
    return priceUnit[priceUnit_query] || '';
}

function getAgeOfConstruction(age) {
    return ageOfConstruction[age] || '';
}

function getPropertyType(propertyType) {
    return propertyType[propertyType] || '';
}
