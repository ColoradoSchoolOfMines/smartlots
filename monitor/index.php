<?php
//Roy Stillwell, Andrew Koeling, Thomas
//Modified 2.24.14 for json return by Roy Stillwell
//This script connects to the parking database and returns a json object with a list of all lots and status codes.

//setup error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$db = mysqli_connect("localhost", "parking2", "JK5zhB5dD3xrxQLR", "parking");  //creating db object for mysql server on local vm with user acmx and password acmx on database parking

$query = 'select lotname, lotid, carcount, carmax from parking.lots'; // just pull all data from database. Does not use prepared statment.
$result = $db->query($query); //do query

//echo "<table>"; //pretty table
$lotArray = array();
while ($row = mysqli_fetch_array($result)) { //iterate through all rows reterived.
    $array = array('lotid' => $row['lotid'], 'lotname' => $row['lotname'], 'carcount' => $row['carcount'], 'carmax' => $row['carmax']);
                array_push($lotArray, $array);
}
    header('Content-type: application/json');  //set header as json. type
    echo json_encode($lotArray);  //return as json.
?>
