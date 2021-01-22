<?php
include V3_DIR . '/var/pass/AES_decode.php';
function pass_loginOrRegister($user, $client_secret) {

    $re = [
        'user_pass' => PASS_LOGIN_PASSWORD,
    ];
    $re['autoLoginYn'] = $user['autoLoginYn'];
    $re['autoStatusCheck'] = $user['autoStatusCheck'];





    /// 처음 로그인 또는 자동 로그인이 아닌 경우, ci 와 전화번호 및 기타 값이 들어온다.
    /// 이 때, 모든 값을 user meta 정보에 남긴다.
    /// 그리고, name, birthdate, gender 은 bio 테이블에 남긴다.
    if ( isset($re['autoLoginYn'])  && $re['autoLoginYn'] == 'N' ) {
        $re['plid'] = $user['plid'];
        $re['agegroup'] = $user['agegroup'];
        $re['gender'] = $user['gender'];
        $re['foreign'] = $user['foreign'];
        $re['telcoCd'] = $user['telcoCd'];
        $re['ci'] = aes_dec($user['ci'], $client_secret);
        $re['phoneNo'] = aes_dec($user['phoneNo'], $client_secret);
        $re['name'] = aes_dec($user['name'], $client_secret);
        $re['birthday'] = aes_dec($user['birthday'], $client_secret);
        $re['birthdate'] = aes_dec($user['birthdate'], $client_secret);

        $re['user_email'] = MOBILE_PREFIX . "$re[phoneNo]@passlogin.com";
        $profile = loginOrRegister($re);

        table_update(['table' => 'bio', 'field' => 'name', 'value' => $re['name'] ]);
        table_update(['table' => 'bio', 'field' => 'birthdate', 'value' => $re['birthdate'] ]);
        table_update(['table' => 'bio', 'field' => 'gender', 'value' => $re['gender'] ]);
        return $profile;
    } else {
        /// plid 가 들어 온 경우, meta 에서 ci 를 끄집어 낸다.
        $users = get_users([ 'meta_key' => 'plid', 'meta_value' => $user['plid'] ]);
//        print_r($users);
        if ( count($users) == 1 ) {
            $found = $users[0];
            $profile = userProfile($found->ID);
//            print_r($profile);
            $re['user_email'] = MOBILE_PREFIX . "$profile[phoneNo]@passlogin.com";
//print_r($re);
            return login($re);
        } else {
            debug_log("duplicated plid: $user[plid]");
            return null; // error
        }
    }



}

function pass_over_client($profile) {
    $json = json_encode($profile);
    echo <<<EOJ
<script>
    var pass = '$json';
    messageHandler.postMessage(pass);
</script>
EOJ;

}