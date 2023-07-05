<?php

class User{
	private $db_connection;
	private $host = "localhost";
	private $user = "haris_db_user";
	private $pass = "p";
	public  $db   = "dev_db";
	
	public function __construct(){
		try{
			$this->db_connection = new mysqli($this->host, $this->user, $this->pass, $this->db);
		}catch(Exception $e) {
  			echo 'Database connection error: '.$e->getMessage().PHP_EOL;
  		}

		//echo "Connected to database".PHP_EOL;
	}//close _construct
	
	public function validateLogin($email, $password){
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		
		if(!$this->db_connection){
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
	
		$_email = $this->db_connection->real_escape_string($email);
		$_password = $this->db_connection->real_escape_string($password);
		
		echo "Validating email and password".PHP_EOL;
		$statement = "SELECT * FROM Users WHERE email='$_email' and hashed_data='$_password'";
		$query_response = $this->db_connection->query($statement);
			
		if(!$query_response){
    			// Error occurred while executing the query
   			echo "Error: ".mysqli_error($db_connection).PHP_EOL;
   			array_push($response["messages"], "Error: ".mysqli_error($db_connection) );
   		}else{
    			if(mysqli_num_rows($query_response) > 0 ){
        			// Records found
        			while ($row = mysqli_fetch_assoc($query_response)) {
           				if($row['email'] == $_email &&  $row['hashed_data'] == $_password){ 
           					echo"password matches for $_email".PHP_EOL;
						$response["status"] = true;
						array_push($response["messages"], "password matches for $_email");
           				}else{
						echo "password did not match for $_email".PHP_EOL;
						array_push($response["messages"], "incorrect password for $_email");
					}
        			}// while
        			
    			}else{
        			// No records found
        			echo "No records found with the email: $_email.".PHP_EOL;
        			array_push($response["messages"], "No records found for $_email" );
    			} // else
		} // else
		
		return $response;
	} // close validateLogin()
} // close User class
