<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');
include V3_DIR . '/var/pass/pass_loginOrRegister.php';
$client_id = "gvC47PHoY7kS3DfpGfff";
$client_secret = "32729fcbc8e2a597c42704a855cbd16c9104d1930a516b6a24a0c69d113fe8d8";


$string_extra =<<<EOH
{"code":"0000","error":"","message":"성공입니다.","user":{"plid":"fake-9-2e4c87fa-a93e-4b5b-b7da-b394935c80e4","ci":"Bsg0kn6B53eucqulA1cY6CaotMq63682UKhFpf9GeiyuIxDmkgl2KWmHD4ZyntKOIQchCUmWu8giyzOJSqgE2YY8I2uuzNLXebhnZDCHF0XHw6bCl77N7UfyiTGefetJ","phoneNo":"jN92yVRNdUJEvQqOF+kQ4Q==","name":"FfVr08QagDyZ5P+8yxyNkA==","gender":"M","agegroup":"40","birthday":"zNMFMgmK9yQMvyAEbPG+Uw==","birthdate":"0yBVJgYu9LfAqScSopJGhw==","foreign":"L","telcoCd":"S","autoLoginYn":"N","autoStatusCheck":"N"}}
EOH;
$json = json_decode($string_extra, true);

$user = $json['user'];
$user['plid'] .= MOBILE_PREFIX . $user['plid'];

//
$profile = pass_loginOrRegister($user, $client_secret);
isTrue(is_string($profile['session_id']), "session ID: $profile[session_id]");


$second = pass_loginOrRegister(['plid' => $user['plid']], $client_secret);
isTrue(!empty($second['session_id']) && is_string($second['session_id']), "Same user: $second[session_id]");

pass_over_client($second);







