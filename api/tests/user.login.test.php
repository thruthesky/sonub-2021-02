<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


/// Prepare
$user_login = 'id' . time();
$jenny = 'jenny' . $user_login . '@test.com';
$password = 'PW'.$jenny;

/// Step 1.
$registeredUser = register(['user_email' => $jenny, 'user_pass' => $password]);
isSuccess($registeredUser, "User registered: {$registeredUser['ID']}");

$loggedUser = login(['user_email' => $jenny, 'user_pass' => $password]);
isSuccess($loggedUser, "jenny logged in");


/// Step 2.
include V3_DIR . '/routes/user.route.php';
$user = new UserRoute();
$loginWithRoute = $user->login(['user_email' => $jenny, 'user_pass' => $password]);
isSuccess($loginWithRoute, "Jenny logged in with route: {$loginWithRoute['ID']}");

/// Step 3.
$loginWithApi = getRoute(['route' => 'user.login', 'user_email' => $jenny, 'user_pass' => $password]);
isSuccess($loginWithApi, "Login with Api: {$loginWithApi['data']['ID']}");



displayTestSummary();


