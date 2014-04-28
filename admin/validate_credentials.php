<?php

	function authorize() {
		if ( !isset($_SERVER['PHP_AUTH_USER']) ) {
		  	header('WWW-Authenticate: Basic realm="Administrator Realm"');
		    header('HTTP/1.0 401 Unauthorized');
		    die ("Error: Permission Denied. Administrator username/password is incorrect.");
		}

		$username = $_SERVER['PHP_AUTH_USER'];
		$password = $_SERVER['PHP_AUTH_PW'];

		if (!validate($username, $password)) {
		    header('WWW-Authenticate: Basic realm="Administrator Realm"');
		    header('HTTP/1.0 401 Unauthorized');
		    die ("Error: Permission Denied. Administrator username/password is incorrect.");
		}
	}

	function validate($username, $password) {

		if ( is_null($username) || is_null($password) ) {
			return false;
		}

		// Try to connect to the database
		@ $db = new mysqli('localhost', 'wsn', 'raspberryp1', 'parking');
		if (mysqli_connect_errno()) {
			echo 'Error: Could not connect to database. Please try again later.';
			return false;
		}

		// Prepare query statement and execute it
		$get_user_query = "select password from users where username=?";
		$stmt = $db->prepare($get_user_query);

		$stmt->bind_param("s", $username);
		$stmt->bind_result($user_password);
		if ( !($stmt->execute()) ) {
			// Close database
			$db->close();
			// Result was false (error with query)
			return false;
		}
		$stmt->fetch();
		$stmt->free_result();
		// Close database
		$db->close();

		if ($user_password == md5($password)) {
			return true;
		} else {
			return false;
		}

	}

?>