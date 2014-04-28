<?php

	require_once 'database_connector.php';

	function create_user($post_array) {

		// Try to connect to the database
		$db = connect_to_db();

		// Get field information from Create Account page
		$username = $post_array['username'];
		$password = md5($post_array['password']);
		$confirm_password = md5($post_array['confirm_password']);

		if (
			is_null($username) || is_null($password) || is_null($confirm_password)
			|| $username == "" || $password == ""  || $confirm_password == ""
		) {
			echo
				"Please fill out all fields.<br>" .
				"<a href = /smartlots/admin/create_account.php>" .
					"Try again" .
				"</a>"
			;
			$db->close();
			exit;
		}

		if ( $password != $confirm_password ) {
			echo
				"Passwords don't match!<br>" .
				"<a href = /smartlots/admin/create_account.php>" .
					"Try again" .
				"</a>"
			;
			$db->close();
			exit;
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
				"We're sorry, the username '" . $username . "' has already been taken.<br>" .
				"<a href = /smartlots/admin/create_account.php>" .
					"Try again" .
				"</a>"
			;
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
				"Error: Your account was not successfully created. Please try again later.<br>" .
				"<a href = /smartlots/admin/create_account.php>" .
					"Try again" .
				"</a>"
			;
			$db->close();
			exit;
		}

		// Close the database connection
		$db->close();

	}

	function extract_all_users() {
		// Try to connect to the database
		$db = connect_to_db();

		$select_statement = 
			"select users.id, users.username from users";
		;

		$stmt = $db->prepare($select_statement);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not get users.";
			$db->close();
			exit;
		}

		$json = "[";

		$stmt->bind_result($id, $username);

		while ( $stmt->fetch() ) {
			$info = array('id' => $id, 'username' => $username);
			$json = $json . json_encode($info) . ",";
		}

		echo substr_replace($json, "]", -1);

		$db->close();
	}

	function delete_user($userID) {
		// Try to connect to the database
		$db = connect_to_db();
		
		$delete_statement = 
			"delete from users where id = ?";
		;

		$stmt = $db->prepare($delete_statement);

		// Bind the parameter to the query
		$stmt->bind_param("i", $userID);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not delete user. Please try again later.";
			$db->close();
			exit;
		}

		$db->close();
	}

	function extract_user($userID) {
		// Try to connect to the database
		$db = connect_to_db();
		
		$select_statement = 
			"select id, username from users where id = ?";
		;

		$stmt = $db->prepare($select_statement);

		// Bind the parameter to the query
		$stmt->bind_param("i", $userID);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not find user. Please try again later.";
			$db->close();
			exit;
		}

		$stmt->bind_result($id, $username);

		if ( $stmt->fetch() ) {
			$info = array('id' => $id, 'username' => $username);
			echo json_encode($info);
		}

		$db->close();
	}

?>