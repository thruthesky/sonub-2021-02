<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');





/// @attention By this code, learn how to load route directly and test it.
include_once V3_DIR . '/routes/app.route.php';
$app = new AppRoute();

isSuccess($app->version(), "version test.....");


$re = getRoute(['route' => 'app.version']);
isSuccess($re['data']['version'] == $app->version()['version'], "app version: {$re['data']['version']}");


displayTestSummary();



