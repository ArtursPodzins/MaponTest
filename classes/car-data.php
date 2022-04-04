<?php
use Mapon\MaponApi;
include __DIR__ . '/../vendor/autoload.php';

//Set timezone
date_default_timezone_set('GMT');

// Api connection
$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

class CarData {

    public function getUnitList($api){
        $unitResult = $api->get('unit/list', array(
            'units' => 0,
        ));

        return $unitResult;
    }

    public function getCarNumbers ($unitResult){
        $carNumbers = [];
        foreach($unitResult->data->units as $unit_id => $unit_data){
            array_push($carNumbers, $unit_data->label);
        }

        return $carNumbers;
    }

    public function getRouteResult($api, $startDate, $endDate, $carData){
        $routeResult = $api->get('route/list', array(
            'from' => $startDate,
            'till' => $endDate,
            'units' => 0,
            'unit_id' => $carData[0]->unit_id,
            'include' => array('polyline', 'decoded_route')
        ));

        return $routeResult;
    }

    public function getRoutePoints($routeResult, $api){
        if(!empty($routeResult)){
            foreach($routeResult->data->units as $unit_id => $unit_data){
                foreach ($unit_data->routes as $route) {
                    if ($route->type == 'route') {
                        if (isset($route->polyline)) {
                            $points = $api->decodePolyline($route->polyline);
                        }
                    }
                }
            }
            return $points;
        }else{

        }
    }
}