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
$json = json_encode(profile());
echo <<<EOJ
<script>
    messageHandler.postMessage('$json');
</script>
EOJ;
