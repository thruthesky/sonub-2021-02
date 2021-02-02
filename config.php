<?php
/**
 * @file config.php
 */




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
 */
if ( !defined('BROWSER_COOKIE_DOMAIN') ) define('BROWSER_COOKIE_DOMAIN', '');


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
define('PASS_LOGIN_CALLBACK_URL', "https://local.sonub.com/wp-content/themes/sonub/pass-login-callback.php");
define('PASS_LOGIN_SALT', 'Random_Salt_oO^.^Oo_S.0.48.PM'); // This is any random (secret) string.

