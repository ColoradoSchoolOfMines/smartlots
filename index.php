<?php

	require 'vendor/autoload.php';
	require 'insert_fio_data.php';
	require 'insert_pi_data.php';
	require 'validate_credentials.php';
	require 'createAccount.php';
	require 'queryUsers.php';

	# Start the server/app
	$app = new \Slim\Slim();

	date_default_timezone_set('America/Denver');

	# Define all the routes our application will accept below

	############### FRONT END (WEBPAGE) ROUTES ###################
	##############################################################

	# return 'home.php' when someone requests the root of our site
	$app->get('/', function() {
		readfile('home.html');
	});

	# return 'list.php' when someone requests /list
	$app->get('/list', function() {
		readfile('list.html');
	});

	# return 'stats.php' when someone requests /stats
	$app->get('/stats', function() {
		readfile('stats.html');
	});

	# return 'home.php' when someone requests /map
	$app->get('/map', function() {
		readfile('home.html');
	});

	# admin interface homepage
	$app->get('/admin', function() {
		readfile('admin.html');
	});

	# create a user account
	$app->get('/users/new', function() {
		readfile('createAccount.html');
	});

	# view all users
	$app->get('/users', function() {
		extractAllUsers();
	});

	$app->get('/addImage', function() {
		readfile('addImage.html');
	});

	$app->get('/imageViewer', function() {
		readfile('imageViewer.html');
	});

	# adds a new user
	$app->post('/users', function() {
		create_account($_POST);
	});

	# deletes a user
	$app->delete('/user/:id', function($id) {
		delete_user($id);
	});

	############ BACK END (PARKING SYSTEM) ROUTES ################
	##############################################################

	# Adds data from the parking lot fio sensors to the database
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

	# Adds data from the parking lot pi sensors to the database
	$app->post('/pidata', function() {
		$username = $_POST['username'];
		$password = $_POST['password'];
		if ( validate($username, $password) ) {
			process_pi_data($_POST, $_FILES);
		} else {
			echo "Permission Denied. This invalid attempt has been logged for security purposes.";
			# Log attempt to database
		}
	});

	# Return the image with the specified date/timestamp
	$app->get('/images/:date', function($date) {
		header('Content-Type: image/jpg');
		imagejpeg(imagecreatefromjpeg("/var/license_plates/images/$date"));
	});

	$app->run();

?>