<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Prepare
$user_login = 'id' . time();
$email = $user_login . '@test.com';
$password = 'PW'.$email;

$he = "HE$email";
$she = "HER$email";


/// Step 1.

$re = register([]);
testError($re, ERROR_EMPTY_EMAIL, ERROR_EMPTY_EMAIL);

$re = register(['user_email' => 'thruthesky@gmail.com']);
testError($re, ERROR_EMPTY_PASSWORD, ERROR_EMPTY_PASSWORD);


$re = register(['user_email' => 'thruthesky@gmail.com', 'user_pass' => 'expect: error email_exists']);
testError($re, ERROR_EMAIL_EXISTS, ERROR_EMAIL_EXISTS);

$re = register(['user_email' => '@wrong format', 'user_pass' => 'expect: error wrong email format']);
testError($re, ERROR_WRONG_EMAIL_FORMAT, ERROR_WRONG_EMAIL_FORMAT);


$user = register(['user_email' => $email, 'user_pass' => $password]);
testSuccess($user, "User registered: {$user['ID']}");



/// Step 2.
include API_DIR . '/routes/user.route.php';
$user = new UserRoute();
$heResult = $user->register(['user_email' => $he, 'user_pass' => $password]);
testSuccess($heResult, "He registered: {$heResult['ID']}");

/// Step 3.
$she = getRoute(['route' => 'user.register', 'user_email' => $she, 'user_pass' => $password]);
testSuccess($she, "She registered: {$she['data']['ID']}");



displayTestSummary();


