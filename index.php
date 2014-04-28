<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require 'vendor/autoload.php';
	require 'admin/validate_credentials.php';

	require 'backend_php/fios.php';
	require 'backend_php/images.php';
	require 'backend_php/licenses.php';
	require 'backend_php/pis.php';
	require 'backend_php/sensors.php';
	require 'backend_php/users.php';

	# Create the server/app
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

	######################## USER ROUTES #########################
	##############################################################

	$app->get('/users', function() {
		authorize();
		extract_all_users();
	});

	$app->get('/users/:id', function($id) {
		authorize();
		extract_user($id);
	});

	$app->post('/users', function() use ($app) {
		authorize();
		create_user($_POST);
		$app->redirect('/smartlots/admin/view_users.php');
	});

	$app->delete('/users/:id', function($id) use ($app) {
		authorize();
		delete_user($id);
	});

	#################### IMAGE ROUTES ############################
	##############################################################

	$app->get('/images', function() {
		authorize();
		extract_all_images();
	});

	# Return the image with the specified date/timestamp
	$app->get('/images/:date', function($date) {
		authorize();
		header('Content-Type: image/jpg');
		imagejpeg(imagecreatefromjpeg("/var/license_plates/images/$date"));
	});

	// $app->post('/images', function() {
	// 	authorize();
	// 	create_image($_POST);
	// });

	// $app->delete('/images/:id', function($id) {
	// 	authorize();
	// 	delete_image($id);
	// });

	############## LICENSE PLATE NUMBER ROUTES ###################
	##############################################################

	$app->get('/licenses', function() {
		authorize();
		extract_all_licenses();
	});

	$app->get('/licenses/:id', function($id) {
		authorize();
		extract_license($id);
	});

	// $app->post('/licenses', function() {
	// 	authorize();
	// 	create_license($_POST);
	// });

	// $app->delete('/licenses/:id', function($id) {
	// 	authorize();
	// 	delete_license($id);
	// });

	############## INTRADA PLATE NUMBER ROUTES ###################
	##############################################################

	$app->get('/intrada_licenses', function() {
		authorize();
		extract_all_intrada_licenses();
	});

	$app->get('/intrada_licenses/:id', function($id) {
		authorize();
		extract_intrada_license($id);
	});

	// $app->post('/intrada_licenses', function() {
	// 	authorize();
	// 	create_intrada_license($_POST);
	// });

	// $app->delete('/intrada_licenses/:id', function($id) {
	// 	authorize();
	// 	delete_intrada_license($id);
	// });

	##################### SENSOR DATA ROUTES #####################
	##############################################################

	$app->get('/sensors', function() {
		authorize();
		extract_all_sensors();
	});

	$app->get('/sensors/:id', function($id) {
		authorize();
		extract_sensor($id);
	});

	// $app->post('/sensors', function() {
	// 	authorize();
	// 	create_sensor($_POST);
	// });

	// $app->delete('/sensors/:id', function($id) {
	// 	authorize();
	// 	delete_sensor($id);
	// });

	############ BACK END (PARKING SYSTEM) ROUTES ################
	##############################################################

	# Adds data from the parking lot fio sensors to the database
	$app->post('/fiodata', function() {
		authorize();
		process_fio_data($_POST);
	});

	# Adds data from the parking lot pi sensors to the database
	$app->post('/pidata', function() use ($app) {
		authorize();
		process_pi_data($_POST, $_FILES);
		$app->redirect('/smartlots/admin/view_licenses.php');
	});

	$app->run();

?>