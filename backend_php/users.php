<?php

	require_once 'database_connector.php';

	function create_user($post_array) {

		// Try to connect to the database
		$db = connect_to_db();

		// Get field information from Create Account page
		$username = $post_array['username'];
		$password = md5($post_array['password']);

		if ( is_null($username) || is_null($password) || $username == "" || $password == "" ) {
			echo
				"<div class = \"serverMessage\">" .
					"Please enter a valid username and password." .
				"</div>";
		}

		// Check if username exists
		$check_user_name_query = "select id from users where username = ?";
		$stmt = $db->prepare($check_user_name_query);

		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($check_user_name_query_result);
		$stmt->fetch();
		$stmt->free_result();

		// $check_user_name_query_result;

		if ($check_user_name_query_result != null) {
			echo
				"<div class = \"serverMessage\">" .
					"We're sorry, the username '" . $username . "' has already been taken.<br>" .
					"<a class = \"serverMessage\" href = \"/smartlots\">Return to Home</a>" .
				"</div>";
			$db->close();
			exit;
		}

		// Prepare user query
		$create_user_query = "insert into users (username, password) values (?, ?)";
		$stmt = $db->prepare($create_user_query);

		// Bind the parameters to the query
		$stmt->bind_param("ss", $username, $password);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo
				"<div class = \"serverMessage\">" .
					"Error: Your account was not successfully created. Please try again later.<br>" .
					"<a class = \"serverMessage\" href = \"/smartlots\">Return to Home</a>" .
				"</div>";
			$db->close();
			exit;
		} else {
			echo
				"<div class = \"serverMessage\">" .
					"Congratulations, your account was successfully created.<br>" .
					"<a class = \"serverMessage\" href = \"/smartlots\">Return to Home</a>" .
				"</div>";
		}

		// Close the database
		$db->close();

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