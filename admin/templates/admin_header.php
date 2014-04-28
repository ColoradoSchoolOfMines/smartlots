<!DOCTYPE html>

<?php
    require_once 'validate_credentials.php';
    authorize();
?>

<html>
    <head profile="http://www.w3.org/2005/10/profile">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/png" href="/smartlots/images/favicon.png">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="/smartlots/css/style.css">
        <link rel="stylesheet" type="text/css" href="/smartlots/css/admin.css">
        <script src="/smartlots/js/json2.js"></script>
        <script src="/smartlots/js/controller.js"></script>
        <script src="/smartlots/js/templates.js"></script>
        <script src="/smartlots/js/licenses.js"></script>
        <script src="/smartlots/js/users.js"></script>
        <title>Smartlots Admin</title>
    </head>
	<body>
		<table style = "vertical-align: middle;"><tr><td>
			<a href = "/smartlots">
				<img src = "/smartlots/images/SmartLotsLogo.png">
			</a>
		</td>
		<td class = "nav">
			<a href = "/smartlots/admin/admin.php">Admin Home</a> | 
			<a href = "/smartlots/admin/view_users.php">All Users</a> | 
			<a href = "/smartlots/admin/create_account.php">New User</a> | 
			<a href = "/smartlots/admin/view_licenses.php">View Licenses</a> | 
			<a href = "/smartlots/admin/add_image.php">Add Image</a> | 
			<a href = "/smartlots/admin/image_viewer.php">Image Viewer</a> |
			<a href = "/smartlots/admin/view_sensors.php">Sensor Data</a>
		</td></tr></table>
