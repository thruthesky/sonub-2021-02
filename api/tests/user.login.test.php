<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Prepare
$user_login = 'id' . time();
$jenny = 'jenny' . $user_login . '@test.com';
$password = 'PW'.$jenny;

/// Step 1.
$registeredUser = register(['user_email' => $jenny, 'user_pass' => $password]);
testSuccess($registeredUser, "User registered: {$registeredUser['ID']}");

$loggedUser = login(['user_email' => $jenny, 'user_pass' => $password]);
testSuccess($loggedUser, "jenny logged in");


/// Step 2.
include API_DIR . '/routes/user.route.php';
$user = new UserRoute();
$loginWithRoute = $user->login(['user_email' => $jenny, 'user_pass' => $password]);
testSuccess($loginWithRoute, "Jenny logged in with route: {$loginWithRoute['ID']}");

/// Step 3.
$loginWithApi = getRoute(['route' => 'user.login', 'user_email' => $jenny, 'user_pass' => $password]);
testSuccess($loginWithApi, "Login with Api: {$loginWithApi['data']['ID']}");



displayTestSummary();


