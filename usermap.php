<?php
use Mapon\MaponApi;
include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/autoloader.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

$unitResult = $api->get('unit/list', array(
    'units' => 0,
));

$carData = [];
if(isset($_POST["submit"])){
    foreach($unitResult->data->units as $unit_id => $unit_data){
        if($_POST["carLabel"] == $unit_data->label){
            array_push($carData, $unit_data);
        }
    }
    if(isset($_POST)){
        $startDate = $_POST["startDate"]."T".$_POST["startTime"].":00Z";
        $endDate = $_POST["endDate"]."T".$_POST["endTime"].":00Z";
    }

    if(!empty($startDate) && !empty($endDate)){
        $routeResult = $api->get('route/list', array(
            'from' => $startDate,
            'till' => $endDate,
            'units' => 0,
            'unit_id' => $carData[0]->unit_id,
            'include' => array('polyline', 'decoded_route')
        ));
    }

    foreach($routeResult->data->units as $unit_id => $unit_data){
        foreach ($unit_data->routes as $route) {
            if ($route->type == 'route') {
                if (isset($route->polyline)) {
                    $points = $api->decodePolyline($route->polyline);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        #map {
            height: 500px;
            width: 70%;
            border: solid 3px black;
        }
    </style>
</head>
<body>
    <form method="POST">
        <label>
            <span>VW Crafter</span>
            <input type="checkbox" name="carLabel" value="VW Crafter">
            <br>
            <span>Volvo</span>
            <input type="checkbox" name="carLabel" value="Volvo">
            <br>
            <span>Golf Car</span>
            <input type="checkbox" name="carLabel" value="Golf car">
            <br>
            <br>
            <span>Choose a time from:</span>
            <input type="date" id="startDate" name="startDate" value="startDate" min="2013-01-01" max="">
            <input type="time" id="startTime" name="startTime">
            <br>
            <span>Choose a time till:</span>
            <input type="date" id="endDate" name="endDate" value="endDate" min="2013-01-01" max="">
            <input type="time" id="endTime" name="endTime">
        </label>
        <br>
        <button type="submit" name="submit">Submit</button>
    </form>
    <div id="map"></div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmqQlgXiUgVjdGxZQdkvzLQmkNc12pgKQ&callback=initMap"></script>
    <script>
        function initMap(){
            // Map options
            var options = {
                zoom:8,
                center:{lat:<?php echo $points[0]["lat"]?>, lng: <?php echo $points[0]["lng"]?>}
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
                <?php foreach($points as $coords){
                    ?>{lat:<?php echo $coords["lat"];?>, lng:<?php echo $coords["lng"];?>},
                <?php }?>
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
    </script>
</body>
</html>