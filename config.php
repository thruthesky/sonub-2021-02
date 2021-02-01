<?php
/**
 * @file config.php
 */

/**
 * Below are not recommended to be edited.
 */
/**
 * @todo Definition name change to PASS_LOGIN_MOBILE_PREFIX
 */
define('MOBILE_PREFIX', 'm');


if (WP_DEBUG) {
    define('REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST', true);
}




/**
 * Play Store App Link Service Account Key, for in-app-purchase
 * @TODO This must go to nalia.class.php
 */
define("SERVICE_ACCOUNT_LINK_TO_APP_JSON_FILE_PATH", API_DIR . "/keys/android-pub-key-api-6678595955257701195-92794-iam-gserviceaccount.com.json");


/**
 * For Jewelry System
 * @TODO This must go to ext/nalia.class.php
 */
define('MIN_BONUS_DIAMOND', 0);
define('MAX_BONUS_DIAMOND', 0);
define('MIN_BONUS_GOLD', 2);
define('MAX_BONUS_GOLD', 10);
define('MIN_BONUS_SILVER', 10);
define('MAX_BONUS_SILVER', 50);

define('GOLDBOX_RATE', [
    'min_diamond' => 0, 'max_diamond' => 0,
    'min_gold' => 10, 'max_gold' => 50,
    'min_silver' => 50, 'max_silver' => 100
]);

define('DIAMONDBOX_RATE', [
    'min_diamond' => 1, 'max_diamond' => 20,
    'min_gold' => 50, 'max_gold' => 100,
    'min_silver' => 100, 'max_silver' => 200
]);


/**
 * ================================================================================
 * Edit below on your needs
 * ================================================================================
 */

/**
 * Once set, do not change it. Or, all users must login again.
 */
define('SESSION_ID_SALT', "__SID__This_is_secret__<.o*o.>_salt__~,7__");

/**
 * Firebase Admin Service Account Key, for firebase connection
 */
define("FIREBASE_ADMIN_SDK_SERVICE_ACCOUNT_KEY_PATH", THEME_DIR . "/keys/firebase-adminsdk.json");
define("FIREBASE_DATABASE_URI", "https://nalia-app-default-rtdb.firebaseio.com");


$domain_themes = [
    'apple' => 'apple',
    'banana' => 'banana',
];

define('API_URL_ON_CLI', 'https://local.sonub.com/wp-content/themes/sonub/api/index.php');



/**
 * Public tables can be directly SQL-queried.
 */
define('PUBLIC_TABLES', [
    'bio',
]);


/**
 * If this is set to true, the user who registers will subscribe for 'new comments' under his post or comment.
 * If this is set to false, the registering user will not subscribe to 'new comments'.
 * The user can change this option on settings.
 */
define('SUBSCRIBE_NEW_COMMENT_ON_REGISTRATION', true);

define('POSTS_PER_PAGE', 3);
define('NO_OF_PAGES_ON_NAV', 3);

/**
 * Cookie domain
 *
 * If you set the cookie domain, it will apply the cookies on that domain.
 * This is useful to apply login cookie to all subdomains.
 *
 * To apply login cookie(and all other cookies) to all sub domains, set the root domain(like `domain.com`) name here
 */
define('BROWSER_COOKIE_DOMAIN', 'sonub.com');


/**
 * Pass login
 * @see README
 */
define('PASS_LOGIN_CLIENT_ID', 'b90hirE4UYwVf2GkHIiK');
define('PASS_LOGIN_CLIENT_SECRET_KEY', '366c0f3775bfa48f2239226506659f5981afd3eb2b08189f9f9d22cdc4ca63c9');
define('PASS_LOGIN_CALLBACK_URL', "https://local.sonub.com/wp-content/themes/sonub/pass-login-callback.php");
define('PASS_LOGIN_SALT', 'S_oO0Oo_S.0.48.PM'); // This is any random (secret) string.

