<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


/// Prepare
$user_login = 'id' . time();
$email = $user_login . '@test.com';
$password = 'PW'.$email;

$he = "HE$email";
$she = "HER$email";


/// Step 1.

$re = register([]);
isError($re, ERROR_EMPTY_EMAIL, ERROR_EMPTY_EMAIL);

$re = register(['user_email' => 'thruthesky@gmail.com']);
isError($re, ERROR_EMPTY_PASSWORD, ERROR_EMPTY_PASSWORD);


$re = register(['user_email' => 'thruthesky@gmail.com', 'user_pass' => 'expect: error email_exists']);
isError($re, ERROR_EMAIL_EXISTS, ERROR_EMAIL_EXISTS);

$re = register(['user_email' => '@wrong format', 'user_pass' => 'expect: error wrong email format']);
isError($re, ERROR_WRONG_EMAIL_FORMAT, ERROR_WRONG_EMAIL_FORMAT);


$user = register(['user_email' => $email, 'user_pass' => $password]);
isSuccess($user, "User registered: {$user['ID']}");



/// Step 2.
include V3_DIR . '/routes/user.route.php';
$user = new UserRoute();
$heResult = $user->register(['user_email' => $he, 'user_pass' => $password]);
isSuccess($heResult, "He registered: {$heResult['ID']}");

/// Step 3.
$she = getRoute(['route' => 'user.register', 'user_email' => $she, 'user_pass' => $password]);
isSuccess($she, "She registered: {$she['data']['ID']}");



displayTestSummary();


