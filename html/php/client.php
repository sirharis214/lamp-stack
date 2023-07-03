<?php
# $_SERVER['DOCUMENT_ROOT'] = '/var/www/html'
require_once($_SERVER['DOCUMENT_ROOT'].'/rabbitmq/rabbitMQLib.inc');

session_start();
$client = new rabbitMQClient('rabbitmq.ini', 'dev-server');

function login(){
	global $client;
	$request = array();
	$request['type']     = "login";
	$request['email']    = $_POST['email'];
	$request['password'] = $_POST['password'];
	
	$response = $client->send_request($request);
	return $response;
}

if(isset($_POST['login']) ){
	$response = login();
	$_SESSION['messages'] = $response["messages"];
	
	if($response["status"]==true){
		header('location:../files/sites/home.php' );
	}else if($response["status"]==false){
		header('location:../index.php' );
	}
}
?>

