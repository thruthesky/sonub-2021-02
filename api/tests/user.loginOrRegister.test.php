<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


/// Prepare
$user_login = 'id' . time();
$susan = 'susan' . $user_login . '@test.com';
$password = 'PW'.$susan;

/// Step 1.
$registeredUser = loginOrRegister(['user_email' => $susan, 'user_pass' => $password]);
isSuccess($registeredUser, "User registered: {$registeredUser['ID']}");
isTrue($registeredUser['mode'] == 'register', 'registered');


/// Step 2.
include V3_DIR . '/routes/user.route.php';
$user = new UserRoute();
$loginWithRoute = $user->loginOrRegister(['user_email' => $susan, 'user_pass' => $password]);
isSuccess($loginWithRoute, "Susan logged in with route: {$loginWithRoute['ID']}");
isTrue($loginWithRoute['mode'] == 'login', 'logged in');

/// Step 3.
$loginWithApi = getRoute(['route' => 'user.loginOrRegister', 'user_email' => $susan, 'user_pass' => $password]);
isSuccess($loginWithApi, "Login with Api: {$loginWithApi['data']['ID']}");
isTrue($loginWithApi['data']['mode'] == 'login', 'logged in');



displayTestSummary();


