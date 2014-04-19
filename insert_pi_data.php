<?php
	require 'insert_license_number.php';

	function process_pi_data($post_array, $files_array){
		$db = mysqli_connect("localhost", "smartlots", "M!nesRules", "parking");
		
		if (mysqli_connect_errno()) {
			echo
				"Error: Could not connect to the database. Please try again later.";
			exit;
		}

		insert_pi_data($post_array, $files_array, $db); // add parameters to this function as needed
		
	}

	// Inserts the relevant pi data to the database, and kicks off
	// the 'insert_license_data' function, which will run the alpr software.
	function insert_pi_data($post_array, $files_array, $db) {
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

		$insert_query = "insert into images (sensor_id, url) values (?, ?)";
		$stmt = $db->prepare($insert_query);
		$stmt->bind_param("ss", $id, $url);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo "Error: the image was not saved correctly.";
			$db->close();
			exit;
		}

		$select_query = "select id from images where url = ?";
		$stmt = $db->prepare($select_query);
		$stmt->bind_param("s", $url);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not access database.";
		}

		$stmt->bind_result($image_id);
		$stmt->fetch();
		$stmt->free_result();

		$db->close();

		// Kick off the script that will convert the image to a string.
		process_license_data($file_path, $url, $image_id);

	}

?>