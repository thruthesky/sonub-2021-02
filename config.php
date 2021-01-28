<?php
/**
 * @file config.php
 */

define('DEFAULT_TOPIC', 'DefaultTopic');

/**
 * Below are not recommended to be edited.
 */
/**
 * @todo Definition name change to PASS_LOGIN_MOBILE_PREFIX
 */
define('MOBILE_PREFIX', 'phoneNo_');


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
define('PASS_LOGIN_PASSWORD', 'S_oO0Oo_S.0.48.PM');

/**
 * Firebase Admin Service Account Key, for firebase connection
 */
define("SERVICE_ACCOUNT_FIREBASE_JSON_FILE_PATH", THEME_DIR . "/keys/nalia-app-firebase-admin-sdk-service-account-key.json");
define("FIREBASE_DATABASE_URI", "https://nalia-app-default-rtdb.firebaseio.com");


$domain_themes = [
    'apple' => 'apple',
    'banana' => 'banana',
];

define('API_URL_ON_CLI', 'https://local.sonub.com/wp-content/themes/wigo/api/index.php');



/**
 * PUBLIC Routes can be accessed without login.
 */
define('PUBLIC_ROUTES', [
    'app.version',
    'user.register',
    'user.login',
    'user.loginOrRegister',
    'user.testLoginOrRegister',
    'notification.updateToken',
    'forum.search',
    'forum.getPost',
    'app.query',
    'translation.list',
    'bio.search',
]);



/**
 * Public tables can be directly SQL-queried.
 */
define('PUBLIC_TABLES', [
    'bio',
]);



