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

    //Getting route list for single car
    public function getRouteResult($api, $startDate, $endDate, $carData)
    {
            $routeResult = $api->get('route/list', array(
                'from' => $startDate,
                'till' => $endDate,
                'units' => 0,
                'unit_id' => $carData[0]->unit_id,
                'include' => array('polyline', 'decoded_route')
            ));
        return $routeResult;
    }

    // Getting route list for all selected cars
    public function getRouteResultAllCars($api, $startDate, $endDate, $carData)
    {
        $carRouteResult = [];
        foreach($carData as $car){
            $routeResult = $api->get('route/list', array(
                'from' => $startDate,
                'till' => $endDate,
                'units' => 0,
                'unit_id' => $car->unit_id,
                'include' => array('polyline', 'decoded_route')
            ));
            array_push($carRouteResult, $routeResult);
        }
        
        return $carRouteResult;
    }

    // Getting route ids
    public function getRouteIds($routeResult, $api){
        $routesIds = [];
        if(!empty($routeResult)){
            foreach($routeResult->data->units as $unit_id => $unit_data){
                foreach ($unit_data->routes as $route) {
                    if ($route->type == 'route') {
                        array_push($routesIds, $route->route_id);
                    }
                }
            }
            return $routesIds;
        }else{

        }
    }

    // Getting Full Route Points
    public function getFullRouteByTime($routeResult, $api){
        $fullRoutePoints = [];
        $points = [];
        if(!empty($routeResult)){
            foreach($routeResult->data->units as $unit_id => $unit_data){
                foreach($unit_data->routes as $route){
                    if($route->type == 'route'){
                        if(isset($route->polyline)){
                            array_push($points,$api->decodePolyline($route->polyline));
                        }
                    }
                }
            }
            array_push($fullRoutePoints, $points);
        }
        return $fullRoutePoints;
    }

    // Getting all cars route points
    public function getCarsFullRoutesByTime($routeResults, $api){
        $fullRoutePoints = [];
        $points = [];
        if(!empty($routeResults)){
            foreach($routeResults as $carResult){
                foreach($carResult->data->units as $unit_id => $unit_data){
                    foreach($unit_data->routes as $route){
                        if($route->type == 'route'){
                            if(isset($route->polyline)){
                                array_push($points,$api->decodePolyline($route->polyline));
                            }
                        }
                    }
                }
                array_push($fullRoutePoints, $points);
            }
        }
        return $fullRoutePoints;
    }

    // Getting specific route by selected id
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

    // Getting selected route data
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