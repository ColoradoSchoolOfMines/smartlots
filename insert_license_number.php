<?php

	function process_license_data($image_filepath, $image_url, $image_id){
		$db = mysqli_connect("localhost", "wsn", "raspberryp1", "parking");
		
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
		// Start the OpenALPR engine on the ACMx server.
		// Sample result string (plate is LTM378):
		// - LTM378 confident: 92.4582 template_match: 0
		$unfiltered_result = exec("/var/license_plates_openalpr/src/alpr -r /var/license_plates/openalpr/runtime_data -n 1 $image_filepath");
		$exploded_result = explode(' ', $unfiltered_result);
		/*$c = count($exploded_result);
		$f = fopen("brandondebug.txt",'w');
		fwrite($f, $c);
		fwrite($f, "$image_filepath");
		fwrite($f, '\n');
		fwrite($f, var_dump($unfiltered_result));
		fwrite($f, var_dump($exploded_result));
		fclose($f);*/
		$license_number = $exploded_result[5];

		// Create a SOAP request for Intrada ALPR cloud service.
		$intrada_client = new SoapClient('http://intrada2.cloudapp.net/IntradaService.asmx?WSDL',
		    array('soap_version' => SOAP_1_2));
		$intrada_request = $intrada_client->IntradaRecognizeDirectPassage(
		    array(
		      'project' => 'brodrigu.demo',
		      'key' => '8053',
		      'images' => array($image_url),
		      'metadata' => array()
		    )
		);

		// Sample result string (plate is LTM378):
		// RESULT:{hash}:{plate},{confidence},{state},{confidence},{coordinates of license plate in image}:{execution time}
		// RESULT:592E1EE8D1DE427BB6E0042F34745523:LTM378,750,USA_CO,750,(309,156),(503,159),(309,206),(503,207):1594
		$unfiltered_result = $intrada_request->IntradaRecognizeDirectPassageResult;
		$exploded_result = explode(':', $unfiltered_result);
		$unfiltered_result = $exploded_result[2];
		$exploded_result = explode(',', $unfiltered_result);
		$intrada_license_number = $exploded_result[0];
		$state = $exploded_result[2];
		 
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
