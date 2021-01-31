<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Prepare
$user_login = 'id' . time();
$susan = 'susan' . $user_login . '@test.com';
$password = 'PW'.$susan;

/// Step 1.
$registeredUser = login_or_register(['user_email' => $susan, 'user_pass' => $password]);
testSuccess($registeredUser, "User registered: {$registeredUser['ID']}");
isTrue($registeredUser['mode'] == 'register', 'registered');


/// Step 2.
include API_DIR . '/routes/user.route.php';
$user = new UserRoute();
$loginWithRoute = $user->loginOrRegister(['user_email' => $susan, 'user_pass' => $password]);
testSuccess($loginWithRoute, "Susan logged in with route: {$loginWithRoute['ID']}");
isTrue($loginWithRoute['mode'] == 'login', 'logged in');

/// Step 3.
$loginWithApi = getRoute(['route' => 'user.loginOrRegister', 'user_email' => $susan, 'user_pass' => $password]);
testSuccess($loginWithApi, "Login with Api: {$loginWithApi['data']['ID']}");
isTrue($loginWithApi['data']['mode'] == 'login', 'logged in');



displayTestSummary();


