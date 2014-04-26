<!DOCTYPE html>

<?php
    require 'admin/authorization.php'
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
        <title>SmartLots Admin</title>
    </head>
    <body>
        <h1>Administrator Homepage</h1>
        <a href = "/smartlots/admin/users.php">View All Users</a><br>
        <a href = "/smartlots/admin/create_account.php">New User</a><br>
        <a href = "/smartlots/admin/image_viewer.php">Image Viewer</a><br>
        <a href = "/smartlots/admin/add_image.php">Add New Image</a><br>
        <a href = "/smartlots/admin/view_licenses.php">View Licenses</a><br>
        <a href = "/smratlots/admin/view_sensor_data.php">View Sensor Data</a><br>
    </body>
</html>