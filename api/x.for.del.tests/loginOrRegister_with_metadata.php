<?php
include 'test.helper.php';
include 'defines.php';
include 'lib/AES_decode.php';
$client_id = "gvC47PHoY7kS3DfpGfff";
$client_secret = "32729fcbc8e2a597c42704a855cbd16c9104d1930a516b6a24a0c69d113fe8d8";

$string_extra =<<<EOH
{"code":"0000","error":"","message":"성공입니다.","user":{"plid":"2e4c87fa-a93e-4b5b-b7da-b394935c80e4","ci":"Bsg0kn6B53eucqulA1cY6CaotMq63682UKhFpf9GeiyuIxDmkgl2KWmHD4ZyntKOIQchCUmWu8giyzOJSqgE2YY8I2uuzNLXebhnZDCHF0XHw6bCl77N7UfyiTGefetJ","phoneNo":"jN92yVRNdUJEvQqOF+kQ4Q==","name":"FfVr08QagDyZ5P+8yxyNkA==","gender":"M","agegroup":"40","birthday":"zNMFMgmK9yQMvyAEbPG+Uw==","birthdate":"0yBVJgYu9LfAqScSopJGhw==","foreign":"L","telcoCd":"S","autoLoginYn":"N","autoStatusCheck":"N"}}
EOH;
$json = json_decode($string_extra, true);

$user = $json['user'];

$re = [
    'plid' => $user['plid'],
    'agegroup' => $user['agegroup'],
    'gender' => $user['gender'],
    'foreign' => $user['foreign'],
    'telcoCd' => $user['telcoCd'],
    'autoLoginYn' => $user['autoLoginYn'],
    'autoStatusCheck' => $user['autoStatusCheck'],
];

$re['ci'] =aes_dec($user['ci'], $client_secret);
$re['phoneNo'] =aes_dec($user['phoneNo'], $client_secret);
$re['name'] =aes_dec($user['name'], $client_secret);
$re['birthday'] = aes_dec($user['birthday'], $client_secret);
$re['birthdate'] = aes_dec($user['birthdate'], $client_secret);


$re['route'] = 'loginOrRegister';
$re['user_email'] = 'meta' . time() . '@test.com';
$re['user_pass'] = "$re[user_email],*";



// Register
$result = getRoute($re);
isTrue(is_string($result['response']['session_id']), "Register success. got session ID: {$result['response']['session_id']}");


// Login
$re['login_count'] = 1;
$result = getRoute($re);
isTrue(is_string($result['response']['session_id']), "Login success. got session ID: {$result['response']['session_id']}");



// get user profile
$result = getRoute(['route' => 'profile', 'session_id' => $result['response']['session_id']]);
isTrue(is_string($result['response']['session_id']), "Got profile. session ID: {$result['response']['session_id']}");
isTrue($result['response']['login_count'] == 1, 'count ok');
