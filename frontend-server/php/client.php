<?php
# $_SERVER['DOCUMENT_ROOT'] = '/var/www/html'
require_once($_SERVER['DOCUMENT_ROOT'].'/rabbitmq/rabbitMQLib.inc');

session_start();
$rabbitmq_client = new rabbitmqClient("rabbitmq.ini", "rabbitmq-server"); // $ini_file, $server
$backend_client = new rabbitmqClient("rabbitmq.ini", "backend-server"); // $ini_file, $server

function register() {
	global $backend_client;
	$default_role = "read_only";
	$current_time = date("m/d/Y H:i:s");
	
	$request = array();
	$request['type'] = "register";
	$request['data'] = array(
		"username" => $_POST['first-name'].".".$_POST['last-name'], 
		"email" => $_POST['email'],
		"role" => $default_role,
		"created_on" => $current_time,
		"hashed_data" => password_hash($_POST['password'], PASSWORD_DEFAULT)
	);
	
	$response = $backend_client->send_request($request);
	return $response;
}

function login2() {
	global $backend_client;
	$request = array();
	$request['type'] = "login";
	$request['email'] = $_POST['email'];
	$request['password']= $_POST['password'];

	$response = $backend_client->send_request($request);
	return $response;
}

function login() {
	global $rabbitmq_client;
	$request = array();
	$request['type']     = "login";
	$request['email']    = $_POST['email'];
	$request['password'] = $_POST['password'];
	
	$response = $rabbitmq_client->send_request($request);
	return $response;
}

if (isset($_POST['login']) ) {
	$response = login();
	$_SESSION['messages'] = $response["messages"];
	
	if ($response["status"]==true) {
		header('location:../files/sites/home.php' );
	} else if ($response["status"]==false) {
		header('location:../index.php' );
	}
} else if (isset($_POST['login2']) ) {
	$response = login2();
	$_SESSION['messages'] = $response['messages'];

	if ($response['status']==true) {
		header('location:../files/sites/home.php');
	} else if ($response["status"]==false) {
		header('location:../login.php');
	}
} else if (isset($_POST['register']) ) {
	$response = register();
	$_SESSION['messages'] = $response['messages'];

	if ($response['status']==true) {
		header('location:../files/sites/home.php');
	} else if ($response["status"]==false) {
		header('location:../login.php');
	}
}
?>

