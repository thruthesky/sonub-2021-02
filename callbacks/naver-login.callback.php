<?php
require_once('../../../../wp-load.php');

$code = $_GET["code"];
$state = $_GET["state"];
$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&redirect_uri=".NAVER_CALLBACK_URL."&code=".$code."&state=".$state;

$is_post = false;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// echo "status_code:".$status_code."<hr>";
curl_close ($ch);
if($status_code == 200) {
//	 echo $response; // 액세스 토큰을 얻었다
    $re = json_decode($response,true);
    $token = $re['access_token'];
    $header = "Bearer ".$token; // Bearer 다음에 공백 추가
    $url = "https://openapi.naver.com/v1/nid/me";
    $is_post = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = "Authorization: ".$header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//	 echo "status_code:".$status_code."<hr>";
    curl_close ($ch);
    if($status_code == 200) {
//	    echo "resopnse<hr>";
//		 echo $response;
        $data = json_decode($response, true);
        $res = ['code'=>0, 'data' => $data];
    } else {
        //   echo "Error 내용:".$response;
        $res = ['code'=>1, 'message'=>'프로필을 가져오는 데 실패하였습니다.'];
    }
} else {
    // echo "Error 내용:".$response;
    $res = ['code'=>1, 'message'=>'액세스 토큰을 가져오기 데 실패하였습니다.'];
}


if ( $res['code'] ) {
    jsAlert('네이버 로그인에 실패하였습니다.');
}
$user_id = $res['data']['response']['id'];
$re = login_or_register(['user_email' => md5($user_id) . '@naver.com', 'user_pass' => LOGIN_PASSWORD_SALT, 'provider' => 'naver' ]);
set_login_cookies($re);
jsGo('/');
