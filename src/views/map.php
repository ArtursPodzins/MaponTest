<?php
session_start();
use Mapon\MaponApi;
include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../lib/car-data.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

$carN = new CarData();
$unitlist = $carN->getUnitList($api);
$carNumbers = $carN->getCarNumbers($unitlist);

if(isset($_POST["submit-route"])){
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
    $routeIds = $carN->getRouteIds($routeResult, $api);
    $_SESSION["startDate"] = $startDate;
    $_SESSION["endDate"] = $endDate;
    $_SESSION["carData"] = $carData;
    $_SESSION["routeIds"] = $routeIds;
}
if(isset($_POST["showRoute"])){
    $startDate = $_SESSION["startDate"];
    $endDate = $_SESSION["endDate"];
    $carData = $_SESSION["carData"];
    $routeIds = $_SESSION["routeIds"];
    $routeResult = $carN->getRouteResult($api, $startDate, $endDate, $carData);
    $selectedId = $_POST["routeId"];
    $points = $carN->getRoutePointsById($selectedId, $routeResult, $api);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/map.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <select class="form-select" aria-label="Default select example" name="carLabel">
            <option selected>Select A Car</option>
            <option value="VW Crafter"><?php echo $carNumbers[0] ?></option>
            <option value="Volvo"><?php echo $carNumbers[1]?></option>
            <option value="Golf car"><?php echo $carNumbers[2]?></option>
        </select>
        <label>
            <div class="start-time-box">
                <div class="select-time">
                    <span>SELECT START TIME</span>
                </div>
                <br>
                <div class="date-form">
                    <input type="date" id="startDate" name="startDate" value="startDate" min="2016-01-01" max="" required>
                </div>
                <div class="time-form">
                    <input type="time" id="startTime" name="startTime" required>
                </div>
            </div>
            <div class="end-time-box">
                <div class="select-time">
                    <span>SELECT END TIME</span>
                </div>
                <br>
                <div class="date-form">
                    <input type="date" id="endDate" name="endDate" value="endDate" min="2016-01-01" max="" required>
                </div>
                <div class="time-form">
                    <input type="time" id="endTime" name="endTime" required>
                </div>
            </div>
        </label>
        <br>
        <div class="submit-btn">
            <button type="submit" name="submit-route" class="btn btn-primary btn-sm">GET ROUTES</button>
        </div>
    </form>
    <?php if(!empty($routeIds) || !empty($_SESSION["carData"])){?>
        <form method="post" class="select-route">
            <select name="routeId">
                <option selected>Select Route</option>
                <?php 
                foreach($routeIds as $routeId => $routeid){
                    ?> <option value="<?php echo $routeid;?>"><?php echo "ID: ".$routeid ?></option>
                <?php } ?>
            </select>
            <button type="submit" name="showRoute">SHOW ROUTE</button>
        </form>
    <?php } ?>
    <div id="map"></div>
    <div class="route-data">
        <h2>DATA ABOUT SELECTED ROUTE</h2>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                <th scope="col">Start time</th>
                <th scope="col">End time</th>
                <th scope="col">Address</th>
                <th scope="col">Distance</th>
                <th scope="col">Time spent</th>
                </tr>
            </thead>
        </table>
    </div>
    <script>
        function initMap(){
            <?php if(!empty($points)){
            $last = count($points);?>
            // Map options
            var options = {
                zoom:10,
                center:{lat:<?php echo $points[0]["lat"]?>, lng: <?php echo $points[0]["lng"]?>}
            };
            // New Map
            var map = new google.maps.Map(document.getElementById('map'),options);

            // Add marker
            var start = new google.maps.Marker({
                position:{lat:<?php echo $points[0]["lat"]?>, lng: <?php echo $points[0]["lng"]?>},
                map:map
                
            });

            var end = new google.maps.Marker({
                position:{lat:<?php echo $points[$last - 1]["lat"]?>, lng: <?php echo $points[$last - 1]["lng"]?>},
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
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmqQlgXiUgVjdGxZQdkvzLQmkNc12pgKQ&callback=initMap"></script>
</body>
</html>