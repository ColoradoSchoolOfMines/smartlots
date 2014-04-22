<?php

	function connect_to_db() {
		// Try to connect to the database
		$db = new mysqli('localhost', 'smartlots', 'M!nesRules', 'parking');

		if (mysqli_connect_errno()) {
			echo
				"Error: Could not connect to the database. Please try again later.";
			exit;
		}
		return $db;
	}
	
	function extract_all_users() {
		$db = connect_to_db();
		$select_query = "select id, username from users";
		// Finish querying database

		echo "[\"" . "{\"username\":\"tsallee\", \"id\":1}" . "\",\"" . "{\"username\":\"brodriguez\", \"id\":2}" . "\"]";
		
	}

	function delete_user($userID) {

	}

?>