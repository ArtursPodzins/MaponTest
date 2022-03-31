function initMap(){
    // Map options
    var options = {
        zoom:11,
        center:{lat:57.52233, lng: 24.37825}
    };
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
        {lat: 57.52233, lng: 24.37825},
        {lat: 57.52209, lng:24.37818},
        {lat: 57.5218, lng: 24.37828},
        {lat: 57.52157, lng: 24.37841},
        {lat: 57.52117, lng: 24.37909}
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