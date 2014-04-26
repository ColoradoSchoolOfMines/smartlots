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
        <script src="js/templates.js"></script>
        <title>Add Image</title>
    </head>
	<body>
		<h1>Add a new Image</h1>
		<form action="/smartlots/pidata" method="post" accept-charset="utf-8" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Username:</td>
					<td><input name = "username"></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input name = "password" type = "password"></td>
				</tr>
				<tr>
					<td>Sensor ID:</td>
					<td><input name = "id"></td>
				</tr>
				<tr>
					<td>Image:</td>
					<td><input name = "image" type = "file" id = "image"></td>
				</tr>
				<tr>
					<td colspan = 2 style = "text-align: center;"><input type = "submit"></td>
				</tr>
			</table>
		</form>
	</body>
</html>