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
        <script src="js/licenses.js"></script>
        <title>View Licenses</title>
	<body>
	    <input id = "url_input">
	    <a href = "#" onclick = "loadLicenses();">Go</a>
	    <br>
	    <div id = "image_div"></div>
	</form>
	</body>
</html>