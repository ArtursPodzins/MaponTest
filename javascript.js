function initMap(){
    // Map options
    // New Map
    var map = new google.maps.Map(document.getElementById('map'),options);

    // Add marker
    var start = new google.maps.Marker({
        position:{lat:57.52233, lng: 24.37825},
        map:map
    });
    var end = new google.maps.Marker({
        position:{lat:57.50424, lng:25.55932},
        map:map
    });

    var carCoordinates = [

    ];

    var route = new google.maps.Polyline({
        path: carCoordinates,
        geodesic: true,
        strokeColor: "#e81a1a",
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    route.setMap(map);
}