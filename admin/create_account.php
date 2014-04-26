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
        <title>Create Account</title>
    </head>
	<body>
		<h1>Create a User Account</h1>
		<form action = "/smartlots/users" method = "post">
			<table id = "create_user_table">
				<tr>
					<td>
						<label>Username: </label>
					</td>
					<td>
						<input name = "username" required>
					</td>
				</tr>
				<tr>
					<td>
						<label>Password: </label>
					</td>
					<td>
						<input name = "password" type = "password" required><br>
					</td>
				</tr>
				<tr>
					<td colspan = 2 style = "text-align: center;">
						<input type = "submit">
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>