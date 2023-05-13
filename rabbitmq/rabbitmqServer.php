#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

function requestProcessor($request){
	echo "received request !".PHP_EOL;
	echo "{\n";
	foreach($request as $key=>$value){
		echo " $key : $value\n";
	}
	echo "}\n";
	
	if(!isset($request['type']) ){
		return "Error: unsupported message type";
	}
	
	switch($request['type']){
		case "test":
			return array("returnCode"=>"0", "message"=>"recieved type:test");
		case "second_test":
			return array("returnCode"=>"0", "message"=>"recieved type:second_test");
	}
	
	return array("returnCode"=>"0", "message"=>"Server received request and processed");
}

$server = new rabbitMQServer('rabbitmq.ini', 'dev-server');
$server->process_requests('requestProcessor');
exit();

