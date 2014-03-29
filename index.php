<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/', function () {
	readfile('home.php');
});

$app->get('/list', function() {
	readfile('list.php');
});

$app->get('/stats', function() {
	readfile('stats.php');
});

$app->get('/map', function() {
	readfile('home.php');
});

$app->get('/admin', function () {
	echo "<p>Administrator interface coming soon.</p>";
});

$app->run();

?>