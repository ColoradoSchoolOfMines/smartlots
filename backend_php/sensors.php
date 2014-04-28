<?php

	require_once 'database_connector.php';

	function extract_all_sensors() {
		$db = connect_to_db();
		$select_statement = 
			"select sensors.sensorid, sensors.carcount, sensors.battery, " .
			"sensors.lastcount, lots.lotname " .
			"from sensors, lots, sensormapping " .
			"where sensors.sensorid = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid"
		;

		$stmt = $db->prepare($select_statement);

		if ( !($stmt->execute()) ) {
			// Result was false (error inserting into database)
			echo "Error: Could not retrieve sensor data.";
			$db->close();
			exit;
		}

		$json = "[";

		$stmt->bind_result($id, $carcount, $battery, $lastcount, $lotname);

		while ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'carcount' => $carcount,
				'battery' => $battery,
				'lastcount' => $lastcount,
				'lotname' => $lotname
			);
			$json = $json . json_encode($info) . ",";
		}

		echo substr_replace($json, "]", -1);

		$db->close();
	}

	function extract_sensor($sensorID) {
		// Try to connect to the database
		$db = connect_to_db();
		
		$select_statement = 
			"select sensors.sensorid, sensors.carcount, sensors.battery, " .
			"sensors.lastcount, lots.lotname " .
			"from sensors, lots, sensormapping " .
			"where sensors.sensorid = ? " .
			"and sensors.sensorid = sensormapping.sensorid " .
			"and sensormapping.lotid = lots.lotid"
		;

		$stmt = $db->prepare($select_statement);

		// Bind the parameter to the query
		$stmt->bind_param("i", $sensorID);

		if ( !($stmt->execute()) ) {
			echo "Error: Could not find sensor. Please try again later.";
			$db->close();
			exit;
		}

		$stmt->bind_result($id, $carcount, $battery, $lastcount, $lotname);

		if ( $stmt->fetch() ) {
			$info = array(
				'id' => $id,
				'carcount' => $carcount,
				'battery' => $battery,
				'lastcount' => $lastcount,
				'lotname' => $lotname
			);
			echo json_encode($info) . ",";
		}

		$db->close();
	}

?>