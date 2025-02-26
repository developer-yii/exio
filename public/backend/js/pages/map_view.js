$(document).ready(function () {
    initLocationtMap();
});

function initLocationtMap() {
    // Initialize Google Places Autocomplete
    var initialLat = parseFloat($("#latitude").val()) || 23.022505;
    var initialLong = parseFloat($("#longitude").val()) || 72.5713621;
    // Initialize map
    var map = new google.maps.Map($("#location-map")[0], {
        center: { lat: initialLat, lng: initialLong },
        zoom: 16,
        mapTypeId: "roadmap",
    });

    var marker = new google.maps.Marker({
        position: { lat: initialLat, lng: initialLong },
        map: map,
        draggable: true,
        icon: assetUrl + "backend/images/map_icon.png",
    });

    // Update lat/long inputs when the marker is moved
    google.maps.event.addListener(marker, "dragend", function () {
        var latLng = marker.getPosition();
        $("#latitude").val(latLng.lat());
        $("#longitude").val(latLng.lng());
    });

    // Google Places Autocomplete
    var input = document.getElementById("map-search");
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        // Set new position for marker
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location);
        map.setZoom(16); // Adjust zoom for better view

        // Update inputs
        $("#latitude").val(place.geometry.location.lat());
        $("#longitude").val(place.geometry.location.lng());
    });
}
