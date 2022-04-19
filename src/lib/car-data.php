<?php
use Mapon\MaponApi;
include __DIR__ . '/../../vendor/autoload.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

class CarData {
    // Getting unit list data
    public function getUnitList($api)
    {
        $unitResult = $api->get('unit/list', array(
            'units' => 0,
        ));

        return $unitResult;
    }

    // Getting car labels for selection
    public function getCarNumbers ($unitResult)
    {
        $carNumbers = [];
        foreach($unitResult->data->units as $unit_id => $unit_data){
            array_push($carNumbers, $unit_data->label);
        }

        return $carNumbers;
    }

    // Getting route list
    public function getRouteResult($api, $startDate, $endDate, $carData)
    {
        $carRouteData = [];
        foreach($carData as $car){
            $routeResult = $api->get('route/list', array(
                'from' => $startDate,
                'till' => $endDate,
                'units' => 0,
                'unit_id' => $car->unit_id,
                'include' => array('polyline', 'decoded_route')
            ));
            array_push($carRouteData, $routeResult);
        }
        return $carRouteData;
    }

    public function getRouteIds($routeResult, $api){
        $routesIds = [
            0 => [],
            1 => [],
            2 => []
        ];
        $routes = [];
        $i = 0;
        if(!empty($routeResult)){
            foreach($routeResult as $carRoute){
                foreach($carRoute->data->units as $unit_id => $unit_data){
                    foreach ($unit_data->routes as $route) {
                        if ($route->type == 'route') {
                            array_push($routes, $route->route_id);
                        }
                    }
                    for($i == 0; $i < count($routesIds); $i++){
                        if(empty($routesIds[$i])){
                            array_push($routesIds[$i], $routes);
                        }
                    }
                    
                }
            }
            return $routesIds;
        }else{

        }
    }

    public function getFullRouteByTime($routeResult, $api){
        $fullRoutePoints = [];
        $points = [];
        if(!empty($routeResult)){
            foreach($routeResult as $carRoute){
                foreach($carRoute->data->units as $unit_id => $unit_data){
                    foreach($unit_data->routes as $route){
                        if($route->type == 'route'){
                            if(isset($route->polyline)){
                                array_push($points,$api->decodePolyline($route->polyline));
                            }
                        }
                    }
                    array_push($fullRoutePoints, $points);
                }
            }
        }
        return $fullRoutePoints;
    }

    public function getRoutePointsById($selectedId, $routeResult, $api){
        if(!empty($routeResult) && !empty($selectedId)){
            foreach($routeResult->data->units as $unit_id => $unit_data){
                foreach ($unit_data->routes as $route) {
                    if ($route->type == 'route') {
                        if($selectedId == $route->route_id){
                            if(isset($route->polyline)){
                                $points = $api->decodePolyline($route->polyline);
                            }
                        }
                    }
                }
            }
            return $points;
        }else{

        }
    }

    public function getRouteData($routeResult, $api, $selectedId){
        if(!empty($routeResult) && !empty($selectedId)){
            foreach($routeResult->data->units as $unit_id => $unit_data){
                foreach($unit_data->routes as $route){
                    if($route->type == 'route'){
                        if($selectedId == $route->route_id){
                            $routeData = $route;
                        }
                    }
                }
            }
        }
        return $routeData;
    }
}