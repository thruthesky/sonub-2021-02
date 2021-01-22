<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


/// Prepare
$user_login = 'id' . time();
$kelvin = 'kelvin' . $user_login . '@test.com';
$password = 'PW'.$kelvin;

/// Step 1.
$registeredUser = register(['user_email' => $kelvin, 'user_pass' => $password]);
testSuccess($registeredUser, "User registered: {$registeredUser['ID']}");

$loggedUser = login(['user_email' => $kelvin, 'user_pass' => $password]);
testSuccess($loggedUser, "kelvin logged in");


$profile = profile($loggedUser['ID']);


isTrue( $registeredUser['ID'] == $loggedUser['ID'], 'login ID check');
isTrue($loggedUser['ID'] == $profile['ID'], "login id check 2");

/// Step 2.
include API_DIR . '/routes/user.route.php';
$user = new UserRoute();
$loginWithRoute = $user->profile();
testSuccess($loginWithRoute, "Kelvin logged in with route : {$loginWithRoute['ID']}");

/// Step 3.
$profileWithApi = getRoute(['route' => 'user.profile', 'session_id' => $profile['session_id']]);
testSuccess($profileWithApi, "Login with Api: {$profileWithApi['data']['ID']}");



displayTestSummary();


