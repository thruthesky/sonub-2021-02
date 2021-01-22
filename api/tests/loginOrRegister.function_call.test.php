<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR .'/defines.php');
require_once(V3_DIR . '/functions.php');
require_once(V3_DIR . '/router.php');
require_once(V3_DIR . '/test.helper.php');

date_default_timezone_set('Asia/Seoul');


$router = new Router();
$re = $router->loginOrRegister([]);
isTrue($re === ERROR_EMPTY_EMAIL);

$re = $router->loginOrRegister(['user_email' => 'loginOrEmail' . time() . '@test.com']);
isTrue($re === ERROR_EMPTY_PASSWORD, $re);

$re = $router->loginOrRegister(['user_email' => 'loginOrEmail' . time() . '@test.com', 'user_pass' => 'W-12345a,*']);
isTrue(is_string($re['session_id']),$re['session_id']);


