<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');





/// @attention By this code, learn how to load route directly and test it.
include_once V3_DIR . '/routes/app.route.php';
$app = new AppRoute();

wp_set_current_user(1);
isError($app->update([]), ERROR_EMPTY_TABLE, ERROR_EMPTY_TABLE);
isError($app->update(['table' => 'abc']), ERROR_EMPTY_FIELD, ERROR_EMPTY_FIELD);
isSuccess($app->update(['table' => 'bio', 'field' => 'birthdate', 'value'=>'731016']), '731016');
isTrue($app->get(['table'=>'bio'])['birthdate'] == '731016', "Match: 731016");
isSuccess($app->update(['table' => 'bio', 'field' => 'birthdate', 'value'=>'123456']), '123456');
isTrue($app->get(['table'=>'bio'])['birthdate'] == '123456', "Match: 123456");





displayTestSummary();



