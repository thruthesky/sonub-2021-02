<?php
if ( ! defined( 'API_DIR' ) ) {
    define( 'API_DIR', '../..' );
}
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');
require_once(API_DIR . '/var/pass/pass_loginOrRegister.php');

date_default_timezone_set('Asia/Seoul');

/// PASS KEY
$client_id = "gvC47PHoY7kS3DfpGfff";
$client_secret = "32729fcbc8e2a597c42704a855cbd16c9104d1930a516b6a24a0c69d113fe8d8";


/**
 * STEP 1.
 *
 * PASS 서버로 로그인해서, access_token 을 가져온다.
 * 참고로 Refresh 를 하면 Invalid authorization code 에러가 난다.
 * 로그인 성공하면, ["access_token" => "...", "token_type" => "bearer", "expires_in" => 600, "state" => "..."] 와 같은 정보가 나온다.
 */
$url = "https://id.passlogin.com/oauth2/token";
if ( ! isset($_REQUEST['code'] ) ) {
    $str = 'pass_login_callback.php::code is empty';
    debug_log($str);
    echo $str;
    return;
}
$code = $_REQUEST['code'];
$state = $_REQUEST['state'];
$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic '. base64_encode("$client_id:$client_secret")
);
$o = [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => "grant_type=authorization_code&code=$code&state=$state",
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADER => 0, // 결과 값에 HEADER 정보 출력 여부
    CURLOPT_FRESH_CONNECT => 1, // 캐시 사용 0, 새로 연결 1
    CURLOPT_RETURNTRANSFER => 1, // 리턴되는 결과 처리 방식. 1을 변수 저장. 2는 출력.
    CURLOPT_SSL_VERIFYPEER => 0 // HTTPS 사용 여부
];
$ch = curl_init();
curl_setopt_array( $ch, $o );

try {
    $response = curl_exec( $ch );
    $re = json_decode($response, true);
    if ( isset($re['error']) && !empty($re['error'])) {
        echo "ERROR: $re[error], MESSAGE: $re[message]";
    } else {
        /// 성공
    }
    debug_log($response);
}
catch ( exception $e ) {
    print_r( $e, true );
}
curl_close( $ch );


/**
 * STEP 2.
 *
 * access_token 의 회원 정보를 가져온다.
 * access_token 당 1회만 조회 가능. 주의: 자동 로그인을 할 때에는 전화번호나 기타 정보가 따라오지 않는다. 그래서 가능하면 로그인을 한번하고 로그인을 끊어 줘야 한다.
 */
$headers = ['Authorization: Bearer ' . $re['access_token']];
$o = [
    CURLOPT_URL => "https://id.passlogin.com/v1/user/me",
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADER => 0, // 결과 값에 HEADER 정보 출력 여부
    CURLOPT_FRESH_CONNECT => 1, // 캐시 사용 0, 새로 연결 1
    CURLOPT_RETURNTRANSFER => 1, // 리턴되는 결과 처리 방식. 1을 변수 저장. 2는 출력.
    CURLOPT_SSL_VERIFYPEER => 0 // HTTPS 사용 여부
];
$ch = curl_init();
curl_setopt_array( $ch, $o );

try {
    $response = curl_exec( $ch );
    $result = json_decode($response, true);
    if ( isset($result['error']) ) echo "[ ERROR: $result[error], MESSAGE: $result[message]";
    debug_log($response);
}
catch ( exception $e ) {
    print_r( $e, true );
}
curl_close( $ch );

$user = $result['user'];

$profile = pass_loginOrRegister($user, $client_secret);
pass_over_client($profile);


