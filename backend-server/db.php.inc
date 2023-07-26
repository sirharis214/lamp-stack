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
		        $checkEmailQuery = "SELECT email, role, hashed_data FROM $tableName WHERE email = ?";
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
				    $response["role"] = $row['role'];
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
		    array_push($response["messages"], "Sorry, we're experiencing a db error: ". $e->getMessage() );
		}
		
		return $response;
	}
} // close User class

class Data {
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
	}
	
	public function deleteUser($data) {
		$tableName = 'Users';
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		if (!$this->db_connection) {
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
		
		$userId = $data['id'];
		$username = $data['username'];
		$email = $data['email'];
		
		echo "Performing deleteUser".PHP_EOL;
		try {
			// Prepare the query
			$query = "DELETE FROM $tableName WHERE id = ? AND email = ?";

			// Prepare and execute the statement
			$stmt = $this->db_connection->prepare($query);
			$stmt->bind_param("ss", $userId, $email);
			$result = $stmt->execute();

			if ($result) {
				echo "Delete query executed successfully.".PHP_EOL;
				$response["status"] = true;
				array_push($response["messages"], "Deleted user $username");
			} else {
				// If there was an error executing the query, you can check the error message for debugging
				echo "Error executing the delete query: " . $stmt->errorInfo()[2].PHP_EOL;
				array_push($response["messages"], "Error executing the delete query: " . $stmt->errorInfo()[2]);
			}
		} catch (Exception $e) {
		    // Handle the exception
		    echo "Error: " . $e->getMessage().PHP_EOL;
		    array_push($response["messages"], "Sorry, we're experiencing a db error: ". $e->getMessage() );
		}
		return $response; 
	}
	
	public function updateUser($data) {
		$tableName = 'Users';
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		if (!$this->db_connection) {
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
		
		$userId = $data['id'];
		$newUsername = $data['username'];
		$newEmail = $data['email'];
		$newRole = $data['role'];
		$updated_on = $data['updated_on'];

		echo "Performing updateUser".PHP_EOL;
		try {
			// Check if any of the user's info has changed in the new form submission
			// Query the database to get the original user data
			$sql = "SELECT * FROM Users WHERE id = '$userId'";
			$result = mysqli_query($this->db_connection, $sql);

			// Assuming you have only one user with the given username
			$user = mysqli_fetch_assoc($result);

			// Check if the fields have changed
			if ($user["username"] !== $newUsername || $user["email"] !== $newEmail || $user["role"] !== $newRole) {
				// Update the user's information in the database
				$sql = "UPDATE Users SET username = '$newUsername', email = '$newEmail', role = '$newRole', updated_on = '$updated_on' WHERE id = '$userId'";
				mysqli_query($this->db_connection, $sql);
				$response["status"] = true;
				array_push($response["messages"], "Updated $newUsername");
			} else {
				array_push($response["messages"], "$newUsername Information Unchanged");
			}
		} catch (Exception $e) {
		    // Handle the exception
		    echo "Error: " . $e->getMessage().PHP_EOL;
		    array_push($response["messages"], "Sorry, we're experiencing a db error: ". $e->getMessage() );
		}
		return $response;    
	}
	
	public function get_all_users_data() {
		$tableName = 'Users';
		$response = array();
		$response["status"] = false;
		$response["messages"] = array();
		
		if (!$this->db_connection) {
			array_push($response["messages"], "Sorry, we're currently experiencing database issues, Please try again later!");
			return $response;
		}
		
		#echo "Performing get_all_users_data".PHP_EOL;
		try {
			$query = "SELECT id,username,email,role,created_on,updated_on FROM $tableName";
		    $stmt = $this->db_connection->prepare($query);
		    $stmt->execute();
		    $result = $stmt->get_result();
		        
		    if ($result->num_rows > 0) {
		        $response["status"] = true;
				array_push($response["messages"], "data retrieved for get_all_users_data");
				$response["data"] = array();
				// Output data of each row
			    while ($row = $result->fetch_assoc()) {
					array_push($response["data"], $row);
			    }
			} else {
				$msg = "No results found.";
			    echo $msg.PHP_EOL;
			    array_push($response["messages"], $msg);
			}
		} catch (Exception $e) {
		    // Handle the exception
		    echo "Error: " . $e->getMessage().PHP_EOL;
		    array_push($response["messages"], "Sorry, we're experiencing a db error: ". $e->getMessage() );
		}
		return $response;
	}
}
