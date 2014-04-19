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
		$intrada_license_number = $license_info[1];
		$state = $license_info[2];

		insert_license_number($license_number, $state, $image_id, $db);
		insert_intrada_license_number($intrada_license_number, $state, $image_id, $db);
		
		$db->close();

	}

	// Gets the license plate number from the alpr software.
	function get_license_number($image_filepath, $image_url) {
		$license_number = "614-VIP";
		$intrada_license_number = "XXX-OOO";
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
			1 => $intrada_license_number,
			2 => $state
		);

		return $license_info;
	}

	// Insert the license plate number to the database
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
	}

	// Insert the intrada license plate number to the database
	function insert_intrada_license_number($license_number, $state, $image_id, $db) {
		$insert_query = "";
		if ( is_null($state) ) {
			$insert_query = "insert into intrada_licenses (number, image_id) values(?, ?)";
		} else {
			$insert_query = "insert into intrada_licenses (number, state, image_id) values (?, ?, ?)";
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
	}

?>