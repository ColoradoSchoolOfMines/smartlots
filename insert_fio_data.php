<?php

function process_fio_data($dataArray){
	//$db = mysqli_connect("parking", "root", "M!nesRules", "parking");
	//postToSmartLots($dataArray["id"], $dataArray["carcount"], $dataArray["voltage"], $dataArray["temperature"], $dataArray["window"], $db);
	echo "fuck you, you dirty bastard";
}


function postToSmartLots($sensorID, $carcount, $voltage, $temperature, $window, $db){ // post to the database and do the post to the log


	//get the lastcount value
	$query = "select carcount from sensors where sensorid = ".$sensorID;
	$result = $db->query($query);
	$row = mysqli_fetch_array($result);
	$previousCount = $row["carcount"];



	//insert into sensors db
	$query = "insert into parking.sensors(sensorid, carcount, battery, lastcount) values (?, ?, ?, ?) on duplicate key update carcount=?,battery=?,lastcount=?";
	$statement = $db->prepare($query);
	$battery = (($voltage-2.7/(4.23-2.7))*100);
	$statement->bind_param("iiiii", $sensorID, $carcount, $battery, $carcount, $battery, $previousCount);
	$statement->execute();
	$statement->close();

	//get the lot associated to sensor
	$query = "select lotid from parking.sensorsmapping where sensorid=?";
	$statement = $db->prepare($query);
	$statement->bind_param("i", $sensorID);
	$statement->execute();
	$lotID = -1;
	$statement->bind_result($lotID);
	$statement->fetch(); 
	$statement->close();
	
	//get all the sensors associated to that lot. can also stop using prepared statments as of now since any query is being genrated from info already in the database
	$query = "select sensorid from parking.sensorsmapping where lotid = ".$lotID;
	$result = $db->query($query);
	$lotSensors = [];
	while($row = mysqli_fetch_array($result)){
		array_push($lotSensors, $row["sensorid"]);
	}
	
	//calculate the differential for the lot
	$totalChange = 0;
	while(count($lotSensors)){
		$query = "select carcount, lastcount from parking.sensor where sensorid = ".array_pop($lotSensors);
		$result = $db->query($query);
		$row = mysqli_fetch_array($result);
		$totalChange += $row["carcount"] - $row["lastcount"];
	}	

	//calculat the new value for lot
	$query = "select carcount from lots where lotid = ".$lodID;
	$result = $db->query($query);
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
	$statement->execute();
	$statement->close();
	
}





/*
function doPost($sensorID, $carcount, $battery, $db){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	


		//now we have previous car count, check to see if increasing or decresing
		
	//update the sensor table
	$previousCarCount = 0;
	if ($sensorID == 1 or $sensorID == 2 or $sensorID == pow(2, 32)-1){
		$query = 'select carcount from parking.sensors where sensorid=?';
		$statement = $db->prepare($query);
		$statement->bind_param("i", $sensorID);
		$statement->execute();
		$statement->bind_result($previousCarCount); 
		$statement->fetch();
		$statement->close();
	}	

	$query = 'insert into parking.sensors (sensorid, carcount, battery) values (?, ?, ?) on duplicate key update carcount=?,battery=?'; // prepare the query making sure that we update if the key is already in there
	$statement = $db->prepare($query);
	$statement->bind_param("iiiii", $sensorID, $carcount, $battery, $carcount, $battery); //bind the params. its (id, carcount, battery)...update carcount, battery
	$statement->execute();
	$statement->close();

	//update the lot sum
	if ($sensorID==1 or $sensorID==2){
		$query = "select sensorid, carcount from parking.sensors where (sensorid = 1 or sensorid =2)";
		$result = $db->query($query);
		$sensorCount1 = 0;
		$sensorCount2 = 0;
		while ($row = mysqli_fetch_array($result)) {
			if ($row["sensorid"] == 1 ) {
				$sensorCount1 = $row["carcount"];
			} else {
				$sensorCount2 = $row["carcount"];
			}
		}
		$lotCount1 = 0;
		$lotCount2 = 0;
		if ( $sensorCount1 > 183) {
			$lotCount2 += $sensorCount1-183;
			$lotCount1 += 183;
		}
		else if( $sensorCount1 <0){
			$lotCount1 += 0;
			$lotCount2 += $sensorCount1;
		} 
		else {
			$lotCount1 += $sensorCount1;	
		}
		if  ($sensorCount2 > 174) {
			$lotCount1 += $sensorCount2-174;
			$lotCount2 += 174;
		}
		else if( $sensorCount2 <0){
			$lotCount2 += 0;
			$lotCount1 += $sensorCount2;
		} 
		else {
			$lotCount2 += $sensorCount2;	
		}

		$query1 = "update parking.lots set carcount=".$lotCount1." where lotid=1";
		$query2 = "update parking.lots set carcount=".$lotCount2." where lotid=2";
		$db->query($query1);
		$db->query($query2);
	}
}

			/*
			if ($carcount > $previousCarCount){ //we are increaing
				if ($sensorID ==1) {
					if($carcount > 183){ //overflow
						$difference = $carcount - $previousCarCount;
						$query = 'select carcount from parking.lots where lotid=2';
						$result = $db->query($query);
						$row = mysqli_fetch_array($result);
						$count = $row['carcount'];
						$newCarCount = $count + $difference;
						// now we update the lots car count
						
						$query = 'update parking.lots set carcount='.$newCarCount.' where lotid=2';
						$db->query($query);
				
					}
					else{ //increasing but room in upper lot
						updateNormal($sensorID, $db);
					}
				}
				else { //sensor id = 2
					if($carcount > 174){//overflow
						$difference = $carcount - $previousCarCount;
						$query = 'select carcount from parking.lots where lotid=1';
						$result = $db->query($query);
						$row = mysqli_fetch_array($result);
						$count = $row['carcount'];
						$newCarCount = $count + $difference;
						// now we update the lots car count
						
						$query = 'update parking.lots set carcount='.$newCarCount.' where lotid=1';
						$db->query($query);
					
					}
					else{
						updateNormal($sensorID, $db);
					}
				}
			}
			elseif($carcount < $previousCarCount){ //were decreasing
				if($carcount < 0){
					$difference = $previousCarCount - $carcount;
					//first get other lots number
					if ($sensorID == 1)
						$query = "select carcount from parking.lots where lotid=2";
					else
						$query = "select carcount from parking.lots where lotid=1";

					$result = $db->query($query); 
					$row = mysqli_fetch_array($result);
					$count = $row['carcount'];
					$newCarCount = $count - $difference;
					if ($sensorID == 1)
						$query = 'update parking.lots set carcount='.$newCarCount.' where lotid=2';
					else
						$query = 'update parking.lots set carcount='.$newCarCount.' where lotid=1';
					$db->query($query);
				}	
				else{
					updateNormal($sensorID, $db);
				}
			}
			else{
				//do nothing, nothing to update if count is somehow the same.
			}

		}



function updateNormal($sensorID, $db){
	echo "******".$sensorID."******";
	
	//your updating the lower or upper ctml lot
	$query = "select sum(carcount), map2.lotid from parking.sensormapping, parking.sensors, parking.sensormapping as map2 where sensormapping.sensorid=sensors.sensorid and map2.lotid = sensormapping.lotid and map2.sensorid=?";
	if(!$statement = $db->prepare($query))
		echo "PREPARE FAILED: (". $db->errno .") ". $db->error;
	$statement->bind_param("i", $sensorID);
	$statement->execute();
	$totalCars = 0;
	$lotId = 0;
	$statement->bind_result($totalCars, $lotId);
	$statement->fetch();
	$statement->close();
	
	echo $totalCars."\n";

	$query = 'update parking.lots set carcount=? where lots.lotid=?'; // prepare the query making sure that we update if the key is already in there
	$statement = $db->prepare($query);
	$statement->bind_param("ii", $totalCars, $lotId); //bind the params. its (id, carcount, battery)...update carcount, battery
	$statement->execute();
	$statement->close();

}
*/
?>
