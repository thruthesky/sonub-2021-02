<?php
/**
 * @file pass-login-callback.php
 * @desc
 */



require_once('../../../wp-load.php');

$user = pass_login_callback($_REQUEST);

if ( api_error($user) ) {
    echo "<h1>ERROR: $user</h1>";
    exit;
}


d($user);
pass_login_or_register($user);

/**
 * 여기까지 오면 로그인 성공
 */

/**
 * state 가 openHome 이면, 홈페이지로 이동
 */
if ( $_REQUEST['state'] === 'openHome' ) {
    return jsGo('/');
}

/**
 * 자바스크립트로 메시지 전송
 */
$json = json_encode(profile());
echo <<<EOJ
<script>
    messageHandler.postMessage('$json');
</script>
EOJ;
