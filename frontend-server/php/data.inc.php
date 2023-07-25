<?php
require_once('client.php');
class Data {
	function get_all_users_data() {
		$request = array();
		$request['type'] = "get_all_users_data";
		$response = data($request);
		if ($response['status']==true) {
			# data was able to be retrieved
			$_SESSION['table_data'] = true;
			return $response['data'];
		} else {
			$_SESSION['table_data'] = false;
		}
	}
}

