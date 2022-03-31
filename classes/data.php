<?php
use Mapon\MaponApi;
include __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');

$apiKey = '5333a9720180356462a0d9615a38f6dfff4581aa';
$apiUrl = 'https://mapon.com/api/v1/';
$api = new MaponApi($apiKey, $apiUrl);

class dataHandler{

    public function getAllUnitData($api){
        $unitData = $api->get('unit/list', array(
            'units' => 0,
        ));

        return $unitData;
    }
}
