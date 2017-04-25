<?php
session_start();
require_once('../SwaggerClient-php/autoload.php');
require_once('../vendor/autoload.php');
require_once('../provider.php');
require_once('../inc.php');

token_refresh();

header('Content-Type: application/json;charset=utf-8');

$api_crop = new Swagger\Client\Api\CorporationApi();
$api_universe = new Swagger\Client\Api\UniverseApi();
$datasource = "tranquility"; // string | The server name you would like data from

try {
    $corpmem = $api_corp->getCorporationsCorporationIdMembers(corpid(charid()),$datasource,token());
    $ids = array();
    foreach ($corpmem as $key => $value) {
    	$ids[$key]=$value['character_id'];
    }
    $split_ids=array_chunk($ids,1000);
    $json=array();
    foreach ($split_ids as $value){
        $json=array_merge($json,$api_universe->postUniverseNames($value, $datasource));
    }
    echo'
    <script>
    console.log(\'json\',JSON.parse('.json_encode($json).'));
    </script>
    ';
    echo json_encode($json);
} catch (Exception $e) {
    echo 'Exception: ', $e->getMessage(), PHP_EOL;
}



?>
