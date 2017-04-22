<?php
session_start();
require_once('../SwaggerClient-php/autoload.php');
require_once('../vendor/autoload.php');
require_once('../provider.php');
require_once('../inc.php');

token_refresh();

header('Content-Type: application/json;charset=utf-8');
$raw=getcontract(charid(),token());
$json=array();
$json['attributes']=$raw->result->rowset->attributes();
$json['row']=array();
// print_r($raw->result->rowset);
foreach ($raw->result->rowset->row as $value) {
	$json['row'].append($value->attributes());
}

print_r($json);
// print_r($raw);
// echo json_encode(getcontract(charid(),token()));

?>