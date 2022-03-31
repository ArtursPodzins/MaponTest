<?php
use Mapon\MaponApi;
include __DIR__ . '/vendor/autoload.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

$unitResult = $api->get('unit/list', array(
    'units' => 0,
));

//mysql://b71f20df94555f:8527f4eb@eu-cdbr-west-02.cleardb.net/heroku_d5d58245fb0f454?reconnect=true

//username: b71f20df94555f
//pass: 8527f4eb
//host: eu-cdbr-west-02.cleardb.net
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
    print_r($points);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="javascript.js"></script>
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        #map {
            height: 500px;
            width: 100%;
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
            <input type="date" id="startDate" name="startDate" value="startDate" min="2013-01-01" max="2022.03.28">
            <input type="time" id="startTime" name="startTime">
            <br>
            <span>Choose a time till:</span>
            <input type="date" id="endDate" name="endDate" value="endDate" min="2013-01-01" max="2022.03.28">
            <input type="time" id="endTime" name="endTime">
        </label>
        <br>
        <button type="submit" name="submit">Submit</button>
    </form>
    <div id="map"></div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDp6jv3cOJMWA_iz292l4r075XhK5aXwp0&callback=initMap"></script>
</body>
</html>