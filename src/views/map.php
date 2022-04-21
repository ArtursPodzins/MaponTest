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
    $allRoutes = [];
    $fullRoutes = [];
    foreach($unitlist->data->units as $unit_id => $unit_data){
        foreach($_POST["carLabel"] as $label){
            if($label == $unit_data->label){
                array_push($carData, $unit_data);
            }
        }
    }
    $startDate = $_POST["startDate"]."T".$_POST["startTime"].":00Z";
    $endDate = $_POST["endDate"]."T".$_POST["endTime"].":00Z";
    if(!empty($startDate) && !empty($endDate) && !is_countable($carData)){
        $routeResult = $carN->getRouteResultAllCars($api, $startDate, $endDate, $carData);
    }elseif(!empty($startDate) && !empty($endDate)){
        $routeResult = $carN->getRouteResult($api, $startDate, $endDate, $carData);
    }
    if(isset($routeResult)){
        $routeIds = $carN->getRouteIds($routeResult, $api);
        $_SESSION["routeIds"] = $routeIds;
        $routes = $carN->getFullRouteByTime($routeResult, $api);
    }else{
        $routes = $carN->getCarsFullRoutesByTime($routeResult, $api);
    }
    
    foreach($routes as $carRoutes){
        foreach($carRoutes as $carR){
            array_push($allRoutes, $carR);
        }
    }
    foreach($allRoutes as $route){
        array_push($fullRoutes, $route);
    }
    $_SESSION["startDate"] = $startDate;
    $_SESSION["endDate"] = $endDate;
    $_SESSION["carData"] = $carData;
    $_SESSION["fullRoutes"] = $fullRoutes;
}

if(isset($_POST["showRoute"])){
    $startDate = $_SESSION["startDate"];
    $endDate = $_SESSION["endDate"];
    $carData = $_SESSION["carData"];
    $routeIds = $_SESSION["routeIds"];
    $routeResult = $carN->getRouteResult($api, $startDate, $endDate, $carData);
    $selectedId = $_POST["routeId"];
    $points = $carN->getRoutePointsById($selectedId, $routeResult, $api);
    $routeData = $carN->getRouteData($routeResult, $api, $selectedId);
    $fullRoutes = $_SESSION["fullRoutes"];
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
    <form method="post" action="logout"><?php
        if(isset($_SESSION["users_uid"])){?>
            <div class="logout-btn">
                <button type="submit" class="btn btn-outline-warning">LOGOUT</button>
            </div><?php
        }?>
    </form>
    <form method="post" class="main-form">
        <div class="car-select">
            <input type="checkbox" name="carLabel[]" value="<?php echo $carNumbers[0] ?>">VW-Crafter
            <input type="checkbox" name="carLabel[]" value="<?php echo $carNumbers[1] ?>">Volvo
            <input type="checkbox" name="carLabel[]" value="<?php echo $carNumbers[2] ?>">Golf-Car
        </div>
        <label>
            <div class="start-time-box">
                <div class="select-time">
                    <span>SELECT START TIME</span>
                </div>
                <br>
                <div class="date-form">
                    <input type="date" id="startDate" name="startDate" value="2022-03-01" min="2016-01-01" max="" required>
                </div>
                <div class="time-form">
                    <input type="time" id="startTime" name="startTime" value="12:00" required>
                </div>
            </div>
            <div class="end-time-box">
                <div class="select-time">
                    <span>SELECT END TIME</span>
                </div>
                <br>
                <div class="date-form">
                    <input type="date" id="endDate" name="endDate" value="2022-03-30" min="2016-01-01" max="" required>
                </div>
                <div class="time-form">
                    <input type="time" id="endTime" name="endTime" value="12:00" required>
                </div>
            </div>
        </label>
        <br>
        <div class="submit-btn">
            <button type="submit" name="submit-route" class="btn btn-primary btn-sm">GET ROUTES</button>
        </div>
    </form>
    <?php if(!empty($routeIds)){?>
        <form method="post">
            <div class="route-box">
                <select name="routeId">
                    <option selected>Select Route</option><?php
                        foreach($routeIds as $routeId => $routeid){
                            ?> <option value="<?php echo $routeid;?>"><?php echo "ID: ".$routeid;?></option><?php
                        }?>
                </select>
                <button type="submit" name="showRoute">SHOW ROUTE</button>
                <button type="submit" name="showAllRoutes">CLEAR ROUTES</button>
            </div>
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
                <th scope="col">Start Address</th>
                <th scope="col">End Address</th>
                <th scope="col">Distance</th>
                <th scope="col">Time spent</th>
                </tr>
            </thead>
            <?php if(!empty($routeData)){
                $startTime = $routeData->start->time;
                $startTime = str_replace("T", " ",$startTime);
                $startTime = str_replace("Z", "",$startTime);
                $endTIme = $routeData->end->time;
                $endTime = str_replace("T", " ",$endTIme);
                $endTime = str_replace("Z", "",$endTIme);
                $startTS = strtotime($startTime);
                $endTS = strtotime($endTIme);
                ?><tbody>
                    <tr>
                        <td><?php echo $startTime;?></td>
                        <td><?php echo $endTime;?></td>
                        <td><?php echo $routeData->start->address;?></td>
                        <td><?php echo $routeData->end->address;?></td>
                        <td><?php if($routeData->distance < 1000){
                            echo $routeData->distance."m";?>
                        <?php }else{
                            echo round($routeData->distance / 1000, 1)."km";}?></td>
                        <td><?php echo round(abs($startTS - $endTS)/(60*60), 2). " hour(s)";?></td>
                    </tr>
                </tbody>
            <?php }?>
        </table>
    </div>
    <script>
        function initMap(){<?php
            if(!empty($points)){
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
                });<?php 
            }else if(!empty($fullRoutes) || isset($_POST["showAllRoutes"])){?>
                var options = {
                    zoom:10,
                    center:{lat:56.935008, lng: 24.141213}
                };
                // New Map
                var map = new google.maps.Map(document.getElementById('map'),options);

                var carCoordinates = [<?php
                    foreach($fullRoutes as $carRoute){
                        foreach($carRoute as $coords){?>
                            {lat:<?php echo $coords["lat"];?>, lng:<?php echo $coords["lng"];?>},<?php 
                        }
                    }?>
                ];
    
                var route = new google.maps.Polyline({
                    path: carCoordinates,
                    geodesic: true,
                    strokeColor: "#0c18f7",
                    strokeOpacity: 2.0,
                    strokeWeight: 2,
                });<?php 
            }else{ ?>
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