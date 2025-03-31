// Move markers and map to global scope
let markers = [];
let map;
let mc;
let currentInfoWindow = null;
projects = projects.data;

async function initMap() {
    try {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        map = new Map(document.getElementById("map"), {
            zoom: 12,
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

// Make the function global
// window.initMap = initMap;

$(document).ready(function () {
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

                const isWishlistedByUser = Array.isArray(location.wishlisted_by_users) &&
                    location.wishlisted_by_users.some(user => user.id == authId);

                    
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

                let priceFormatted = getFormattedPrice(
                    location.price_from,
                    location.price_from_unit,
                    location.price_to,
                    location.price_to_unit
                );
                
                const infoWindowContent = `
                    <div class="map-info-window">
                        <div class="propertyCard propertyCardMap isOnMap cursor-default">
                            <div class="owl-carousel owl-theme">
                                ${location.project_images.map(image => `
                                <div class="item">
                                    <div class="imgBox">
                                        <img src="${assetUrl}storage/project_images/${image.image}" alt="${location.project_name}">
                                        <div class="imgheader">
                                            ${location.project_badge ? `<span>${location.project_badge.name}</span>` : '<span style="opacity: 0 !important;""></span>'}
                                            <i class="${isWishlistedByUser ? 'fa-solid' : 'fa-regular'} fa-heart heartIconFill" data-id="${location.id}"></i>
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
                                <h5 class="one-line-text" title="${location.project_name}">${location.project_name}</h5>
                            </div>
                            <div class="locationProperty">
                                <p class="one-line-text" title="${location.custom_property_type || ''} | ${location.floor_plans.map(plan => plan.carpet_area).join(', ') + ' Sqft' || ''} | ${location.location.location_name || ''}">
                                    ${location.custom_property_type || ''} | ${location.floor_plans.map(plan => plan.carpet_area).join(', ') + ' Sqft' || ''} | ${location.location.location_name || ''}
                                </p>
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
                                0: { items: 1 }
                            }
                        });

                        initializeComparison();
                        // if ($(".checkbox").length > 0) {
                        //     initializeComparison();
                        // } else {
                        //     console.warn("Checkboxes not loaded, retrying...");
                        //     setTimeout(initializeComparison, 1000);
                        // }
                    }, 100);

                    // Adjust map to make the info window fully visible
                    // const markerPosition = marker.position;
                    // const mapDiv = document.getElementById("map");
                    // const mapHeight = mapDiv.clientHeight;

                    // // Move the map down to make space for the info window
                    // const newCenter = {
                    //     lat: markerPosition.lat() - (mapHeight / 40000), // Adjust value to push map down
                    //     lng: markerPosition.lng(),
                    // };

                    // map.panTo(newCenter);
                    // map.panTo(marker.position);
                    // map.setZoom(4);
                });

                return marker;
            })
            .filter((marker) => marker !== null); // Remove null markers

        mc = new markerClusterer.MarkerClusterer({ markers, map });

        // if (markers.length > 0) {            
        //     const firstMarker = markers[0];
        
        //     google.maps.event.addListenerOnce(map, "idle", function () {
        //         setTimeout(() => {
        //             google.maps.event.trigger(firstMarker, "click");
        //         }, 500); // Delay added to ensure markers are fully rendered
        //     });
        // }
        

        if (markers.length > 0) {
            google.maps.event.addListenerOnce(map, "idle", function () {
                setTimeout(() => {
                    // Find the middle index
                    const middleIndex = Math.floor(markers.length / 2);
        
                    // Get the middle marker
                    const middleMarker = markers[middleIndex];
        
                    // If a valid marker exists, trigger a click event
                    if (middleMarker) {
                        google.maps.event.trigger(middleMarker, "click");
                    }

                    // initializeComparison();
                }, 500); // Small delay ensures markers are fully rendered
            });
        }        

        // Fit bounds code...
        const bounds = new google.maps.LatLngBounds();
        markers.forEach((marker) => {
            if (marker) {
                bounds.extend(marker.position);
            }
        });

        if (markers.length > 1) {
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
        }
        
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

// function formatPriceUnit(priceUnit_query) {
//     return priceUnit[priceUnit_query] || '';
// }
