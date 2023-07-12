<?php

class User{
	
	public function validateLogin($email, $password){
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		
		echo "Validating email and password".PHP_EOL;
		if($email == "haris@test.com" && $password == "test2"){
			array_push($response["messages"], "$email logged in");
			$response["status"] = true;
		}else{
			array_push($response["messages"], "Error: incorrect password for $email");
		}
   			
		return $response;
	} // close validateLogin()
} // close User class
