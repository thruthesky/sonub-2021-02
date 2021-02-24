<?php
require_once('../../../../wp-load.php');
if ( in('kakao_id') ) {
    $re = login_or_register(['user_email' => md5(in('kakao_id')) . '@kakao.com', 'user_pass' => LOGIN_PASSWORD_SALT, 'provider' => 'kakao' ]);
    set_login_cookies($re);
    jsGo('/');
}

