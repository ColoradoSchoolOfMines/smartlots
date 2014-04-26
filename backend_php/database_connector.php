<?php
	function connect_to_db() {
		$db = mysqli_connect("localhost", "wsn", "raspberryp1", "parking");
		if (mysqli_connect_errno()) {
			echo "Error: Could not connect to the database. Please try again later.";
			exit;
		}
		return $db;
	}
?>