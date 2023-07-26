#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
require_once('db.php.inc');

function deleteUser($data) {
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	/* For Testing */
	$status = true;	
	echo "Performing deleteUser for ".$data['username'].PHP_EOL;
	$response["status"] = $satus;
	array_push($response['messages'], "Deleted user ".$data['username']);
	
	
	/* to actually delete user from table */
	/*$db_data = new Data();
	$deleteUser_response = $db_data->deleteUser($data);
	
	# update response messages
	foreach($deleteUser_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# check response status
	if ($deleteUser_response['status'] === true) {
		$response['status'] = true;
	}*/
	
	print_r($response);
	return $response;
}

function updateUser($data) {
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	$db_data = new Data();
	$updateUser_response = $db_data->updateUser($data);
	
	# update response messages
	foreach($updateUser_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# check response status
	if ($updateUser_response['status'] === true) {
		$response['status'] = true;
	}
	print_r($response);
	return $response;
}

function get_all_users_data() {
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	$data = new Data();
	$all_users_data_response = $data->get_all_users_data();
	
	# update response messages
	foreach($all_users_data_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# if response status is true, update response and return it
	if ($all_users_data_response['status']) {
		$response['status'] = true;
		$response['data'] = $all_users_data_response['data'];
	}
	#echo(count($response['data'])." Rows of data".PHP_EOL );
	return $response;
}

function register($data) {
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	$register_user = new User();
	$register_user_response = $register_user->registerUser($data);
	
	# update response messages
	foreach($register_user_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# check if register was successful or not
	if($register_user_response['status'] == true){
		$response["status"] = true;
	}
	return $response;
}

function login($data){
	$response = array();
	$response["status"] = false;
	$response["messages"] = array();
	
	$check_login = new User();
	$check_login_response = $check_login->validateLogin($data);
	
	# update response messages
	foreach($check_login_response['messages'] as $msg){
		array_push($response['messages'], $msg);
	}
	# check if login was successful or not
	if($check_login_response['status'] == true){
		$response["status"] = true;
		$response["role"] = $check_login_response['role'];
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
				return login($request['data']);
			case "register":
				return register($request['data']);
			case "get_all_users_data":
				return get_all_users_data();
			case "update-user":
				return updateUser($request['data']);
			case "delete-user":
				return deleteUser($request['data']);
		}
	
		return array("returnCode"=>"0", "message"=>"Server received request and processed");
	}
}

$server = new rabbitmqServer('rabbitmq.ini', 'backend-server');
$server->process_requests('requestProcessor');
exit();

