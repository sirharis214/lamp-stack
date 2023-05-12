<?php
# $_SERVER['DOCUMENT_ROOT'] = '/var/www/html'
require_once($_SERVER['DOCUMENT_ROOT'].'/rabbitmq/rabbitMQLib.inc');


function test(){

	$client = new rabbitMQClient('rabbitmq.ini', 'dev-server');

	$request = array();
	$request['type'] = "test";
	$response = $client->send_request($request);

	return $response;
}
$data = test();
?>

