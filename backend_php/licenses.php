<?php

	require_once 'database_connector.php';

	function extract_all_licenses() {
		$db = connect_to_db();
		$select_statement = 
			"select licenses.id, licenses.number, licenses.state, images.url, " .
			"images.timestamp, lots.lotname " .
			"from licenses, images, lots, sensormapping " .
			"where licenses.image_id = images.id " .
			"and images.sensor_id = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid " .
			"order by images.timestamp desc"
		;

		$stmt = $db->prepare($select_statement);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo "Error: the license number was not saved correctly.";
			$db->close();
			exit;
		}

		$json = "[";

		$stmt->bind_result($id, $number, $state, $url, $timestamp, $lotname);

		while ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'number' => $number,
				'state' => $state,
				'url' => $url,
				'timestamp' => $timestamp,
				'lotname' => $lotname
			);
			$json = $json . json_encode($info) . ",";
		}

		echo substr_replace($json, "]", -1);

		$db->close();
	}

	function extract_all_intrada_licenses() {
		$db = connect_to_db();
		$select_statement = 
			"select intrada_licenses.id, intrada_licenses.number, intrada_licenses.state, images.url, " .
			"images.timestamp, lots.lotname " .
			"from intrada_licenses, images, lots, sensormapping " .
			"where intrada_licenses.image_id = images.id " .
			"and images.sensor_id = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid " .
			"order by images.timestamp desc"
		;

		$stmt = $db->prepare($select_statement);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo "Error: the license number was not saved correctly.";
			$db->close();
			exit;
		}

		$json = "[";

		$stmt->bind_result($id, $number, $state, $url, $timestamp, $lotname);

		while ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'number' => $number,
				'state' => $state,
				'url' => $url,
				'timestamp' => $timestamp,
				'lotname' => $lotname
			);
			$json = $json . json_encode($info) . ",";
		}

		echo substr_replace($json, "]", -1);

		$db->close();
	}

	function process_license_data($image_filepath, $image_url, $image_id){
		$db = connect_to_db();

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
		$license_number = "FAIL";
		$intrada_license_number = "FAIL";
		$state = NULL;

		// Start the OpenALPR engine on the ACMx server.
		// Sample result string (plate is LTM378):
		// - LTM378 confident: 92.4582 template_match: 0
		$unfiltered_result = exec("/var/license_plates/openalpr/src/alpr -r /var/license_plates/openalpr/runtime_data -n 1 $image_filepath");

		if ($unfiltered_result != '') {
			$exploded_result = explode(' ', $unfiltered_result);
			$license_number = $exploded_result[5];
		}

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
		if (!is_soap_fault($unfiltered_result)) {
			$exploded_result = explode(':', $unfiltered_result);
			$unfiltered_result = $exploded_result[2];
			if ($unfiltered_result == '[reject]') {
				$unfiltered_result = 'REJECT';
			}
			$exploded_result = explode(',', $unfiltered_result);
			$intrada_license_number = $exploded_result[0];
			if ($unfiltered_result != 'REJECT') {
				$state = $exploded_result[2];
			}
		}

		// $license_number will be FAIL if OpenALPR didn't find a number, or the plate/partial.
		// $intrada_license_number will be FAIL if Intrada had an error,
		// [reject] if it could not find a number, or the plate/partial.
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

	function extract_license($licenseID) {
		// Try to connect to the database
		$db = connect_to_db();
		
		$select_statement = 
			"select licenses.id, licenses.number, licenses.state, images.url, " .
			"images.timestamp, lots.lotname " .
			"from licenses, images, lots, sensormapping " .
			"where licenses.id = ? " .
			"and licenses.image_id = images.id " .
			"and images.sensor_id = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid"
		;

		$stmt = $db->prepare($select_statement);

		// Bind the parameter to the query
		$stmt->bind_param("i", $licenseID);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not find license. Please try again later.";
			$db->close();
			exit;
		}

		$stmt->bind_result($id, $number, $state, $url, $timestamp, $lotname);

		if ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'number' => $number,
				'state' => $state,
				'url' => $url,
				'timestamp' => $timestamp,
				'lotname' => $lotname
			);
			echo json_encode($info);
		}

		$db->close();
	}

	function extract_intrada_license($licenseID) {
		// Try to connect to the database
		$db = connect_to_db();
		
		$select_statement = 
			"select intrada_licenses.id, intrada_licenses.number, intrada_licenses.state, images.url, " .
			"images.timestamp, lots.lotname " .
			"from intrada_licenses, images, lots, sensormapping " .
			"where intrada_licenses.id = ? " .
			"and intrada_licenses.image_id = images.id " .
			"and images.sensor_id = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid"
		;

		$stmt = $db->prepare($select_statement);

		// Bind the parameter to the query
		$stmt->bind_param("i", $licenseID);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not find license. Please try again later.";
			$db->close();
			exit;
		}

		$stmt->bind_result($id, $number, $state, $url, $timestamp, $lotname);

		if ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'number' => $number,
				'state' => $state,
				'url' => $url,
				'timestamp' => $timestamp,
				'lotname' => $lotname
			);
			echo json_encode($info);
		}

		$db->close();
	}

?>
