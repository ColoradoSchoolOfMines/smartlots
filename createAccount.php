<?php

	function create_account($post_array) {

		// Try to connect to the database
		@ $db = new mysqli('localhost', 'smartlots', 'M!nesRules', 'parking');
		if (mysqli_connect_errno()) {
			echo
				"<div class = \"serverMessage\">" .
					"Error: Could not connect to the database. Please try again later.<br>" .
				"</div>";
			exit;
		}

		// Get field information from Create Account page
		$username = $post_array['username'];
		$password = md5($post_array['password']);

		// Check if username exists
		$check_user_name_query = "select id from user where username = ?";
		$stmt = $db->prepare($check_user_name_query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($check_user_name_query_result);
		$stmt->fetch();
		$stmt->free_result();

		$check_user_name_query_result;

		if ($check_user_name_query_result != null) {
			echo
				"<div class = \"serverMessage\">" .
					"We're sorry, the username '" . $username . "' has already been taken.<br>" .
					"<a class = \"serverMessage\" href = \"#\" onclick = \"closeAccountPopup()\">Return to Home</a>" .
				"</div>";
				exit;
		}

		// Prepare user query
		$create_user_query = "insert into user (username, password) values (?, ?)";
		$stmt = $db->prepare($create_user_query);

		// Bind the parameters to the query
		$stmt->bind_param("ss", $username, $password);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo
				"<div class = \"serverMessage\">" .
					"Error: Your account was not successfully created. Please try again later.<br>" .
					"<a class = \"serverMessage\" href = \"#\" onclick = \"closeAccountPopup()\">Return to Home</a>" .
				"</div>";
			exit;
		} else {
			echo
				"<div class = \"serverMessage\">" .
					"Congratulations, your account was successfully created.<br>" .
					"<a class = \"serverMessage\" href = \"#\" onclick = \"closeAccountPopup()\">Return to Home</a>" .
				"</div>";
		}

		// Close the database
		$db->close();

	}

?>