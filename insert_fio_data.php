<?php

	function process_fio_data($post_array){
		$db = mysqli_connect("localhost", "smartlots", "M!nesRules", "parking");
		if (mysqli_connect_errno()) {
			echo "Error: Could not connect to the database. Please try again later.";
			exit;
		}
		insert_fio_data($post_array["id"], $post_array["carcount"], $post_array["voltage"], $post_array["temperature"], $post_array["window"], $db);
		//echo "Fio Data Successfully Received.";
	}

	// insert fio data to the database and write an entry to the log
	function insert_fio_data($sensorID, $carcount, $voltage, $temperature, $window, $db) {

		//get the lastcount value
		$query = "select carcount from sensors where sensorid = ".$sensorID;
		$result = $db->query($query);
		$row = mysqli_fetch_array($result);
		$previousCount = $row["carcount"];

		//insert into sensors db
		$query = "insert into parking.sensors(sensorid, carcount, battery, lastcount) values (?, ?, ?, ?) on duplicate key update carcount=?,battery=?,lastcount=?";
		$statement = $db->prepare($query);
		$battery = (($voltage-2.7/(4.23-2.7))*100);
		$statement->bind_param("iiiiiii", $sensorID, $carcount, $battery, $previousCount, $carcount, $battery, $previousCount);
		if( !($statement->execute())){
			echo "Error. could not insert into the sensor table";
			$db->close();
			exit;
		}
		$statement->close();


		//get the lot associated to sensor
		$query = "select lotid from parking.sensormapping where sensorid=?";
		$statement = $db->prepare($query);
		$statement->bind_param("i", $sensorID);
		if( !($statement->execute())){
			echo "Error. could not insert into the sensormapping table";
			$db->close();
			exit;
		}
		$lotID = -1;
		$statement->bind_result($lotID);
		$statement->fetch(); 
		$statement->close();
		
		// Get all the sensors associated to that lot. can also stop using prepared statments as of now since any query is being genrated from info already in the database
		$query = "select sensorid from parking.sensormapping where lotid = ".$lotID;
		$result = $db->query($query);
		if($result == false){
			echo "Could not pull sensors attached to lotID ".$lotID;
			$db->close();
			exit;
		}
		$lotSensors = [];
		while($row = mysqli_fetch_array($result)){
			array_push($lotSensors, $row["sensorid"]);
		}
		
		// Calculate the differential for the lot
		$totalChange = 0;
		while(count($lotSensors)){
			$query = "select carcount, lastcount from parking.sensor where sensorid = ".array_pop($lotSensors);
			$result = $db->query($query);
			if($result == false){
				echo "Could not pull sensor data attached to sensorID";
				$db->close();
				exit;
			}
			$row = mysqli_fetch_array($result);
			$totalChange += $row["carcount"] - $row["lastcount"];
		}	

		// Calculate the new value for lot
		$query = "select carcount from lots where lotid = ".$lodID;
		$result = $db->query($query);
		if($result == false){
			echo "Could not pull carcount attached to lotID ".$lotID;
			$db->close();
			exit;
		}
		$row = mysqli_fetch_array($result);
		$newCount = $row["carcount"] + $totalChange;

		//post the new value for the lot
		$query = "update parking.lots set carcount = ".$newCount." where lotid = ".$lotID;
		$db->query($query);
		
		//now that the data is updated, post to the database log
		postToLog($sensorID, $carcount, $voltage, $temperature, $window, $db);
			
		
	}

	function postToLog($sensorID, $carcount, $voltage, $temperature, $window, $db){
		
		$query = "insert into parking.log(sensorid, localcount, date, voltage, temp, windowdata) values (?, ?, NOW(), ?, ?, ?)";
		$statement = $db->prepare($query);
		$statement->bind_param("iiiis", $sensorID, $carcount, $voltage, $temperature, $window);
		if( !($statement->execute())){
			echo "Error. could not insert into the log table";
			$db->close();
			exit;
		}
		$statement->close();

	}

?>
