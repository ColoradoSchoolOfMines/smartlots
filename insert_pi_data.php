<?php

	function process_pi_data($post_array, $files_array){
		$db = mysqli_connect("localhost", "smartlots", "M!nesRules", "parking");
		insert_pi_data($post_array, $files_array); // add parameters to this function as needed
		// Kick off Brandon's script
	}

	// add parameters to this function as needed
	function insert_pi_data($post_array, $files_array) {
		$d = new DateTime();
		list($usec, $sec) = explode(" ", microtime());
		$timestamp = $d->format('Y-m-d_H:i:s') . '.' . substr($usec, 2);

		$file_path = "/var/license_plates/images/$timestamp" . ".png";
		$url = "http://acmxlabs.org/smartlots/images/$timestamp" . ".png";
		$id = $post_array['id'];

		move_uploaded_file( $_FILES['image']['tmp_name'], "$file_path" );

		echo "Pi Data Successfully Received.";
		echo "Url: $url\n";
		echo "Id: $id\n";

	}

?>