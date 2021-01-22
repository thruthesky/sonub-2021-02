<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');





/// @attention By this code, learn how to load route directly and test it.
include_once API_DIR . '/routes/app.route.php';
$app = new AppRoute();

testSuccess($app->version(), "version test.....");


$re = getRoute(['route' => 'app.version']);
testSuccess($re['data']['version'] == $app->version()['version'], "app version: {$re['data']['version']}");


displayTestSummary();



