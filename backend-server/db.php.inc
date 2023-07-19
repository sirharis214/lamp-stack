<?php

class User{
	private $db_connection;
	private $host = "localhost";
	private $user = "haris_db_user";
	private $pass = "p";
	public  $db   = "dev_db";
	
	public function __construct() {
		try {
			$this->db_connection = new mysqli($this->host, $this->user, $this->pass, $this->db);
		} catch(Exception $e) {
  			echo 'Database connection error: '.$e->getMessage().PHP_EOL;
  		}
		//echo "Connected to database".PHP_EOL;
	}//close _construct
	
	public function validateLogin($data) {
		$tableName = 'Users';
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		
		if (!$this->db_connection) {
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
		
		$_email = $this->db_connection->real_escape_string($data['email']);
		$_password = $this->db_connection->real_escape_string($data['password']);
		
		echo "Validating email and password".PHP_EOL;
		try {
			// Create the SQL query to check email and retrieve the hashed password
		        $checkEmailQuery = "SELECT email, hashed_data FROM $tableName WHERE email = ?";
		        $stmt = $this->db_connection->prepare($checkEmailQuery);
		        $stmt->bind_param("s", $_email);
		        $stmt->execute();
		        $result = $stmt->get_result();
		        
		        if ($result->num_rows === 0) {
				// No record found with the given email
				echo "No records found with the email: $_email".PHP_EOL;
        			array_push($response["messages"], "No records found for $_email");
			} else {
				// Record found, check the password
				$row = $result->fetch_assoc();
				$hashedPasswordFromDB = $row['hashed_data'];

				// Verify the password
				if (password_verify($_password, $hashedPasswordFromDB) ) {
				    // Password matches, authentication successful
				    echo "Authentication successful!".PHP_EOL;
				    $response["status"] = true;
				    array_push($response["messages"], "Logged in as $_email");
				} else {
				    // Password does not match
				    echo "Error: Incorrect password.".PHP_EOL;
				    array_push($response["messages"], "Incorrect password for $_email");
				}
			}
			// Close the statement and the database connection
		        $stmt->close();
		        $this->db_connection->close();
		} catch (Exception $e) {
			// Handle the exception
    			echo "Error: " . $e->getMessage().PHP_EOL;
    			array_push($response["messages"], "Sorry, there was an issue processing your request: ". $e->getMessage() );
		}
		return $response;
	}
	
	public function registerUser($data) {
		$tableName = 'Users';
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		
		if (!$this->db_connection) {
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
		
		$_username    = $this->db_connection->real_escape_string($data['username']);
		$_email       = $this->db_connection->real_escape_string($data['email']);
		$_role        = $this->db_connection->real_escape_string($data['role']);
		$_created_on  = $this->db_connection->real_escape_string($data['created_on']);
		$_hashed_data = $this->db_connection->real_escape_string($data['hashed_data']);
		
		echo "Registering user $_username".PHP_EOL;
		
		try {	
			// Check if the email already exists in the database
		        $checkEmailQuery = "SELECT COUNT(*) AS email_count FROM $tableName WHERE email = ?";
			$checkStmt = $this->db_connection->prepare($checkEmailQuery);
		        $checkStmt->bind_param("s", $_email);
		        $checkStmt->execute();
		        $checkResult = $checkStmt->get_result();
		        $row = $checkResult->fetch_assoc();
		        $emailCount = $row['email_count'];

		        if ($emailCount > 0) {
				echo "Error: Email $_email already exists in the database.".PHP_EOL;
				array_push($response["messages"], "Sorry, an account already exists with the email $_email");
		        } else {
		        	// Create the SQL query
				$sql = "INSERT INTO $tableName (username, email, role, created_on, hashed_data) VALUES (?, ?, ?, ?, ?)";
				
				// Prepare the statement
				$stmt = $this->db_connection->prepare($sql);
				
				// Bind the parameters and execute the query
				$stmt->bind_param("sssss", $_username, $_email, $_role, $_created_on, $_hashed_data);
				$stmt->execute();
				
				// Check if the insertion was successful
				if ($stmt->affected_rows > 0) {
					echo "Record inserted successfully.".PHP_EOL;
					$response["status"] = true;
					array_push($response["messages"], "Registered $_username successfully.");
				} else {
					echo "Error inserting record: " . $stmt->error.PHP_EOL;
					# array_push($response["messages"], "Error registering $_username: " . $stmt->error);
					array_push($response["messages"], "Error registering $_username with email $_email");
				}

				// Close the statement and the database connection
				$stmt->close();
		        }
			// Close check statement and db connection
			$checkStmt->close();
			$this->db_connection->close();
		} catch (Exception $e) {
		    // Handle the exception
		    echo "Error: " . $e->getMessage().PHP_EOL;
		    array_push($response["messages"], "Sorry, we're experiecing a db error: ". $e->getMessage() );
		}
		
		return $response;
	}
} // close User class
