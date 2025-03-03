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

                // Create InfoWindow content
                const infoWindowContent = `
                    <div class="map-info-window">
                        <h4>${location.project_name}</h4>
                        <p>${location.address || "Address not available"}</p>
                        ${
                            location.image_url
                                ? `<img src="${location.image_url}" alt="${location.project_name}" style="max-width: 200px;">`
                                : ""
                        }
                        <div class="mt-2">
                            <a href="${
                                location.detail_url || "#"
                            }" class="btn btn-primary btn-sm">View Details</a>
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

                    // Center the map on the clicked marker
                    map.panTo(marker.position);
                    map.setZoom(10);
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
             style="width: 70px; height: 70px;"
             alt="Location marker">
    `;
    return pin;
}
