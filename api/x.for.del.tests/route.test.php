<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Step 1. Test method

$re = get_route([]);
testError($re, ERROR_EMPTY_ROUTE, ERROR_EMPTY_ROUTE);

$re = get_route(['route' => 'something']);
testError($re, ERROR_MALFORMED_ROUTE, ERROR_MALFORMED_ROUTE);

$re = get_route(['route' => 'wrong.route class name']);
testError($re, ERROR_ROUTE_CLASS_FILE_NOT_EXISTS, ERROR_ROUTE_CLASS_FILE_NOT_EXISTS);

$re = get_route(['route' => 'app.wrongMethodName']);
testError($re, ERROR_METHOD_NOT_EXIST, ERROR_METHOD_NOT_EXIST);

list($instance, $methodName) = get_route(['route' => 'app.version']);
$version = $instance->$methodName();
testSuccess($version);



/// Step 2. Test route
/// @attention By this code, learn how to load route directly and test it.
include_once API_DIR . '/routes/app.route.php';
$app = new AppRoute();


/// Step 3. Test as client.
$re = getRoute(['route' => 'app.version']);
testSuccess($re['data']['version'] == $app->version()['version'], "app version: {$re['data']['version']}");


displayTestSummary();

