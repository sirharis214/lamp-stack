#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
require_once('db.php.inc');

function login($email, $password){
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	$check_login = new User();
	$check_login_response = $check_login->validateLogin($email, $password);
	
	# update response messages
	foreach($check_login_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# check if login was successful or not
	if($check_login_response['status'] == true){
		$response["status"] = true;
		array_push($response["messages"], "Logged in email: ".$email." with password: ".$password);
	}
	
	//var_dump($check_login_response);
	print_r($response);
	return $response;
}

function requestProcessor($request){
	echo "received request !".PHP_EOL;
	print_r($request);
	/*echo "{\n";
	foreach($request as $key=>$value){
		echo " $key : $value\n";
	}
	echo "}\n";
	*/
	
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

$server = new rabbitmqServer('rabbitmq.ini', 'backend-server');
$server->process_requests('requestProcessor');
exit();

