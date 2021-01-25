<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');





/// @attention By this code, learn how to load route directly and test it.
include_once API_DIR . '/routes/app.route.php';
$app = new AppRoute();

wp_set_current_user(1);
testError($app->update([]), ERROR_EMPTY_TABLE, ERROR_EMPTY_TABLE);
testError($app->update(['table' => 'abc']), ERROR_EMPTY_FIELD, ERROR_EMPTY_FIELD);
testSuccess($app->update(['table' => 'bio', 'field' => 'birthdate', 'value'=>'731016']), '731016');
isTrue($app->get(['table'=>'bio'])['birthdate'] == '731016', "Match: 731016");
testSuccess($app->update(['table' => 'bio', 'field' => 'birthdate', 'value'=>'123456']), '123456');
isTrue($app->get(['table'=>'bio'])['birthdate'] == '123456', "Match: 123456");





displayTestSummary();



