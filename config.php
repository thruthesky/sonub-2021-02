<?php
/**
 * @file config.php
 */
/**
 * ================================================================================
 * Edit configurations on your needs
 * ================================================================================
 */

define('APP_VERSION', '0.1.6');

/**
 * Theme configuration
 */

$domain_themes = [
    'philov' => 'sonub',
    'tellvi' => 'sonub',
    'sonub' => 'sonub',
    'goldenage50' => 'itsuda',
    'itsuda' => 'itsuda'
];

/**
 * Theme configuration exists, load it.
 * @attention 관리자 페이지에 있는 경우, 관리자 페이지 theme 이 아닌, 실제 theme 의 config.php 를 실행한다.
 */
$_theme = get_domain_theme(false);
$_path = THEME_DIR . "/themes/$_theme/$_theme.config.php";
if ( file_exists($_path) ) {
    require_once($_path);
}



/**
 * Once set, do not change it. Or, all users must login again.
 */
define('SESSION_ID_SALT', "__SID__This_is_secret__<.o*o.>_salt__~,7__");

/**
 * Firebase Admin Service Account Key, for firebase connection
 */
if ( ! defined('FIREBASE_ADMIN_SDK_SERVICE_ACCOUNT_KEY_PATH') ) {
    define("FIREBASE_ADMIN_SDK_SERVICE_ACCOUNT_KEY_PATH", THEME_DIR . "/keys/sonub-firebase-adminsdk.json");
}
if ( ! defined('FIREBASE_DATABASE_URI') ) {
    define("FIREBASE_DATABASE_URI", "https://itsuda50-default-rtdb.firebaseio.com/");
}




/**
 *
 */
define('API_URL_ON_CLI', 'https://local.sonub.com/wp-content/themes/sonub/api/index.php');

/**
 * If this is set to true, the user who registers will subscribe for 'new comments' under his post or comment.
 * If this is set to false, the registering user will not subscribe to 'new comments'.
 * The user can change this option on settings.
 */
define('SUBSCRIBE_NEW_COMMENT_ON_REGISTRATION', true);

/**
 * POSTS_PER_PAGE is to display how many posts for the post list page when the category has no posts_per_page settings.
 */
define('POSTS_PER_PAGE', 30);
/**
 * NO_OF_PAGES_ON_NAV is to display how many pages on navigation bar when it is not set.
 */
define('NO_OF_PAGES_ON_NAV', 3);

/**
 * Cookie domain
 *
 * If you set the cookie domain, it will apply the cookies on that domain.
 * This is useful to apply login cookie to all subdomains.
 *
 * To apply login cookie(and all other cookies) to all sub domains, set the root domain(like `.domain.com`) name here
 *   - note, that dot(.) must be added on root domain.
 *
 * 만약, 개별 설정에서 정의된 쿠키가 없으면, 자동으로 ROOT_DOMAINS 에 있는 것을 기반으로 최상위 도메인(1차) 도메인으로 지정한다.
 */
if ( !defined('BROWSER_COOKIE_DOMAIN') ) {
    define('BROWSER_COOKIE_DOMAIN', '.' . get_root_domain());
}


/**
 * 각종 로그인(패스로그인, 카카오로그인, 등) 할 때, 사용되는 비밀번호.
 */
define('LOGIN_PASSWORD_SALT', 'Random_Salt_oO^.^Oo_S.0.48.PM,*'); // This is any random (secret) string.


/**
 * Pass login
 * @see README
 */

/**
 * PASS_LOGIN_MOBILE_PREFIX is the prefix that will be added on user register through Pass login.
 * PASS_LOGIN_SALT is the secret password used with Pass login. It should be a random string between 16 to 32 chars.
 */
define('PASS_LOGIN_MOBILE_PREFIX', 'm');
define('PASS_LOGIN_CLIENT_ID', 'b90hirE4UYwVf2GkHIiK');
define('PASS_LOGIN_CLIENT_SECRET_KEY', '366c0f3775bfa48f2239226506659f5981afd3eb2b08189f9f9d22cdc4ca63c9');
define('PASS_LOGIN_CALLBACK_URL', "https://sonub.com/wp-content/themes/sonub/callbacks/pass-login-callback.php");

// 날짜 설정
//
// 추천/비추천 및 게시글/코멘트 쓰기 제한 등에서, 일/수 단위로 제한을 할 때, 한국 시간으로 할지, 어느나라 시간으로 할 지 지정 할 수 있다.
date_default_timezone_set('Asia/Seoul');


// Kakao Javascript Api 키
define('KAKAO_CLIENT_ID', '6f8d49d406555f69828891821ea56c8b');
// Kakao Redirect URI
define('KAKAO_CALLBACK_URL', 'https://main.philov.com/wp-content/themes/sonub/callbacks/kakao-login.callback.php');



define('NAVER_CLIENT_ID', 'gCVN3T_vsOmX1ADriDOA');
define('NAVER_CLIENT_SECRET', 'JzWh7zPeJF');
define('NAVER_CALLBACK_URL', urlencode('https://main.philov.com/wp-content/themes/sonub/callbacks/naver-login.callback.php'));
define('NAVER_API_URL', "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".NAVER_CLIENT_ID."&redirect_uri=".NAVER_CALLBACK_URL."&state=1");



define('DEFAULT_DELIVERY_FEE_FREE_LIMIT', 30000);
define('DEFAULT_DELIVERY_FEE_PRICE', 2500);
