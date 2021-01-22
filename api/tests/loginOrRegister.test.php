<?php
include 'test.helper.php';
include 'defines.php';
$re = getRoute(['route' => 'loginOrRegister']);
testError($re, ERROR_EMPTY_EMAIL);


$re = getRoute(['route' => 'loginOrRegister', 'user_email' => 'user' . time() . '@test.com' ]);
testError($re, ERROR_EMPTY_PASSWORD);


$re = getRoute(['route' => 'loginOrRegister', 'user_email' => 'user' . time() . '@test.com', 'user_pass' => '12' ]);
isTrue(is_string($re['response']['session_id']), "Register success. got session ID: {$re['response']['session_id']}");