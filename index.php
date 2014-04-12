<?php

require 'vendor/autoload.php';
require 'insert_fio_data.php';
require 'validate_credentials.php';

# Start the server/app
$app = new \Slim\Slim();

# Define all the routes our application will accept below

############### FRONT END (WEBPAGE) ROUTES ###################
##############################################################

# return 'home.php' when someone requests the root of our site
$app->get('/', function () {
	readfile('home.php');
});

# return 'list.php' when someone requests /list
$app->get('/list', function() {
	readfile('list.php');
});

# return 'stats.php' when someone requests /stats
$app->get('/stats', function() {
	readfile('stats.php');
});

# return 'home.php' when someone requests /map
$app->get('/map', function() {
	readfile('home.php');
});

# admin interface homepage
$app->get('/admin', function () {
	readfile('');
});

############ BACK END (PARKING SYSTEM) ROUTES ################
##############################################################

$app->post('/fiodata', function() {
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ( validate($username, $password) ) {
		process_fio_data($_POST);
	} else {
		echo "Permission Denied. This invalid attempt has been logged for security purposes.";
		# Log attempt to database
	}
});

$app->run();



?>