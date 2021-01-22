<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


/// Step 1. Test method

$re = get_route([]);
isError($re, ERROR_EMPTY_ROUTE, ERROR_EMPTY_ROUTE);

$re = get_route(['route' => 'something']);
isError($re, ERROR_MALFORMED_ROUTE, ERROR_MALFORMED_ROUTE);

$re = get_route(['route' => 'wrong.route class name']);
isError($re, ERROR_ROUTE_CLASS_FILE_NOT_EXISTS, ERROR_ROUTE_CLASS_FILE_NOT_EXISTS);

$re = get_route(['route' => 'app.wrongMethodName']);
isError($re, ERROR_METHOD_NOT_EXIST, ERROR_METHOD_NOT_EXIST);

list($instance, $methodName) = get_route(['route' => 'app.version']);
$version = $instance->$methodName();
isSuccess($version);



/// Step 2. Test route
/// @attention By this code, learn how to load route directly and test it.
include_once V3_DIR . '/routes/app.route.php';
$app = new AppRoute();


/// Step 3. Test as client.
$re = getRoute(['route' => 'app.version']);
isSuccess($re['data']['version'] == $app->version()['version'], "app version: {$re['data']['version']}");


displayTestSummary();

