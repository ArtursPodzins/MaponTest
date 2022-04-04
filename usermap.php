<?php
use Mapon\MaponApi;
include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/classes/car-data.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

$carN = new CarData();
$unitlist = $carN->getUnitList($api);
$carNumbers = $carN->getCarNumbers($unitlist);

if(isset($_POST["submit"])){
    $carData = [];
    foreach($unitlist->data->units as $unit_id => $unit_data){
        if($_POST["carLabel"] == $unit_data->label){
            array_push($carData, $unit_data);
        }
    }
    $startDate = $_POST["startDate"]."T".$_POST["startTime"].":00Z";
    $endDate = $_POST["endDate"]."T".$_POST["endTime"].":00Z";

    if(!empty($startDate) && !empty($endDate)){
        $routeResult = $carN->getRouteResult($api, $startDate, $endDate, $carData);
    }

    $points = $carN->getRoutePoints($routeResult, $api);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/mapstyle.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="btn-group">
        <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Large button
        </button>
        <ul class="dropdown-menu">
            <li>
                <button class="dropdown-item" name="carLabel" value="VW Crafter"><?php echo $carNumbers[0]?></button>
            </li>
        </ul>
    </div>
    <form method="post">
        <label>
            
            <br>
            <span><?php echo $carNumbers[1]?></span>
            <input type="checkbox" name="carLabel" value="Volvo">
            <br>
            <span><?php echo $carNumbers[2]?></span>
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
            <?php if(!empty($points)){?>
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
            <?php }else{ ?>
            var options = {
                zoom:10,
                center:{lat: 56.935008, lng: 24.141213}
            };

            var map = new google.maps.Map(document.getElementById('map'),options);
            <?php } ?>
            route.setMap(map);
        }
    </script>
</body>
</html>