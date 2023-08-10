<?php
session_start();
// Check if the user is authenticated (session variable is set to true)
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    // Redirect the user back to the login page
    header('Location: ../../index.php');
    exit();
}
// Check if last activity was set
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
	// last request was more than 15 minutes ago
	session_unset(); // unset $_SESSION variable for the run-time
	session_destroy(); // destroy session data in storage
	header("Location: ../../index.php"); // redirect to login page
} else {
	$_SESSION['last_activity'] = time(); // update last activity time stamp
}

