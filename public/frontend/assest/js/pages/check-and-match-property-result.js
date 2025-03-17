// Move markers and map to global scope
let markers = [];
let map;
let mc;
let currentInfoWindow = null;

$(document).ready(function () {
    async function initMap() {
        try {
            // Load the required libraries
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement, PinElement } =
                await google.maps.importLibrary("marker");
            map = new Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: -28.024, lng: 140.887 },
                mapId: "DEMO_MAP_ID",
                mapTypeControl: false,
                fullscreenControl: false,
            });

            await loadMarkers(projects);
        } catch (error) {
            console.error("Error initializing map:", error);
        }
    }

    initMap();
});

function clearMarkers() {
    // First clear the marker clusterer
    if (mc) {
        mc.clearMarkers();
    }

    // Then clear individual markers
    markers.forEach((marker) => {
        if (marker) {
            marker.setMap(null);
        }
    });

    // Reset markers array
    markers = [];
}

async function loadMarkers(projects) {
    try {
        clearMarkers();

        const { PinElement } = await google.maps.importLibrary("marker");

        markers = projects
            .map((location, i) => {
                // Convert latitude and longitude to numbers and validate
                const lat = parseFloat(location.latitude);
                const lng = parseFloat(location.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    return null;
                }

                const label = (i + 1).toString();
                const pinGlyph = new google.maps.marker.PinElement({
                    glyph: label,
                    glyphColor: "white",
                });

                const marker = new google.maps.marker.AdvancedMarkerElement({
                    position: { lat: lat, lng: lng },
                    content: createImagePin(map_pin),
                    map: map,
                    title: location.project_name,
                });

                let priceFormatted = '₹' + project.price_from + formatPriceUnit(project.price_from_unit);
                if (location.price_from != location.price_to || location.price_from_unit != location.price_to_unit) {
                    priceFormatted += ' - ' + location.price_to + formatPriceUnit(location.price_to_unit);
                }

                console.log(location.project_badge);

                const infoWindowContent = `
                    <div class="map-info-window">
                        <div class="propertyCard propertyCardMap isOnMap">
                            <div class="owl-carousel owl-theme">
                                ${location.project_images.map(image => `
                                <div class="item">
                                    <div class="imgBox">
                                        <img src="${assetUrl}storage/project_images/${image.image}" alt="${location.project_name}">
                                        <div class="imgheader">
                                            ${location.project_badge ? `<span>${location.project_badge.name}</span>` : ''}
                                            <i class="fa-regular fa-heart heartIconFill"></i>
                                        </div>
                                    </div>
                                </div>`).join('')}
                            </div>
                            <div class="priceBox">
                                <div class="price">
                                    <h5>${priceFormatted}</h5>
                                </div>
                            </div>
                            <div class="propertyName">
                                <h5>${location.project_name}</h5>
                            </div>
                            <div class="locationProperty">
                                <p>${location.custom_property_type || ''} | ${location.floor_plans.map(plan => plan.carpet_area).join(', ') + ' Sqft' || ''} | ${location.location.location_name || ''}</p>
                            </div>
                            <div class="addressBoxMap">
                                <div class="boxLogo">
                                    <img src="${assetUrl}frontend/assest/images/x-btn.png" alt="x-btn">
                                    <span>${location.exio_suggest_percentage || 0}%</span>
                                </div>
                                <div class="clickTo">
                                    <a href="javascript:void(0)" class="compareBoxOpen">
                                        <input type="checkbox" class="form-check-input checkbox" id="checkbox-signin" name="compare[]" autocomplete="off" value="${location.id}">
                                        <label for="checkbox-signin">Compare</label>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                const infoWindow = new google.maps.InfoWindow({
                    content: infoWindowContent,
                    maxWidth: 300,
                });

                // Improved marker click handler
                marker.addListener("click", () => {
                    // Close any open info window
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }

                    // Open this marker's info window
                    infoWindow.open({
                        anchor: marker,
                        map: map,
                    });
                    currentInfoWindow = infoWindow;

                    setTimeout(() => {
                        $('.propertyCardMap .owl-carousel').owlCarousel({
                            loop: true,
                            margin: 10,
                            nav: false,
                            dots: true,
                            responsive: {
                                0: {
                                    items: 1
                                }
                            }
                        });
                    }, 100);

                    // map.panTo(marker.position);
                    // map.setZoom(10);
                });

                return marker;
            })
            .filter((marker) => marker !== null); // Remove null markers

        mc = new markerClusterer.MarkerClusterer({ markers, map });

        // Fit bounds code...
        const bounds = new google.maps.LatLngBounds();
        markers.forEach((marker) => {
            if (marker) {
                bounds.extend(marker.position);
            }
        });

        setTimeout(() => {
            map.fitBounds(bounds, {
                padding: {
                    top: 50,
                    right: 50,
                    bottom: 50,
                    left: 50,
                },
            });
        }, 1000);
    } catch (error) {
        console.error("Error loading markers:", error);
    }
}

function createImagePin(imageUrl) {
    const pin = document.createElement("div");
    pin.innerHTML = `
        <img src="${imageUrl}"
             style="width: 50px; height: 50px;"
             alt="Location marker">
    `;
    return pin;
}

function formatPriceUnit(priceUnit_query) {
    return priceUnit[priceUnit_query] || '';
}