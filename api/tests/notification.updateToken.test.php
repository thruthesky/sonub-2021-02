<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


/// Prepare test data set.
$A = 1;
$tokenA = 'A';
$tokenB = 'Banana';
$extraTokenA = 'Apple';


/// Step 1. Test functions

// Input test: expect error.
$re = update_token([]);
isError($re, ERROR_EMPTY_TOKEN, ERROR_EMPTY_TOKEN);

// update token without login
$re = update_token(['token' => $tokenA]);
isSuccess($re, $tokenA);

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

include_once V3_DIR . '/routes/notification.route.php';
$notification = new NotificationRoute();
$re = $notification->updateToken(['token' => $tokenB]);
isSuccess($re, "$tokenB == {$re['token']} is updated.");

/// Step 3. Test Api call.
$re = getRoute(['route' => 'notification.updateToken', 'token' => $tokenB]);
isSuccess($re, "Token update by Api call");


/** Display the summary of test results. */
displayTestSummary();


