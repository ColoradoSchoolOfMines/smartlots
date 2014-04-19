<?php

	function process_license_data($image_filepath, $image_url, $image_id){
		$db = mysqli_connect("localhost", "smartlots", "M!nesRules", "parking");
		
		if (mysqli_connect_errno()) {
			echo
				"Error: Could not connect to the database. Please try again later.";
			exit;
		}


		$license_info = get_license_number($image_filepath, $image_url);
		
		$license_number = $license_info[0];
		$state = $license_info[1];

		insert_license_number($license_number, $state, $image_id, $db);
		
	}

	// Gets the license plate number from the alpr software.
	function get_license_number($image_filepath, $image_url) {
		$license_number = "614-VIP";
		$state = NULL;		

		/*
		 *
		 *
		 * BRANDON: THIS IS YOUR FUNCTION TO FILL OUT
		 *
		 *
		 */

		$license_info = array(
			0 => $license_number,
			1 => $state
		);

		return $license_info;
	}

	// Insert the license plate numbers to the database
	function insert_license_number($license_number, $state, $image_id, $db) {
		$insert_query = "";
		if ( is_null($state) ) {
			$insert_query = "insert into licenses (number, image_id) values(?, ?)";
		} else {
			$insert_query = "insert into licenses (number, state, image_id) values (?, ?, ?)";
		}

		$stmt = $db->prepare($insert_query);

		if ( is_null($state) ) {
			$stmt->bind_param("si", $license_number, $image_id);
		} else {
			$stmt->bind_param("ssi", $license_number, $state, $image_id);
		}

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo "Error: the license number was not saved correctly.";
			$db->close();
			exit;
		}

		$db->close();
	}

?>