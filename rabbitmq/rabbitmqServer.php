#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

function login($email, $password){
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	if(strcmp($email, "test@test.com") !==0 ){
		array_push($response["messages"], "email: ".$email." does not exist");
	}
	else{
		if(strcmp($password, "testpassword") !==0 ){
			array_push($response["messages"], "password: ".$password." does not match");
		}
		else{
			$response["status"] = true;
			array_push($response["messages"], "Logged in email: ".$email." with password: ".$password);
		}
	}
	return $response;
}

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
	else{
		switch($request['type']){
			case "test":
				return array("returnCode"=>"0", "message"=>"recieved type:test");
			case "login":
				return login($request['email'], $request['password']);
		}
	
		return array("returnCode"=>"0", "message"=>"Server received request and processed");
	}
}

$server = new rabbitMQServer('rabbitmq.ini', 'dev-server');
$server->process_requests('requestProcessor');
exit();

