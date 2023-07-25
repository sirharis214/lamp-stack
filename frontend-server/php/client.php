<?php
# $_SERVER['DOCUMENT_ROOT'] = '/var/www/html'
require_once($_SERVER['DOCUMENT_ROOT'].'/rabbitmq/rabbitMQLib.inc');

session_start();
$rabbitmq_client = new rabbitmqClient("rabbitmq.ini", "rabbitmq-server"); // $ini_file, $server
$backend_client = new rabbitmqClient("rabbitmq.ini", "backend-server"); // $ini_file, $server

function data($request) {
	global $backend_client;
	$response = $backend_client->send_request($request);
	return $response;
}

/* Function to sanitize user input and prevent SQL injection */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}

/* Form Post's */
function updateUser($action) {
	global $backend_client;
	$current_time = date("m/d/Y H:i:s");
	
	$request = array();
	$request['type'] = $action; // update-user
	$request['data'] = array(
		"id" => sanitizeInput($_POST['id']),
		"username" => sanitizeInput($_POST["username"]), 
		"email" => sanitizeInput($_POST['email']),
		"role" => sanitizeInput($_POST['role']),
		"updated_on" => $current_time,
	);
	$response = $backend_client->send_request($request);
	return $response;
}

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
	$request['data'] = array(
		"email" => $_POST['email'],
		"password" => $_POST['password']
	);
	
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'login':
                $response = login();
			    $_SESSION['messages'] = $response["messages"];
			    if ($response["status"]==true) {
			    	$_SESSION['authenticated'] = true;
			    	$_SESSION['last_activity'] = time();
					header('location:../files/sites/home.php' );
				} else if ($response["status"]==false) {
					header('location:../index.php' );
				}
                break;
            case 'login2':
                $response = login2();
				$_SESSION['messages'] = $response['messages'];
				if ($response['status']==true) {
					$_SESSION['authenticated'] = true;
					$_SESSION['role'] = $response['role'];
					$_SESSION['last_activity'] = time();
					header('location:../files/sites/home.php');
				} else if ($response["status"]==false) {
					header('location:../login.php');
				}
                break;
            case 'register':
                $response = register();
				$_SESSION['messages'] = $response['messages'];
				if ($response['status']==true) {
					header('location:../login.php');
				} else if ($response["status"]==false) {
					header('location:../files/sites/register.php');
				}
                break;
            case 'update-user':
            	$response = updateUser($action);
            	$_SESSION['messages'] = $response['messages'];
            	header('location:../files/sites/home.php');
            	break;
            default:
                $_SESSION['messages'] = "Invalid action!";
                header('location:../login.php');
                break;
        }
    } else {
    	$_SESSION['messages'] = "Action not specified!";
        header('location:../login.php');
    }
}
?>

