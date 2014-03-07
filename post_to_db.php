<?php

function doPost($sensorID, $carcount, $battery, $db){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	


		//now we have previous car count, check to see if increasing or decresing
		
	//update the sensor table
	$previousCarCount = 0;
	if ($sensorID == 1 or $sensorID == 2){
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
*/



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
?>
