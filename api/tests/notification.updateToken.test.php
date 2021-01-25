<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Prepare test data set.
$A = 1;
$tokenA = 'A';
$tokenB = 'Banana';
$extraTokenA = 'Apple';


/// Step 1. Test functions

// Input test: expect error.
$re = update_token([]);
testError($re, ERROR_EMPTY_TOKEN, ERROR_EMPTY_TOKEN);

// update token without login
$re = update_token(['token' => $tokenA]);
testSuccess($re, $tokenA);

$record = get_token($tokenA);
isTrue($record['user_ID'] == 0, 'token saved without user id');

// update same token with login
wp_set_current_user($A);
$re= update_token(['token'=>$tokenA]);
isTrue($re['token'] == $tokenA, "token updated: $tokenA");
isTrue($re['user_ID'] == $A, "user ID updated");

$re = update_token(['token' => $extraTokenA]);
isTrue($re['token'] == $extraTokenA && $re['user_ID'] == $A, "$extraTokenA is updated with user ID");


/// Step 2. Test route.

include_once API_DIR . '/routes/notification.route.php';
$notification = new NotificationRoute();
$re = $notification->updateToken(['token' => $tokenB]);
testSuccess($re, "$tokenB == {$re['token']} is updated.");

/// Step 3. Test Api call.
$re = getRoute(['route' => 'notification.updateToken', 'token' => $tokenB]);
testSuccess($re, "Token update by Api call");


/** Display the summary of test results. */
displayTestSummary();


