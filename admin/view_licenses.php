<!DOCTYPE html>

<?php
    require 'authorization.php'
?>

<html>
	<head profile="http://www.w3.org/2005/10/profile">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/png" href="images/favicon.png">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="/smartlots/css/style.css">
        <link rel="stylesheet" type="text/css" href="/smartlots/css/admin.css">
        <script src="/smartlots/js/json2.js"></script>
        <script src="/smartlots/js/controller.js"></script>
        <script src="/smartlots/js/templates.js"></script>
        <script src="/smartlots/js/licenses.js"></script>
        <title>View Licenses</title>
	<body onload = "loadLicenses()">
	    <h1>All License Plates</h1>
        <table id = "license_table"></table>
	</form>
	</body>
</html>