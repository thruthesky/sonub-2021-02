<?php
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');


/**
 * Returns api version in string.
 * @return string
 */
function api_version()
{
    return APP_VERSION;
}

/**
 * JSON input from Client
 * @return mixed|null
 */
function get_JSON_input()
{

    // Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));

    // Attempt to decode the incoming RAW post data from JSON.
    $decoded = json_decode($content, true);

    // If json_decode failed, the JSON is invalid.
    if (!is_array($decoded)) {
        return null;
    }

    return $decoded;
}

/**
 *
 * @note By default it returns null if the key does not exist.
 *
 *
 * @param $name
 * @param null $default
 * @return mixed|null
 *
 */
function in($name = null, $default = null)
{

    // If the request is made by application/json content-type,
    // Then get the data as JSON input.
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    //
    //debug_log("CONTENT_TYPE: $contentType");
    //debug_log($_SERVER);
    //debug_log($_REQUEST);



    if (stripos($contentType, 'application/json') !== false) {
        $_REQUEST = get_JSON_input();
    }

    if ($name === null) {
        return $_REQUEST;
    }
    if (isset($_REQUEST[$name])) {
        $v = $_REQUEST[$name];
        if ( $name == 'page' ) {
            $v = str_replace('.', '/', $v);
            if ( endsWith($v, '/submit') ) {
                $v = str_replace('/submit', '.submit', $v);
            }
        }
        return $v;
    } else {
        return $default;
    }
}




/**
 * Leaves a log message on WordPress log file on when the debug mode is enabled on WordPress.
 * ( wp-content/debug.log )
 *
 * @param $message
 */
function debug_log($message, $obj = null)
{
    static $count_log = 0;
    $count_log++;
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        } else {
            // ....
        }
        if ($obj) {
            $message .= "\n" . print_r($obj, true);
        }
        $message = "[$count_log] $message\n";
        error_log($message, 3, ABSPATH . "/wp-content/debug.log"); //
    }
}





function success($data)
{
    /**
     * 리턴 값을 추가해서 전송한다.
     */
    $result = ['code' => 0, 'data' => $data, 'request' => $_REQUEST];

    response($result);
    exit;
}

/**
 * @param mixed $code error code. client can display error message with the error code.
 */
function error($code)
{
    response(['code' => $code, 'request' => $_REQUEST]);
    exit;
}


function success_or_error($data)
{
    //    echo "===> data: \n";
    //    print_r($data);
    if ($data === null || $data == '') error(ERROR_EMPTY_RESPONSE);
    if (is_string($data)) error($data);
    else success($data);
}


/**
 * @deprecated this function has an error.
 * Replace host url of image to request host.
 *
 * @warning Replacing host url image is not recommended. Use it only for test purpose.
 *
 * @usage Use this method when you need to adjust your image URL.
 *  For instance,
 *  - your API URL is `http://192.168.0.1/wordpress/v56/v3/inde.php`
 *  - while the home url of wordpress is `https://abc.kr/`
 *  - then the image url would be something like `https://abc.kr/wp-content/uploads/2021/01/02/abc.jpg` which your
 *    devices or emulators may not be able to load the image since the image has a domain that the route in the devices
 *    or emulators can't resolve.
 *  - So? this method will convert image url to `https://192.168.0.1/wordpress/v56/wp-content/uploads/2021/01/02/abc.jpg`
 *    then, the devices or emulators in same network may load properly.
 *
 * @attention `REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST` must set to true for it to work.
 * @param $data
 * @param null $apiUrl
 *  It must be something like 'https://abc.com/v3/index.php'
 * @return string
 *
 * @example See phpunit/ReplaceHostOfImageUrlToRequestHostTest.php
 */
function replace_host_of_image_url_to_request_host($data, $apiUrl = null)
{

    if (!defined('REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST') || REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST == false) return $data;

    // Get current(API) url like "https://abc.com/v3/index.php"
    if ($apiUrl) $current_url = $apiUrl;
    else {
        $current_url = get_current_url();
    }


    debug_log("current_url: $current_url");

    // Get current(API) url of host only. like "https://abc.com"
    $arr = explode('/v3', $current_url);
    $current_home_url = $arr[0];
    debug_log("current_home_url: $current_home_url");
    array_walk_recursive($data, function (&$value, $key) use ($current_home_url) {

        // Is the value image URL?
        if (is_string($value) && strpos($value, '/wp-content/uploads/') !== false) {
            // Then, get the path like "/wp-content/uploads/2021/01/14/abc.jpg"
            $arr = explode('/wp-content/uploads/', $value);
            $path = "/wp-content/uploads/$arr[1]";
            $new_url = $current_home_url . $path;
            $value = $new_url;
        }
    });
    return $data;


    //    $str = preg_replace("/(https?):\/\/([^\/]*)\/wp-content\/uploads\//", "$1://$host/wp-content/uploads/", $str);
    //    $str = str_replace($domain, $host, $str);
    //    return $str;
}




function response($data)
{
    try {
        /// Convert into json string
        //        $data = replace_host_of_image_url_to_request_host($data);
        $re = json_encode($data);
        if ($re) {
            // JSON 으로 출
            header('Content-Type: application/json; charset=utf-8');

            // 강제로 Content-Length 를 추가하니, 서버에서 연결을 끊지 못하고 계속 물고 있다.
            // 그래서 Client End 에서 timeout 에러가 발생한다.

            // 내용 출력
            echo $re;
        }
    } catch (Exception $e) {
        json_error();
    }
}

function json_error()
{

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
            break;
    }

    echo PHP_EOL;
}


/**
 * This method let the user log-in with $session_id
 *
 * @note this function requires `defines.php` and `config.php` to be loaded first.
 *
 * @return WP_User or Error object.
 *
 */
function authenticate($session_id)
{
    return session_login($session_id);
}




/**
 *
 * session_id 값을 입력 받아
 *      - 해당 사용자를 로그인 시키고,
 *      - 해당 사용자를 author 등급으로 업그레이드 하고,
 *      - WP_User 객체를 리턴한다.
 * @param $session_id
 *
 * @return mixed|WP_User|void
 *      - Returns WP_User instance on success
 *      - Error object if error.
 * @code
 *  $user = session_login( $_REQUEST['session_id'] );
 * @endcode
 *
 */
function session_login($session_id)
{

    if (empty($session_id)) return ERROR_EMPTY_SESSION_ID;

    $arr = explode('_', $session_id);
    if (count($arr) != 2) return ERROR_MALFORMED_SESSION_ID;
    list($ID, $trash) = $arr;
    debug_log("user session ID: $ID", $session_id);
    debug_log('server', $_SERVER);
    $user = get_userdata($ID);
    //    debug_log(print_r($user, true));
    if ($user) {
        if ($session_id == get_session_id($user)) {
            wp_set_current_user($ID);
            $user = wp_get_current_user();
            if ($user->ID != $ID) return ERROR_FAILED_TO_SET_LOGGED_IN_USER;
            else {
                // xlog('success on setting logged in user: ' . $user->ID);

            }
            return $user;
        } else {
            return ERROR_WRONG_SESSION_ID;
        }
    } else {
        return ERROR_NO_USER_BY_THAT_SESSION_ID;
    }
}

/**
 * Return true if the user logged in and not anonymous user.
 *
 * @note Try to use this function instead of wp_is_logged_in()
 * @return bool
 */
function is_logged_in()
{
    return is_user_logged_in() && wp_get_current_user()->ID > 0;
}

/**
 * Alias of is_logged_in()
 * @return bool
 */
function loggedIn()
{
    return is_logged_in();
}
function notLoggedIn()
{
    return loggedIn() === false;
}

/**
 * @param $email
 *
 * @return bool
 */
function check_email_format($email)
{

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //			echo("$email is a valid email address");
        return true;
    } else {
        //			echo("$email is not a valid email address");
        return false;
    }
}


/**
 * Returns a session_id
 *
 * Session id never changes unless SESSION_ID_SALT changes. And SESSION_ID_SALT should never change once it is set.
 *
 * This means, even if user changes his data, it can still validate the user's auth.
 *
 * It can be used as 'secret password'. of the user.
 *
 * @fix 2018-04-10. Remove 'user_login' since it is being changed when user changes his email.
 *
 * @param WP_User $user - Returns the session id of the user. If this is null, then it returns the login user's session id.
 * @return string
 *
 * @code How to get session_id
$user = new XUser($raw->ID );
$raw->session_id = $user->get_session_id( $user );
 * @endcode
 *
 * @Attention it returns '' if there is error.
 *
 *
 */
function get_session_id($user = null)
{
    if ($user === null) {
        $user = wp_get_current_user();
    }
    $userdata = $user->to_array();
    if (!isset($userdata['ID'])) {
        return '';
    }
    $reg = $userdata['user_registered'];
    $reg = str_replace(' ', '', $reg);
    $reg = str_replace('-', '', $reg);
    $reg = str_replace(':', '', $reg);
    $uid = $userdata['ID'] . $reg . SESSION_ID_SALT;
    $uid = $userdata['ID'] . '_' . md5($uid);
    return $uid;
}

function my($field)
{
    if ($field == 'ID') {
        return wp_get_current_user()->ID;
    } else if ($field == 'email') {
        return wp_get_current_user()->user_email;
    } else {
        return get_user_meta(wp_get_current_user()->ID, $field, true);
    }
}

/**
 * Login
 *
 * It logs in and return 0 on success.
 *
 * It returns zero(0). Or error code on error.
 *
 * @param $data -
 *  - $data['user_email']
 *  - $data['user_pass']
 *
 * @return mixed|string error code
 *
 * @logic
 *  - Do user login
 *  - Set the user logged in
 *  - Return user profile
 *
 * @Push Notification Logic
 *  - if token was passed, it will call the update_token so it will update the token user_id
 *  - get all user tokens and subscribe to all existing topics
 */
function login($data)
{
    if (!$data['user_email']) return ERROR_EMPTY_EMAIL;

    if (check_email_format($data['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;

    if (!$data['user_pass']) return ERROR_EMPTY_PASSWORD;

    $user = get_user_by('email', $data['user_email']);
    if (!$user) return ERROR_USER_NOT_FOUND_BY_THAT_EMAIL;

    $user = wp_authenticate($user->user_login, $data['user_pass']);
    if (is_wp_error($user)) return ERROR_WRONG_PASSWORD;


    user_update_meta($user->ID, $data);

    wp_set_current_user($user->ID);

    /**
     * Update the token for the login user.
     *
     * @logic
        - (A) has [phone-1].
        - (B) borrow [phone-1] and Login.
        - The [phone-1] unsubscribes all topics. The [phone-1] with its token of whoever the user might be had subscribed optics and it will unsubscribe from all(any kinds of ) topics.
        - (B) subscribes his topics with the [phone-1](with its token).
        So, the [phone-1] now receives messages of (B), not (A).
     *
     */
    if ( isset($in['token']) && !empty($in['token']) ) {
           update_token([ 'token' => $in['token']]);
           unsubscribeFromAllTopics($in['token']);
    }

    /**
     * If topic exist get all user tokens and subscribe to all existing topics
     */
    $topics = getUserForumTopics($user->ID);
    if (!empty($topics)) {
        $tokens = get_user_tokens();
        if (!empty($tokens)) {
            subscribeTopics($topics,$tokens);
        }
    }

    return profile();
}


/**
 * Save incoming data into user meta.
 *
 * @attention
 *  - It saves user profile data only in `wp_usermeta` table. It does not change data in `wp_users` table.
 *  - The input is key/value map and there is no limit to save properties.
 *
 * @param $in
 * @return array
 *  - returns the user profile after update user meta.
 */
function profile_update($in)
{
    user_update_meta(wp_get_current_user()->ID, $in);
    return profile();
}


/**
 * Update user information.
 *
 * @note permission check must be done before this method call.
 *
 * @note It can change user's email and password only if they are set. If they are not set, then they are not touched at all.
 * @note It can also change whatever meta data.
 * @caution All the meta data will be over written. This means, if a meta value is empty value, then the empty value will be saved.
 *
 * @param $in
 * @return array|string
 */
function admin_user_profile_update($in)
{
    $user = get_user_by('id', $in['ID']);
    if (!$user) return ERROR_USER_NOT_FOUND;

    /// update for wp_user info
    $up = [];
    if (isset($in['user_email']) && !empty($in['user_email'])) {
        $user_by_email = get_user_by('email', $in['user_email']);
        if ($user_by_email && $user->ID !== $user_by_email->ID) return ERROR_EMAIL_EXISTS;
        $up['user_email'] = $in['user_email'];
    }


    //    if (isset($in['user_nicename']) && !empty($in['user_nicename'])) $up['user_nicename'] = $in['user_nicename'];
    //    if (isset($in['user_url']) && !empty($in['user_url'])) $up['user_url'] = $in['user_url'];
    //    if (isset($in['user_activation_key']) && !empty($in['user_activation_key'])) $up['user_activation_key'] = $in['user_activation_key'];
    //    if (isset($in['user_status']) && !empty($in['user_status'])) $up['user_status'] = $in['user_status'];
    //    if (isset($in['display_name']) && !empty($in['display_name'])) $up['display_name'] = $in['display_name'];

    if (isset($in['user_pass']) && !empty($in['user_pass'])) {
        $up['user_pass'] = wp_hash_password($in['user_pass']);
    }

    global $wpdb;
    if (!empty($up)) {
        $wpdb->update('wp_users', $up, ['ID' => $user->ID]);
    }

    user_update_meta($user->ID, $in);
    return profile($user->ID);
}

/**
 * Register
 *
 *
 *
 * It logs in and return 0 on success.
 *
 * @condition
 *  - if `nickname` is empty, the value will be set as `user_email`.
 *
 * @logic
 *   - register
 *   - update user meta
 *   - let the registered user as login
 *   - return user profile
 *
 * @param mixed $in
 *   - required: $in['user_email']
 *   - required: $in['user_pass']
 *   - optional: $in['nickname']
 * @return mixed
 *  zero(0) on success.
 *  otherwise, error code.
 */
function register($in)
{

    if (!isset($in['user_email'])) return ERROR_EMPTY_EMAIL;
    if (!isset($in['user_pass'])) return ERROR_EMPTY_PASSWORD;
    if (get_user_by('email', $in['user_email'])) return ERROR_EMAIL_EXISTS;
    if (check_email_format($in['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;

    $nickname = $in['nickname'] ?? $in['user_email'];

    $userdata = [
        'user_login' => trim($in['user_email']),
        'user_pass' => trim($in['user_pass']),
        'user_email' => trim($in['user_email']),
        'user_nicename' => $nickname,
        'display_name' => $nickname,
    ];

    $user_ID = wp_insert_user($userdata);

    if (is_wp_error($user_ID)) {
        return $user_ID->get_error_code();
    }

    if (isset($in['token'])) {
        if (SUBSCRIBE_NEW_COMMENT_ON_REGISTRATION) {
            $token = $in['token'];
            unset($in['token']);
            $in[NOTIFY_COMMENT] = "Y";
            subscribeTopic(NOTIFY_COMMENT, $token);
        }
    }


    user_update_meta($user_ID, $in);

    wp_set_current_user($user_ID);

    return profile();
}


/**
 * @param $user_ID
 * @param $data
 */
function user_update_meta($user_ID, $data)
{
    foreach ($data as $k => $v) {
        if (!in_array($k, USER_META_EXCEPTIONS)) {
            update_user_meta($user_ID, $k, $v);
        }
    }
}


function user_metas($user_ID)
{
    if (empty($user_ID)) return [];
    $all_metas = get_user_meta($user_ID, '', true);
    $metas = [];
    foreach ($all_metas as $k => $v) {
        $metas[$k] = $v[0];
    }
    return $metas;
}


/**
 *
 * Returns user's profile including user's ID, email from `wp_users` table and all other properties in `wp_usermeta`.
 *
 * @WARNING This must be the ONLY method to return user profile to client.
 *
 *
 *
 * @param number $user_ID - user ID or session id.
 *
 * @return array
 *  - if it cannot find user information, it return an empty array.
 */
function profile($user_ID = null)
{
    if ($user_ID === null) {
        $user_ID = wp_get_current_user()->ID;
    }
    if (is_string($user_ID) && strpos($user_ID, '_') !== false) {
        $arr = explode('_', $user_ID);
        $user_ID = $arr[0];
    }

    $user = new WP_User($user_ID);
    if (!isset($user->ID)) {
        return [];
    }
    $data = $user->to_array();
    unset($data['user_pass'], $data['user_activation_key'], $data['user_status'], $data['user_nicename'], $data['display_name'], $data['user_url']);

    $data['session_id'] = get_session_id($user);
    $data['md5'] = md5($data['session_id']);

    $data = array_merge(user_metas($user_ID), $data);

    foreach ($data as $k => $v) {
        if (in_array($k, USER_META_EXCEPTIONS_FOR_CLIENT)) unset($data[$k]);
    }

    if (admin()) $data['admin'] = true;

    return $data;
}

/**
 *
 * Returns other user's public profile information including
 *  - ID
 *  - nickname,
 *  - profile_photo_url
 *  - md5. the md5 string of user session_id.
 *
 * @param int $user_ID  user ID
 *
 * @return array | string
 *  - if it cannot find user information, it return an empty array.
 */
function otherProfile($user_ID = null)
{

    if (!$user_ID) return ERROR_EMPTY_ID;
    $user = new WP_User($user_ID);
    if (!isset($user->ID)) {
        return [];
    }

    $data = $user->to_array();
    unset($data['user_pass'], $data['user_activation_key'], $data['user_status'], $data['user_nicename'], $data['display_name'], $data['user_url']);
    $data = array_merge(user_metas($user_ID), $data);
    $data['session_id'] = get_session_id($user);

    $ret = [
        'ID' => $data['ID'],
        'nickname' => $data['nickname'] ?? '',
        'profile_photo_url' => $data['profile_photo_url'] ?? '',
        'md5'=> md5($data['session_id']),
        'roomId' => getRoomID($data['session_id'])
    ];

    return $ret;
}

function getRoomID($session_id) {
    $current_session_id = get_session_id();
    if (strcmp($current_session_id, $session_id) < 0 ) {
        $session = $current_session_id . $session_id;
    } else {
        $session = $session_id . $current_session_id;
    }
    return md5($session);
}



/**
 * Returns true if the input is an error(or api or what ever error it is).
 *
 * @note 입력 값 $obj 가 문자열이고, ERROR_ 로 시작하면 에러이다.
 *
 * @param $obj
 * @return bool
 * @todo change name to api_error()
 */
function api_error($obj)
{
    if ($obj && is_string($obj) && strpos($obj, 'ERROR_') === 0) return true;
    else return false;
}


/**
 *
 * @param $in
 * @return array|string
 * @todo change kebab case.
 * @todo 2021. 02. 현재 Location 이 사용되지 않는데, 다시 코드를 명확히 작성 할 것.
 */
function updateUserLocation($in)
{
    global $wpdb;
    $data = [
        'user_ID' => $in['user_ID'] ??  wp_get_current_user()->ID,
        'latitude' => $in['latitude'],
        'longitude' => $in['longitude'],
        'accuracy' => $in['accuracy'] ?? 0,
        'altitude' => $in['altitude'] ?? 0,
        'speed' => $in['speed'] ?? 0,
        'heading' => $in['heading'] ?? 0,
        'time' => $in['time'] ?? 0,
    ];
    $re = $wpdb->replace(LOCATION_TABLE, $data);
    if ($re) return $data;
    else return ERROR_WRONG_QUERY;
}

/**
 * Search users within the radius of in['km'] from a point.
 *
 * Note that, this does SUBQUERY which means the performance may become slow.
 * Common use case is that
 *  - you will only get `user_ID` and `distance` to make the result records as slim as it can be to load records fastly.
 *  - then, apply SUBQUERY again for user table to search who are within the radius.
 *    Again, you may only get user_ID from user table to load fast.
 *
 *
 * @attention It will include the login user in the search.
 * @param $in
 *
 * @return array|object
 *  - `distance` in km is added to the return records.
 *
 * @example see tests/location.test.php
 *
 * @example
 * $re = userSearchByLocation(array_merge($rizal, ['km' => 100, 'fields' => 'user_ID,distance']));
 *
 * @change name to kebab case
 */
function userSearchByLocation($in)
{

    $LATITUDE = $in['latitude'];
    $LONGITUDE = $in['longitude'];
    $DISTANCE_KILOMETERS = $in['km'];
    $LIMIT = $in['limit'] ?? 15;
    $TABLE = LOCATION_TABLE;
    $FIELDS = isset($in['fields']) ? $in['fields'] : '*';

    $sql = <<<EOS
    SELECT $FIELDS FROM (
        SELECT *,
        ( 
            ( 
                ( acos(sin(( $LATITUDE * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $LATITUDE * pi() /180 ))
                *
                cos(( `latitude` * pi() / 180)) * cos((( $LONGITUDE - `longitude`) * pi()/180)))) * 180/pi()
            ) * 60 * 1.1515 * 1.609344
        ) as distance FROM $TABLE
    ) $TABLE
    WHERE distance <= $DISTANCE_KILOMETERS LIMIT $LIMIT
EOS;

    global $wpdb;
    $results = $wpdb->get_results($sql, ARRAY_A);
    if ($results) return $results;
    else return [];
}

/**
 * @param $in - example of input: ['route' => 'app.version']
 * @return array|string
 *
 * @example
 *      list($instance, $methodName) = get_route(['route' => 'app.version']);
 *      $version = $instance->$methodName();
 */
function get_route($in)
{
    $route = $in['route'] ?? null;
    if (empty($route)) return ERROR_EMPTY_ROUTE;
    $arr = explode('.', $route);
    if (count($arr) != 2) return ERROR_MALFORMED_ROUTE;

    $path = API_DIR . "/routes/{$arr[0]}.route.php";
    if (file_exists($path)) {
        include_once $path;
    } else {
        $path = API_DIR . "/ext/{$arr[0]}.route.php";
        if (file_exists($path)) {
            include_once $path;
        } else {
            return ERROR_ROUTE_CLASS_FILE_NOT_EXISTS;
        }
    }

    $className = $arr[0] . 'Route';
    $instance = new $className();
    $method = $arr[1];
    if (!method_exists($instance, $method)) return ERROR_METHOD_NOT_EXIST;

    return [$instance, $method, $route];
}

function end_if_error($code)
{
    if (api_error($code)) error($code);
    return $code;
}

/**
 *
 * @param $in
 * @return array|mixed|string
 *
 *  - ['mode' => 'login'] will be returned if the user logged in
 *  - ['mode' => 'register'] will be returned if the user registered.
 *
 * @example
 *  login_or_register(['user_email' => "ju-$i@test.com", 'user_pass' => "12345a", "other" => "data", ... ]);
 */
function login_or_register($in)
{
    $re = login($in);
    debug_log("login:", $re);
    if (api_error($re)) {
        if ($re == ERROR_USER_NOT_FOUND_BY_THAT_EMAIL) {
            $re = register($in);
            debug_log("register: ", $re);
            if (api_error($re)) return $re;
            $re['mode'] = 'register';
        } else {
            /// ERROR
            return $re;
        }
    } else {
        $re['mode'] = 'login';
    }
    return $re;
}


/**
 * Get record of the token
 *
 * @param $token
 *
 * @return array|object|void|null
 */
function get_token($token)
{
    global $wpdb;
    return $wpdb->get_row("SELECT * FROM " . PUSH_TOKENS_TABLE .  " WHERE token='$token'", ARRAY_A);
}

/**
 * Returns tokens of login user in an array
 * @return array|object|void|null
 *
 * @example phpunit/SubscribeTopicTest.php
 */
function get_user_tokens($ID = null)
{
    global $wpdb;
    if ($ID) $user_ID = $ID;
    else $user_ID = wp_get_current_user()->ID;
    $rows = $wpdb->get_results("SELECT * FROM " . PUSH_TOKENS_TABLE .  " WHERE user_ID='$user_ID'", ARRAY_A);
    return ids($rows, 'token');
}

/**
 * Updates push token
 *
 * @note the user may not logged in.
 *
 * @logic
 *  - user update token (insert or update)
 *  - if topic is provided, then enroll that topic with the token.
 *  - return the record of the token.
 *
 * @param $in
 *  'token' - the token string.
 * @return string
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function update_token($in)
{

    global $wpdb;
    if (!isset($in['token'])) return ERROR_EMPTY_TOKEN;
    $token = $in['token'];
    $user_ID = wp_get_current_user()->ID;
    debug_log("user_ID: $user_ID");

    if (empty($user_ID)) $user_ID = 0;

    $record = get_token($token);



    if (empty($record)) {
        // insert
        debug_log(" ['user_ID' => $user_ID, 'token' => $token, 'stamp' => time()] ");
        $re = $wpdb->insert(PUSH_TOKENS_TABLE, ['user_ID' => $user_ID, 'token' => $token, 'stamp' => time()]);
        if ($re === false) {
            return sql_error(ERROR_INSERT);
        }
    } else {
        // update
        $wpdb->update(PUSH_TOKENS_TABLE, ['user_ID' => $user_ID], ['token' => $token]);
    }


    if (isset($in['topic']) && !empty($in['topic'])) {
        $topic = $in['topic'];
        $re = subscribeTopic($topic, $token);
    } else {
        $re = subscribeTopic(DEFAULT_TOPIC, $token);
    }

    if ($re && isset($re['results']) && count($re['results']) && isset($re['results'][0]['error'])) {
        return ERROR_TOPIC_SUBSCRIPTION;
    }

    return get_token($token);
}





/**
 * Send messages to all users in $in['users']
 *
 * @param $in
 *  - $in['users'] is an array of user id.
 *
 *
 * @return mixed \Kreait\Firebase\Messaging\MulticastSendReport
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function send_message_to_users($in)
{
    if (!isset($in['users'])) return ERROR_EMPTY_USERS;
    if (!isset($in['title'])) return ERROR_EMPTY_TITLE;
    if (!isset($in['body'])) return ERROR_EMPTY_BODY;
    $all_tokens = [];

    $users = explode(',', $in['users']);
    foreach ($users as $ID) {
        $tokens = get_user_tokens($ID);
        $all_tokens = array_merge($all_tokens, $tokens);
    }
    /// If there are no tokens to send, then it will return empty array.
    if (empty($all_tokens)) return ERROR_EMPTY_TOKENS;
    if (!isset($in['imageUrl'])) $in['imageUrl'] = '';

    if ( !isset($in['data'])) $in['data'] = [];
    $in['data']['senderId'] = wp_get_current_user()->ID;
    return sendMessageToTokens($all_tokens, $in['title'], $in['body'], $in['click_action'], $in['data'], $in['imageUrl']);
}


/**
 * @param $slug
 * @return int
 */
function get_category_ID($slug)
{
    $idObj = get_category_by_slug($slug);
    if ($idObj) {
        return $idObj->term_id;
    } else {
        return 0;
    }
}




/**
 * Attach uploaded files to a post.
 * @param $post_ID int Post ID as wp_posts.ID
 * @param $files mixed IDs of attachment in wp_posts.
 *   It can be string of file IDs separated by comma. Or array of files IDs.
 * @param string $post_type
 *          - For comment, it is COMMENT_ATTACHMENT.
 * @return string String of file IDs
 *  - the input '$files' might be array. Then, it returns a string of File IDs separated by comma.
 *  - Returns empty string('') if there is no files.
 * @attention
 *  The reason why we use COMMENT_ATTACHMENT as post_type is because
 *  the same number of comment_ID may exists as wp_posts.ID
 *  To avoid the same number of ID conflict, we mark it its post_type as COMMENT_ATTACHMENT
 *
 *  Note that wp_delete_attachment can not delete COMMENT_ATTACHMENT.
 *  So, It changes comment's post_type to 'attachment' to delete the comment attachments.
 *
 * @example  $this->attachFiles(in('comment_ID'), in('files'), COMMENT_ATTACHMENT);
 */
function attach_files($post_ID, $files, $post_type = '')
{
    if (!$files) return '';
    if (!is_array($files)) {
        $files = explode(',', $files);
    }
    foreach ($files as $file_ID) {
        /// Attach the file to parent.
        $up = ['ID' => $file_ID, 'post_parent' => $post_ID];
        if ($post_type) {
            $up['post_type'] = $post_type;
        }
        wp_update_post($up);
    }
    return implode(",", $files);
}



/**
 *
 * This method saves all the input data into post_meta
 *      (except those are already saved in wp_posts table and specified in xapi_post_query_meta_exclude_vars() )
 *
 *
 * @attention This will save everything except wp_posts fields,
 *      so you need to be careful not to add un-wanted form values.
 *      So, don't just pass unnecessary data from client end.
 *
 * @note
 */
function update_post_properties($post_ID, $in)
{
    foreach ($in as $k => $v) {
        if (in_array($k, POST_FIELDS)) continue;
        if (in_array($k, USER_META_EXCEPTIONS)) continue;
        update_post_meta($post_ID, $k, $v);
    }
}





/**
 * @note it returns the 'post_content' with HTML.
 * @param $ID_or_post - This can be post ID or post object.
 * @param $options
 *
 * @note by default, post_content is returned in 'wpautop()' stirng.
 *  if $options['with_autop'] is set to true, 'post_content' will be set as normal, and 'post_content_autop' wil have wpautop() string.
 *  if $optoins['with_autop'] is set set and $options['autop'] is set to false, then it does not do wpautop()
 *
 * @note postCreate(), postUpdate(), postSearch() uses 'with_autop' option by default.
 *
 * @return mixed|array
 *      - error if there is any error
 *      - An array of post data.
 */
function post_response($ID_or_post, $options = [])
{

    if (empty($ID_or_post)) return ERROR_EMPTY_ID_OR_POST;
    $post = get_post($ID_or_post, ARRAY_A);

    if (!$post) return ERROR_POST_NOT_FOUND;

    if (isset($options['with_autop']) && $options['with_autop']) {
        $post['post_content_autop'] = wpautop(($post['post_content']));
    }

    /// Featured Image Url.
    ///
    $post_thumbnail_id = get_post_thumbnail_id($post['ID']);
    if ($post_thumbnail_id) {
        $post['featured_image_url'] = wp_get_attachment_image_url($post_thumbnail_id, 'full');
        $post['featured_image_thumbnail_url'] = wp_get_attachment_image_url($post_thumbnail_id, '100x100');
        $post['featured_image_ID'] = $post_thumbnail_id;
    }

    // $featured_image_url = get_the_post_thumbnail_url($post['ID']);
    // if ($featured_image_url)   $post['featured_image_url'] = $featured_image_url;
    // else $post['featured_image_url'] = '';

    // url of the post
    // It's relative url.
    $arr = explode('/', $post['guid'], 4);
    $post['url'] = "/$post[ID]/" . array_pop($arr);

    //
    $post['files'] = get_uploaded_files($post['ID']);

    /// author name
    $post['author_name'] = get_the_author_meta('display_name', $post['post_author']);

    // profile photo url
    $profile = profile($post['post_author']);
    $post['profile_photo_url'] = $profile['profile_photo_url'] ?? '';


    /// post author profile photo
    ///
    // $u = $this->userResponse($post['post_author']);
    // $post['user_photo'] = $u['photo'];

    /// post date
    $post['short_date_time'] = short_date_time($post['post_date']);

    /// Comments
    /// If there is no comment, then it will return empty array.
    ///
    $comments = get_nested_comments($post['ID']);
    $updated_comments = [];
    foreach ($comments as $comment) {
        $cmt = comment_response($comment['comment_ID'], $options);
        $cmt['depth'] = $comment['depth'];
        $updated_comments[] = $cmt;
    }
    $post['comments'] = $updated_comments;
    //                $post['comments'] = $comments;

    // Add meta datas.
    $metas = get_post_meta($post['ID'], '', true);
    $singles = [];
    if ($metas) {
        foreach ($metas as $k => $v) {
            $singles[$k] = $v[0];
        }
    }
    $post = array_merge($singles, $post);


    // get post slug as category name and pass
    if (count($post['post_category'])) {
        $cat = get_category($post['post_category'][0]);
        $post['category'] = $cat->slug;
    }


    unset($post['_pingme']);
    unset($post['_encloseme']);
    unset($post['post_date_gmt']);
    unset($post['post_excerpt']);
    unset($post['post_status']);
    unset($post['comment_status']);
    unset($post['ping_status']);
    unset($post['post_password']);
    unset($post['post_name']);
    unset($post['to_ping']);
    unset($post['pinged']);
    unset($post['post_modified_gmt']);
    unset($post['post_content_filtered']);
    unset($post['menu_order']);
    unset($post['post_type']);
    unset($post['post_mime_type']);
    unset($post['filter']);
    unset($post['ancestors']);
    unset($post['page_template']);
    unset($post['tags_input']);

    return $post;
}




/**
 * Returns uploaded files of a post.
 *
 * @param $parent_ID
 * @param string $post_type
 * @return array
 * @example
 *      $files = get_uploaded_files(129);
 * print_r($files);
 */
function get_uploaded_files($parent_ID, $post_type = 'attachment')
{
    $ret = [];

    $files = get_children(['post_parent' => $parent_ID, 'post_type' => $post_type, 'orderby' => 'ID', 'order' => 'ASC']);
    // xlog('get_uploaded_files ====> ' . $files);


    if ($files) {
        foreach ($files as $file) {
            $ret[] = get_uploaded_file($file->ID);
        }
    }

    return $ret;
}



/**
 * Returns a single file information.
 * @note this returns upload photo information
 * @param $post_ID - the attachment post id.
 *
 * @todo update thumbnail url. Thumbnail is not right.
 * @return array
 *
 * @note `exif` is not delivered to client by 2021. 01. 11.
 */
function get_uploaded_file($post_ID)
{

    $post = get_post($post_ID);
    if (!$post) return null;
    $ret = [
        'url' => $post->guid, // url is guid.
        'ID' => $post->ID, // wp_posts.ID
        //        'status' => $post->post_status,
        //        'author' => $post->post_author,
        //        'type' => $post->post_type,
        'media_type' => strpos($post->post_mime_type, 'image/') === 0 ? 'image' : 'file', // it will have 'image' or 'file'
        'type' => $post->post_mime_type,
        'name' => $post->post_name, // file name?
        //        'post' => $post->post_parent
    ];
    if ($ret['media_type'] == 'image') {
        $ret['thumbnail_url'] = $post->guid; // thumbnail url
    }
    /// Add image size, width, height
    //    $ret['exif'] = image_exif_details(image_path_from_url($ret['url']));
    return $ret;
}




function short_date_time($date)
{
    $stamp = strtotime($date);
    $Y = date('Y', $stamp);
    $m = date('m', $stamp);
    $d = date('d', $stamp);
    if ($Y == date('Y') && $m == date('m') && $d == date('d')) {
        $dt = date("h:i a", $stamp);
    } else {
        $dt = "$Y-$m-$d";
    }
    return $dt;
}




/**
 * Gets nested comments of a post.
 */
global $nest_comments;
function get_nested_comments($post_ID)
{
    global $nest_comments;
    $nest_comments = [];
    $post = get_post($post_ID);

    $comments = get_comments(['post_id' => $post_ID]);

    $comment_html_template = wp_list_comments(
        [
            'max_depth' => 100,
            'reverse_top_level' => 'asc',
            'avatar_size' => 0,
            'callback' => 'get_nested_comments_with_meta',
            'echo' => false
        ],
        $comments
    );
    return $nest_comments;
}

function get_nested_comments_with_meta($comment, $args, $depth)
{
    global $nest_comments;
    $nest_comments[] = [
        'comment_ID' => $comment->comment_ID,
        'depth' => $depth // Note that, it is a number and client will take it as number.
    ];
}



/**
 * Returns image size, width, height and extra information.
 * @param $path
 * @return array
 *  - array of information
 *  - or empty array if there is any error.
 *
 * @example of return data.
FileName: "a5ba08b7b3f8816f6fbec39f3b79898f.jpg"
FileDateTime: 1582106902
FileSize: 12602
FileType: 2
MimeType: "image/jpeg"
SectionsFound: ""
html: "width="274" height="164""
Height: 164
Width: 274
IsColor: 1
 *
 *
 */
function image_exif_details($path)
{
    $exif = @exif_read_data($path, 'COMPUTED', true);
    if (!$exif) return [];
    $rets = [];
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {

            $rets[$name] =  $val;
        }
    }
    return $rets;
}


/**
 * returns the path of the image.
 * If an Image has wrong url, then it returns null.
 */
function image_path_from_url($url)
{
    $arr = explode('/wp-content/', $url);
    if (count($arr) == 1) return null;
    $path = ABSPATH . 'wp-content/' . $arr[1];
    return $path;
}





function comment_response($comment_id, $options = [])
{
    $comment = get_comment($comment_id, ARRAY_A);
    $ret['comment_ID'] = $comment['comment_ID'];
    $ret['comment_post_ID'] = $comment['comment_post_ID'];
    $ret['comment_parent'] = $comment['comment_parent'];
    $ret['user_id'] = $comment['user_id'];
    $ret['comment_author'] = $comment['comment_author'];
    $ret['comment_content'] = $comment['comment_content'];

    if (isset($options['with_autop']) && $options['with_autop']) {
        $ret['comment_content'] = wpautop(($comment['comment_content']));
    }
    $ret['comment_date'] = $comment['comment_date'];
    $ret['files'] = get_uploaded_files($comment_id, COMMENT_ATTACHMENT);
    /// post author user profile
    ///
    $u = profile($comment['user_id']);
    $ret['user_photo'] = $u['photo'] ?? '';
    // date
    $ret['short_date_time'] = short_date_time($comment['comment_date']);
    return $ret;
}




function file_upload_error_code_message($code)
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;

        default:
            $message = "Unknown upload error";
            break;
    }
    return $message;
}


/**
 * Returns a safe file from a user filename. ( User filename may have characters that are not  supported. like Korean characher ).
 *
 * @param $filename
 *
 * @return string
 */
function get_safe_filename($filename)
{
    $pi = pathinfo($filename);
    $sanitized = md5($pi['filename'] . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . time());
    if (isset($pi['extension']) && $pi['extension']) return $sanitized . '.' . $pi['extension'];
    else return $sanitized;
}


/**
 * Returns true if the user is admin
 * @return bool
 */
function admin()
{
    return current_user_can('manage_options');
}



/**
 * Returns true if the uploaded file belongs to login user.
 * @param $file_ID
 * @return bool
 */
function is_my_file($file_ID)
{
    return is_my_post($file_ID);
}







/**
 * Check if the post belong to the login user.
 * @param $post_ID
 * @return bool
 *      true if the post belongs to the login user.
 *      false otherwise.
 */
function is_my_post($post_ID)
{
    $p = get_post($post_ID);
    if ($p) {
        return $p->post_author == wp_get_current_user()->ID;
    }
    return false;
}




/**
 * Check if the comment belong to the login user.
 * @param $comment_ID
 * @return bool
 *      true if the comment belongs to the login user.
 *      false otherwise.
 */
function is_my_comment($comment_ID)
{
    $c = get_comment($comment_ID);
    if ($c) {
        return $c->user_id == wp_get_current_user()->ID;
    }
    return false;
}


/**
 * @deprecated There is no use case for this method.
 *
 * This function updates(or inserts) multiples fields.
 * - table_update() updates only one(1, single) field while this function updates many fields.
 * - Note that admin can update other user's record.
 * - If the login user is not admin, then he can only update his record.
 *
 * Using this method is recommended since it has better functionality and better tested.
 *
 * @param $in array
 *  - $in['table'] is the table name to update.
 *  - $in['user_ID'] is the unique index for the table to update the record of. So, `user_ID` field must exist as primary key or unique key.
 *  - $in['session_id'] is the login user's session id and this will be ignored.
 *  - The rest of the properties will be saved as table fields. And the fields must exists on the table.
 *
 * @return array|string
 *  - returns an array of the record with ['action' => 'UPDATE'] on update.
 *  - returns an array of the record with ['action' => 'INSERT'] on insert.
 *  - ERROR_UPDATE on update error
 *  - ERROR_INSERT on insert error
 */
function table_updates($in)
{
    $fields = $in;
    unset($fields['route'], $fields['user_ID'], $fields['session_id'], $fields['table']);

    if (isset($in['user_ID'])) {
        if (admin()) {
            $user_ID = $in['user_ID'];
        } else {
            return ERROR_PERMISSION_DENIED;
        }
    } else {
        $user_ID = wp_get_current_user()->ID;
    }

    debug_log("fields::", $fields);
    if (count($fields) == 0) return ERROR_NO_FIELDS;

    global $wpdb;
    $row = $wpdb->get_row("SELECT user_ID FROM $in[table] WHERE user_ID=$user_ID", ARRAY_A);

    if ($row) {
        $fields['updatedAt'] = time();
        $re = $wpdb->update($in['table'], $fields, ['user_ID' => $user_ID]);
        if ($re === false) return sql_error(ERROR_UPDATE);
        else $action = ['action' => 'UPDATE'];
    } else {
        $fields['createdAt'] = time();
        $fields['updatedAt'] = time();
        $re = $wpdb->insert($in['table'], $fields);
        if ($re === false) return sql_error(ERROR_INSERT);
        else $action = ['action' => 'INSERT'];
    }


    $row = $wpdb->get_row("SELECT * FROM $in[table] WHERE user_ID=$user_ID", ARRAY_A);





    return array_merge($action, $row);
}




/**
 * Get the record of user.
 *
 * @note the table must have [user_ID] field. It will returns the record that has same value of user_ID and user's ID.
 *
 * It can be used with combination of update.
 *
 * @param array $in
 * @return array|object|string|void|null
 */
function table_get($in)
{
    $user_ID = wp_get_current_user()->ID;
    global $wpdb;
    return $wpdb->get_row("SELECT * FROM $in[table] WHERE user_ID=$user_ID", ARRAY_A);
}




/**
 * Returns requested url domain
 * @return mixed
 */
function get_host_name()
{
    if (isset($_SERVER['HTTP_HOST'])) return $_SERVER['HTTP_HOST'];
    else return null;
}

/**
 * Alias of get_host_name()
 * @return mixed|null
 */
function get_domain()
{
    return get_host_name();
}
/**
 * Alias of get_host_name()
 * @return mixed|null
 */
function get_domain_name()
{
    return get_host_name();
}


/**
 * 1차 도메인을 리턴한다.
 * @param string|null $_domain 테스트 할 도메인
 * @return string
 *
 * @see api/phpunit/GetDomainNamesTest.php for test.
 */
function get_root_domain(string $_domain = null): string {
    if ( $_domain == null ) $_domain = get_domain_name();
    if ( empty($_domain) ) return '';

    $_root_domains = ['.com', '.net', '.co.kr', '.kr'];
    foreach( $_root_domains as $_root ) {
        if ( stripos($_domain, $_root) !== false ) {
            $_without_root = str_ireplace($_root, '', $_domain);
            $_parts = explode('.', $_without_root);
            $_1st = array_pop($_parts);
            $_domain = $_1st . $_root;
            break;
        }
    }
    return $_domain;
}


function isCli()
{
    return php_sapi_name() == 'cli';
}

/**
 * Returns the URL of the domain.
 *
 * Wordpress `home_url()` returns the url that is set on `wp_options`. But we made it as multi theme supporting multi
 * domains, so each theme may have different domain. Use this method to get home url of each domain.
 *
 * @attention it depends on the api url. if the client browser url is 'abc.com' and apiUrl domain is 'def.com', it wil return 'def.com'.
 *
 * @return string
 *  - http://abc.com
 *  - https://xxx.abc.com
 */
function get_requested_host_url()
{

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    $url .= get_host_name();

    return $url;
}
/**
 * Returns get current URL that appears on web browser URL address bar.
 * @return string
 */
function get_current_url()
{
    // Append the requested resource location to the URL
    return get_requested_host_url() . $_SERVER['REQUEST_URI'];
}


function sql_injection_test($sql)
{
    if (strpos($sql, ';') !== false) return ERROR_SQL_INJECTION . ':;';
    if (stripos($sql, 'INSERT ') !== false) return ERROR_SQL_INJECTION . ':INSERT';
    if (stripos($sql, 'REPLACE ') !== false) return ERROR_SQL_INJECTION . ':REPLACE';
    if (stripos($sql, 'UPDATE ') !== false) return ERROR_SQL_INJECTION . ':UPDATE';
    if (stripos($sql, 'DELETE ') !== false) return ERROR_SQL_INJECTION . ':DELETE';
    if (stripos($sql, 'SELECT ') !== false) return ERROR_SQL_INJECTION . ':SELECT';
    if (stripos($sql, 'WHERE ') !== false) return ERROR_SQL_INJECTION . ':WHERE';
    if (stripos($sql, 'FROM ') !== false) return ERROR_SQL_INJECTION . ':FROM';
    if (stripos($sql, 'CREATE ') !== false) return ERROR_SQL_INJECTION . ':CREATE';
    if (stripos($sql, 'DROP ') !== false) return ERROR_SQL_INJECTION . ':DROP';
    if (stripos($sql, 'JOIN ') !== false) return ERROR_SQL_INJECTION . ':JOIN';
    if (stripos($sql, ' TABLE ') !== false) return ERROR_SQL_INJECTION . ':TABLE';
    return null;
}

/**
 * Direct SQL query to database.
 *
 * @note Only public tables can be queries.
 *
 * @param $in
 * @return array|object|string|null
 */
function sql_query($in)
{

    global $wpdb;
    $table = $in['table'];
    $where = stripslashes($in['where']);

    if (!in_array($table, PUBLIC_TABLES)) return ERROR_PUBLIC_TABLES;

    $re = sql_injection_test($table);
    if ($re) return $re;
    $re = sql_injection_test($where);
    if ($re) return $re;

    $q =  "SELECT * FROM $table WHERE $where";
    debug_log("sql_query: $q");
    return $wpdb->get_results($q, ARRAY_A);
}



/**
 * Returns an error code based on wordpress SQL error message.
 *
 * @usage Use this method to return proper SQL error code whenever there is an SQL query error.
 *
 * @param null $default_error is the default error code when the last error is unknown.
 * @return mixed|null
 */
function sql_error($default_error = null)
{
    global $wpdb;
    $last_error = $wpdb->last_error;
    if ($last_error) {
        if (strpos($last_error, 'Unknown column') !== false) {
            return ERROR_UNKNOWN_COLUMN;
        }
    }
    return $default_error . ":$last_error";
}


/**
 * Gets user_ID ( or any field ) from two dimensional array.
 *
 * @param $users
 * @param string $field
 * @return array
 *
 * @example
 *  ids([ ['user_ID'=>1, ...], [], ... ])
 */
function ids($users, $field = 'user_ID')
{
    $ret = [];
    foreach ($users as $u) {
        $ret[] = $u[$field];
    }
    return $ret;
}


function between($val, $min, $max)
{
    return $val >= $min && $val <= $max;
}


/**
 * @param $in
 * 
 * $in['author'] is the author ID.
 * 
 * if both $in['category_name'] and $in['author'] is not provided, it will return an error.
 * if only $in['category_name'] is provided, it will return all posts from that category.
 * if only $in['author'] is provided, it will return all posts from that author.
 *
 * $in['posts_per_page'] is the number of posts to get.
 * $in['paged'] is the page to get.
 * 
 * @see the params at https://developer.wordpress.org/reference/classes/wp_query/parse_query/
 * @return array|string
 */
function forum_search($in)
{

//    if (!isset($in['category_name']) && !isset($in['author'])) return ERROR_EMPTY_CATEGORY_OR_ID;
    // @deprecated @todo if 'category_name' is empty, then it will search all posts.
//    if ($in['category_name'] == 'all_posts') $in['category_name'] = '';

    $posts = get_posts($in);

    $rets = [];
    foreach ($posts as $p) {
        $rets[] = post_response($p);
    }
    return $rets;
}

/**
 * Sanitize for display posts as latest.
 * @param $in
 */
function latest_search($in) {
    $posts = forum_search($in);
    if ( api_error($posts) ) return $posts;
    if ( empty($posts) ) return [];
    $rets = [];
    foreach($posts as $post) {
        $post['post_title'] = mb_strcut($post['post_title'], 0, 60);
        $post['post_content'] = mb_strcut($post['post_content'], 0, 60);
        $rets[] = $post;
    }
    return $rets;
}


/**
 * Returns posts that has photos.
 * @param $in
 * @return array|string
 *
 * @example
 *  $posts = latest_photos(['category_name'=>'qna']);
 */
function latest_photos($in) {
    $in['meta_query'] = [
        'relation' => 'AND',
        [
            'key' => 'files',
            'value' => '',
            'compare' => '!='
        ]
    ];
    return latest_search($in);
}

/**
 * Returns post from its guid.
 * @note it double check for the http protocol changes.
 * @param $guid
 * @return WP_Post
 *
 * @example to get the post of the current page
 *  print_r( get_post_from_guid( home_url() . $_SERVER['REQUEST_URI'] ) );
 */
function get_post_from_guid($guid)
{
    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
    if ($id) return get_post($id);
    if (stripos($guid, 'http://') !== false) {
        $guid = str_replace('http://', 'https://', $guid);
        $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
    }
    if (stripos($guid, 'https://') !== false) {
        $guid = str_replace('https://', 'http://', $guid);
        $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
    }
    if ($id) return get_post($id);
    return null;
}

/**
 * Returns an array of the names and slugs of categories of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_categories_of_post($post_ID)
{
    $post_categories = wp_get_post_categories($post_ID, ['fields' => 'all']);
    $cats = [];

    foreach ($post_categories as $c) {
        $cat = get_category($c);
        $cats[] = array('name' => $cat->name, 'slug' => $cat->slug);
    }
    return $cats;
}

/**
 * Returns an array of slugs of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_slugs_of_post($post_ID)
{
    $post_categories = wp_get_post_categories($post_ID, ['fields' => 'all']);
    $cats = [];

    foreach ($post_categories as $c) {
        $cat = get_category($c);
        $cats[] = $cat->slug;
    }
    return $cats;
}
/**
 * Returns an array of category IDs of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_category_IDs_of_post($post_ID)
{
    return wp_get_post_categories($post_ID);
}

/**
 * @param $in
 *   - when $in['ID'] is set, post_title, post_content, category will be preserved even if they are not set.
 * @return array|mixed|string
 *
 *
 */
function api_edit_post($in)
{

    if (!isset($in['ID']) && !isset($in['category'])) {
        return ERROR_EMPTY_CATEGORY_OR_ID;
    }

    $data = [
        'post_author' => my('ID'),
        'post_status' => 'publish'
    ];


    if (isset($in['ID'])) {
        $post = get_post($in['ID']);
        // Preserve old properties.
        //            if (in('category') == null) $data['post_category'] = $post->post_category;
        //            if (in('post_title') == null) $data['post_title'] = $post->post_title;
        //            if (in('post_content') == null) $data['post_content'] = $post->post_content;

        $data['post_title'] = isset($in['post_title']) ? $in['post_title'] : $post->post_title;
        $data['post_content'] = isset($in['post_content']) ? $in['post_content'] : $post->post_content;
        $data['post_category'] = get_category_IDs_of_post($post->ID);
        $data['ID'] = $in['ID'];
        debug_log('Updating data: ', $data);
    } else {
        $data['post_title'] = $in['post_title'] ?? '';
        $data['post_content'] = $in['post_content'] ?? '';
    }

    // If in('ID') is set, it will change category. Or It will create new.
    if (isset($in['category'])) {
        $catID = get_category_ID($in['category']);
        if (!$catID) return ERROR_WRONG_CATEGORY;
        $data['post_category'] = [$catID];
    }

    debug_log('post create or update data: ', $data);
    $ID = wp_insert_post($data, true);
    if (is_wp_error($ID)) {
        return ERROR_FAILED_ON_EDIT_POST . ':' . $ID->get_error_message();
    }


    /**
     * Attach files to the post
     * And save the file IDs as 'files' meta property of the post.
     * If no file is uploaded, then it will save empty string.
     */
    if (isset($in['files'])) {
        $fileIDs = attach_files($ID, $in['files']);
        update_post_meta($ID, 'files', $fileIDs);
    }

    if (isset($in['featured_image_ID'])) {
        set_post_thumbnail($ID, $in['featured_image_ID']);
    }

    update_post_properties($ID, $in);

    // NEW POST IS CREATED => Send notification to forum subscriber
    if (!isset($in['ID'])) {
        $title = $in['post_title'];
        $body = $in['post_content'] ?? '';
        $post = get_post($ID, ARRAY_A);
        $slug = get_first_slug($post['post_category']);
        $data = [
            'senderId' => wp_get_current_user()->ID,
            'id' => $ID,
            'type' => 'post'
        ];
        sendMessageToTopic(NOTIFY_POST . $slug, $title, $body, $post['guid'], $data);
    }

    return post_response($ID);
}


/**
 *
 * @param $in
 *
 *
 * if $in['format'] is set to 'language-first', then language will be the key on its first dimensional array. This may
 * be used for GetX translation.
 * i.e) Return of 'language-first' for FLUTTER (GetX recommended structure)
 *  [
 *   'ko' => ['code' => '...', 'name' => '이름', ...........],
 *   'en' => ['code' => '...', 'name' => 'Name', .......],
 *  ]
 * Other wise, code will be the key of its first dimensional array like below.
 * i.e)
 *   [ 'code' => ['ko' => '...', 'en' => '...' ], 'name' => ['ko' => '이름', 'en' => 'Name' ],
 * @return array
 *
 *
 */
function api_get_translations($in)
{
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " ORDER BY code ASC", ARRAY_A);

    $rets = [];
    // This is 'language-first' format for GetX translation.
    if (isset($in['format']) && $in['format'] === 'language-first') {

        foreach ($rows as $row) {
            if (!isset($rets[$row['language']])) $rets[$row['language']] = [];
            $rets[$row['language']][$row['code']] = $row['value'];
        }
    } else {
        foreach ($rows as $row) {
            if (!isset($rets[$row['code']])) $rets[$row['code']] = [
                'code' => $row['code'],
            ];
            $rets[$row['code']][$row['language']] = $row['value'];
        }
    }

    return ['languages' => get_option(LANGUAGES, []), 'translations' => $rets];
}

function get_translation_by_code($code)
{
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " WHERE code='$code'", ARRAY_A);
}

function api_add_translation_language($in)
{
    if (admin() === false) return ERROR_PERMISSION_DENIED;
    if (!isset($in['language'])) return ERROR_EMPTY_LANGUAGE;
    $languages = get_option(LANGUAGES, []);
    if (in_array($in['language'], $languages)) return ERROR_LANGUAGE_EXISTS;

    $languages[] = $in['language'];
    update_option(LANGUAGES, $languages, false);

    return $languages;
}

/**
 * @param $in
 * - Example of input.
 * [
 *   'code' => 'name',
 *   'en' => 'Name',
 *   'ko' => '이름',
 *   'ch' => '...',
 * ]
 * @return mixed|string|null
 * @throws \Kreait\Firebase\Exception\DatabaseException
 */
function api_edit_translation($in)
{
    if (admin() === false) return ERROR_PERMISSION_DENIED;
    if (!isset($in['code'])) return ERROR_EMPTY_CODE;


    $data = $in;

    unset($data['route'], $data['session_id'], $data['code']);

    global $wpdb;
    foreach ($data as $ln => $val) {
        $re = $wpdb->replace(TRANSLATIONS_TABLE, ['code' => $in['code'], 'language' => $ln, 'value' => $val]);
        if ($re === false) return sql_error(ERROR_LANGUAGE_REPLACE);
    }

    api_notify_translation_update();

    return $data;
}

function api_change_translation_code($in)
{
    if (admin() === false) return ERROR_PERMISSION_DENIED;
    if (!isset($in['oldCode'])) return ERROR_EMPTY_OLD_CODE;
    if (!isset($in['newCode'])) return ERROR_EMPTY_NEW_CODE;
    global $wpdb;
    $re = $wpdb->update(TRANSLATIONS_TABLE, ['code' => $in['newCode']], ['code' => $in['oldCode']]);
    if ($re === false) return sql_error(ERROR_CHANGE_CODE);
    api_notify_translation_update();
    return $in;
}

function api_delete_translation($in)
{
    if (admin() === false) return ERROR_PERMISSION_DENIED;
    if (!isset($in['code'])) return ERROR_EMPTY_CODE;

    global $wpdb;

    $code = $in['code'];

    /// check if it exist, return error if not.
    $tr = get_translation_by_code($code);
    if (!$tr) return ERROR_TRANSLATION_NOT_EXIST;

    $re = $wpdb->delete(TRANSLATIONS_TABLE, ['code' => $code]);
    if (!$re) return sql_error(ERROR_DELETING_TRANSLATION);
    api_notify_translation_update();
    return $in;
}


/**
 * Updates global settings.
 *
 * @usage
 *  - Client can save settings through interface.
 *  - PHP can update settings programmatically.
 *
 *
 * @logic
 *  - It saves data into `global_settings` using `update_option()`
 *  - And notify to client by updating Firebase realtime database.
 *
 * @todo 기본 설정을 두고, 도메인마다 다른 설정을 사용 할 수 있도록 해 준다.
 * @todo 가입 약관과 개인 정보 보호는 양이 많으므로, 다른 설정으로 뺀다.
 */
function api_update_settings($data) {
    update_option('global_settings', $data, false);
    api_notify_app_update('settings');
}
function api_get_settings() {

    $options = get_option('global_settings');
//    if ( ! $options ) return $options;
    if ( ! $options ) return [];

    $ret = [];
    /// Strip slashes for quotes.
    foreach( $options as $k => $v ) {
        if ( is_string($v) ) {
            $ret[$k] = stripslashes( $v );
        } else {
            $ret[$k] = $v;
        }
    }
    return $ret;
}

/**
 * Update the document under 'notification' in Firebase RealTime Database.
 * @throws \Kreait\Firebase\Exception\DatabaseException
 */
function api_notify_app_update($document) {
    if (get_phpunit_mode()) return;
    $db = getDatabase();
    $reference = $db->getReference("notifications/" . $document);
    $stamp = time();
    $reference->set(['updatedAt' => $stamp]);
}

/**
 * Update the 'notification/translation' document in Firebase RealTime Database.
 * Clients may listen the document change and update the translations.
 */
function api_notify_translation_update()
{
    api_notify_app_update('translation');
}


/**
 * Get domain theme name
 *
 * @note if the page has admin folder, then it goes to admin theme.
 * @param bool $admin 만약 admin=true 인 경우, 사용자가 관리자 페이지에 있으면 admin 테마를 리턴한다.
 * @return string
 */
function get_domain_theme($admin=true)
{
    if (API_CALL) return null;
    if ($admin && is_admin_page()) return 'admin';
    global $domain_themes;
    if (!isset($domain_themes)) return null;
    $_host = get_host_name();
    $theme = 'default';
    foreach ($domain_themes as $_domain => $_theme) {
        if (stripos($_host, $_domain) !== false) {
            $theme = $_theme;
            break;
        }
    }
    return $theme;
}


/**
 * Debug print on web
 *
 * @param $obj
 * @example
 * d(DOMAIN_THEME_URL);
 */
function d($obj)
{
    echo "\n<pre>\n";
    $str = print_r($obj, true);
    $str = str_replace("<", "&lt;", $str);
    echo $str;
    echo "\n</pre>\n";
}


/**
 * This accesses(calls) the route.
 *
 * Use this function to test(or to call) the route.
 *
 * @param $params
 * @return mixed
 *
 * @example
 *  wp_set_current_user(2);
 *  $profile = profile();
 *  $re = getRoute(['route' => 'purchase.createHistory', 'session_id' => $profile['session_id']]);
 */
function getRoute($params)
{
    $url = API_URL . "?" . http_build_query($params);
    //    echo "url: $url\n";
    $re = file_get_contents($url);
    if (!$re) {
        echo "\n";
        echo "\n* -------------------------------- WARNING -------------------------------- *";
        echo "\n*";
        echo "\n* There is no return data from backend api.";
        echo "\n*";
        echo "\n* Is backend api url correct?";
        echo "\n* API URL: " . API_URL;
        echo "\n*";
        echo "\n* If it's wrong, update API_URL_ON_CLI in config.php to backend api url for test.";
        echo "\n*";
        echo "\n* -------------------------------- WARNING -------------------------------- *";
    }
    $json = json_decode($re, true);
    if (!$json) {
        print_r($re);
    }
    return $json;
}


/**
 * Returns true if the web is running on localhost (or developers computer).
 * @return bool
 */
function is_localhost()
{
    if (isCli()) return false;
    $localhost = false;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || PHP_OS === 'Darwin') $localhost = true;
    else {
        $ip = $_SERVER['SERVER_ADDR'];
        if (strpos($ip, '127.0.') !== false) $localhost = true;
        else if (strpos($ip, '192.168.') !== false) $localhost = true;
    }
    return $localhost;
}


function onCommentCreateSendNotification($comment_id, $in)
{
    /**

     * 1) get post owner id
     * 2) get comment ancestors users_id
     * 3) make it unique to eliminate duplicate
     * 4) get topic subscriber
     * 5) remove all subscriber from token users
     * 6) get users token
     * 7) send batch 500 is maximum
     */

    /**
     *
     *  get all the user id of comment ancestors. - name as '$users_id'
     *  get all the user id of topic subscribers. - named as 'topic subscribers'.
     *  remove users of 'topic subscribers' from 'token users'. - with array_diff($array1, $array2) return the array1 that has no match from array2
     *  get the tokens of the users_id and filtering those who want to get comment notification
     *  
     */

    $post = get_post($in['comment_post_ID'], ARRAY_A);
    $users_id = [];

    /**
     * add post owner id
     */
    if (!is_my_post($post['post_author'])) {
        $users_id[] = $post['post_author'];
    }

    /**
     * get comment ancestors id
     */
    $comment = get_comment($comment_id);
    if ($comment && $comment->comment_parent) {
        $users_id = array_merge($users_id, getAncestors($comment->comment_ID));
    }

    /**
     * get unique user ids
     */
    $users_id = array_unique($users_id);

    /**
     * get user who subscribe to comment forum topic
     */
    $slug = get_first_slug($post['post_category']);
    $topic_subscribers = getForumSubscribers(NOTIFY_COMMENT . $slug);

    /**
     * remove users_id that are registered to comment topic
     */
    $users_id = array_diff($users_id, $topic_subscribers);

    /**
     * get token of user that are not registered to forum comment topic and want to get notification on user settings
     */
    $tokens = getTokensFromUserIDs($users_id, NOTIFY_COMMENT);


    /**
     * set the title and body, etc.
     */
    $title              = $post['post_title'];
    $body               = $in['comment_content'];
    $click_url          = $post['guid'];
    $data               = [
        'senderId' => wp_get_current_user()->ID,
        'type' => 'post',
        'id'=> $comment->comment_post_ID
    ];

    /**
     * send notification to users who subscribe to comment topic
     */
    sendMessageToTopic(NOTIFY_COMMENT . $slug, $title, $body, $click_url, $data);

    /**
     * send notification to comment ancestors who enable reaction notification
     */
    if (!empty($tokens)) sendMessageToTokens( $tokens, $title, $body, $click_url, $data);
}


/**
 * @param $comment_ID
 * @return mixed
 */
function get_ancestor_tokens_for_push_notifications($comment_ID)
{
    $asc = getAncestors($comment_ID);
    return getTokensFromUserIDs($asc);
}


/**
 * @param array $ids
 * @param null $filter 'notifyComment' || 'notifyPost'
 * @return array
 */
function getTokensFromUserIDs($ids = [], $filter = null)
{
    $tokens = [];
    foreach ($ids as $user_id) {
        $rows = getUserTokens($user_id);
        if ($filter) {
            if (get_user_meta($user_id, $filter, true) == 'Y') {
                foreach ($rows as $token) {
                    $tokens[] = $token;
                }
            }
        } else {
            foreach ($rows as $token) {
                $tokens[] = $token;
            }
        }
    }
    return $tokens;
}

/**
 * Returns an array of user ids that are in the path(tree) of comment hierarchy.
 *
 * @note it does not include the login user and it does not have duplicated user id.
 *
 * @param $comment_ID
 *
 * @return array
 *
 *
 */
function getAncestors($comment_ID)
{

    $comment = get_comment($comment_ID);
    $asc     = [];


    while (true) {
        $comment = get_comment($comment->comment_parent);
        if ($comment) {
            if ($comment->user_id == wp_get_current_user()->ID) {
                continue;
            }
            $asc[] = $comment->user_id;
        } else {
            break;
        }
    }

    $asc = array_unique($asc);

    return $asc;
}

function getUserTokens($user_ID)
{
    global $wpdb;
    $rows =  $wpdb->get_results("SELECT token FROM " . PUSH_TOKENS_TABLE . " WHERE user_ID=$user_ID", ARRAY_A);
    $tokens = [];
    foreach ($rows as $user) {
        $tokens[] = $user['token'];
    }
    return $tokens;
}


/**
 * @param $topic - topic as string
 * @return array - array of user ids
 */
function getForumSubscribers($topic = '')
{
    global $wpdb;
    $rows = $wpdb->get_results("SELECT user_id FROM wp_usermeta WHERE meta_key='$topic' AND meta_value='Y' ", ARRAY_A);
    $ids = [];
    foreach ($rows as $user) {
        $ids[] = $user['user_id'];
    }
    return $ids;
}

/**
 * @param $user_ID
 * @return array -  array of topics that starts with the default topic prefix
 */
function getUserForumTopics($user_ID) {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT meta_key FROM wp_usermeta WHERE meta_key LIKE '". DEFAULT_NOTIFY_PREFIX ."%' AND meta_value='Y' AND user_id=$user_ID ", ARRAY_A);
    $topics = [];
    foreach ($rows as $user) {
        $topics[] = $user['meta_key'];
    }
    return $topics;
}

/**
 * Updates category
 *
 * It can update only one field and value. Or it can update multiple fields and values.
 *
 * @param array $in
 *   If $in['field'] and $in['value'] exists, then it is only one field and value updated. Or It will update multiple fields.
 *
 *
 *  - $in['cat_ID'] is the category term id
 *  - $in['field'] is the category field(property) to update.
 *    $in['field'] can be one of
 *      - 'cat_name' - category name or title.
 *      - 'category_description' category description.
 *      - 'category_parent' is the parent category.
 *      - And any name & value meta data can be saved as property and value
 *  - $in['value'] is the value.
 *
 *  - Example of input for one field update.
 *  [ 'cat_ID' => 1, 'field' => 'cat_name', 'value' => 'This is title' ]
 *  [ 'cat_ID' => 1,'field' => 'A', 'value' => 'Apple' ]
 *
 *  - Example of input for multiple fields update.
 *  [ 'cat_ID' => 1, 'cat_name' => 'title', 'category_description' => 'This is description', 'A' => 'Apple' ]
 *
 * @return mixed
 *  - error code on error.
 *  - Array of WP_Term Object of the category term object. @see https://developer.wordpress.org/reference/classes/wp_term/
 */
function update_category($in)
{

    if (!isset($in['cat_ID'])) return ERROR_EMPTY_CATEGORY_ID;
    $cat = get_category($in['cat_ID']);
    if ($cat == null) return ERROR_CATEGORY_NOT_EXIST_BY_THAT_ID;

    if (isset($in['field']) && isset($in['value'])) {
        $re = update_category_meta($in);
    } else {
        foreach ($in as $k => $v) {
            if ($k == 'session_id') continue;
            if ($k == 'route') continue;
            if ($k == 'cat_ID') continue;
            $re = update_category_meta(['cat_ID' => $in['cat_ID'], 'field' => $k, 'value' => $v]);
            if ($re) break;
        }
    }
    /**
     * if error
     */
    if ($re) return $re;

    $ret = get_category($in['cat_ID'])->to_array();

    $metas = get_term_meta($in['cat_ID'], '', true);
    foreach ($metas as $key => $values) {
        $ret[$key] = $values[0];
    }

    return $ret;
}

/**
 *
 * Updates a field(or a meta) of a category.
 *
 * @param $in
 * @return int|string
 */
function update_category_meta($in)
{
    if (in_array($in['field'], ['cat_name', 'category_description', 'category_parent'])) {
        $re = wp_update_category(['cat_ID' => $in['cat_ID'], $in['field'] => $in['value']]);
        if (is_wp_error($re)) {
            return $re->get_error_message();
        }
    } else {
        $re = update_term_meta($in['cat_ID'], $in['field'], $in['value']);
        if (is_wp_error($re)) {
            return $re->get_error_message();
        }
    }
    return 0;
}


/**
 * Returns the slug of first category of the post categories
 * @param $categories
 *
 * @return string
 */
function get_first_slug($categories)
{
    // get post slug as category name and pass
    if (count($categories)) {
        $cat = get_category($categories[0]);
        return $cat->slug;
    } else {
        return '';
    }
}

/**
 * Helper function of `get_term_meta`.
 * @param $cat_ID
 * @param $name
 * @param string $default_value
 * @return mixed|string
 */
function category_meta($cat_ID, $name, $default_value = '')
{
    $v = get_term_meta($cat_ID, $name, true);
    run_hook(__FUNCTION__, $name, $v);
    if ($v) return $v;
    else return $default_value;
}


function isSubscribedToTopic($topic)
{
    return my($topic) === "Y";
}

function pass_login_url($state = '')
{
    return "https://id.passlogin.com/oauth2/authorize?client_id=" . PASS_LOGIN_CLIENT_ID . "&redirect_uri=" . urlencode(PASS_LOGIN_CALLBACK_URL) . "&response_type=code&state=$state&prompt=select_account";
}


function pass_login_aes_dec($str)
{
    $key_128 = substr(PASS_LOGIN_CLIENT_SECRET_KEY, 0, 128 / 8);
    return openssl_decrypt(base64_decode($str), 'AES-128-CBC', $key_128, true, $key_128);
}

function pass_login_callback($in)
{
    // @todo PASS 휴대폰번호 로그인을 하면, Callback URL 이 호출된다. 그리고 그 정보를 기록한다.
    // Callback url 이 처음 호출 되면,
    //   Array ( [code] => lzRdPT, [state] => apple_banana_cherry )
    // 와 같은 값이 넘어 온다.
    // 이, code 로, 사용자 정보를 가져온다.
    if (!isset($in['code'])) return ERROR_EMPTY_CODE;
    $code = $in['code'];
    $state = $in['state'] ?? '';

    // Step 1. 백엔드에서 PASS 서버로 로그인해서, access_token 을 가져온다. 참고로 Refresh 를 하면 Invalid authorization code 에러가 난다.
    // 로그인 성공하면, ["access_token" => "...", "token_type" => "bearer", "expires_in" => 600, "state" => "..."] 와 같은 정보가 나온다.
    $url = "https://id.passlogin.com/oauth2/token";

    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . base64_encode(PASS_LOGIN_CLIENT_ID . ":" . PASS_LOGIN_CLIENT_SECRET_KEY),
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
    curl_setopt_array($ch, $o);

    try {
        $response = curl_exec($ch);
        $re = json_decode($response, true);
        if (isset($re['error'])) {
            echo "<h1>[ ERROR: $re[error], MESSAGE: $re[message]</h1>";
            return;
        }
        // @todo leave log
        //            file_put_contents($log_file, $response ."\r\n\r\n");
    } catch (exception $e) {
        // @todo leave log
        d($e);
    }
    curl_close($ch);



    /// Step 2. access_token 의 회원 정보를 가져온다. access_token 당 1회만 조회 가능. 주의: 자동 로그인을 할 때에는 전화번호나 기타 정보가 따라오지 않는다. 그래서 가능하면 로그인을 한번하고 로그인을 끊어 줘야 한다.

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
    curl_setopt_array($ch, $o);

    try {
        $response = curl_exec($ch);
        $re = json_decode($response, true);
        if (isset($re['error']) && $re['error']) echo "[ ERROR: $re[error], MESSAGE: $re[message] ]";
        // @todo leave log
        //            file_put_contents($log_file, $response ."\r\n\r\n");
    } catch (exception $e) {
        // @todo leave log
        d($e);
    }
    curl_close($ch);

    $user = $re['user'];
    $ret = [];

    if (isset($user['ci']) && $user['ci']) {
        $ret['ci'] = pass_login_aes_dec($user['ci']);
    }
    if (isset($user['phoneNo']) && $user['phoneNo']) {
        $ret['phoneNo'] = pass_login_aes_dec($user['phoneNo']);
    }
    if (isset($user['name']) && $user['name']) {
        $ret['name'] = pass_login_aes_dec($user['name']);
    }
    if (isset($user['birthdate']) && $user['birthdate']) {
        $ret['birthdate'] = pass_login_aes_dec($user['birthdate']);
    }

    if (isset($user['gender']) && $user['gender']) {
        $ret['gender'] = $user['gender'];
    }

    if (isset($user['agegroup']) && $user['agegroup']) {
        $ret['agegroup'] = $user['agegroup'];
    }

    if (isset($user['foreign']) && $user['foreign']) {
        $ret['foreign'] = $user['foreign'];
    }

    if (isset($user['telcoCd']) && $user['telcoCd']) {
        $ret['telcoCd'] = $user['telcoCd'];
    }

    if (isset($user['autoLoginYn']) && $user['autoLoginYn']) {
        $ret['autoLoginYn'] = $user['autoLoginYn'];
    }

    if (isset($user['autoStatusCheck']) && $user['autoStatusCheck']) {
        $ret['autoStatusCheck'] = $user['autoStatusCheck'];
    }


    //    d($user);

    return $ret;
}


function pass_login_or_register($user)
{

    if (isset($user['ci']) && $user['ci']) {
        /// 처음 로그인 또는 자동 로그인이 아닌 경우,
        $user['user_email'] = PASS_LOGIN_MOBILE_PREFIX . "$user[phoneNo]@passlogin.com";
        $user['user_pass'] = md5(PASS_LOGIN_SALT . PASS_LOGIN_CLIENT_ID . $user['phoneNo']);
        $profile = login_or_register($user);
    } else {
        /// plid 가 들어 온 경우, meta 에서 ci 를 끄집어 낸다.
        $users = get_users(['meta_key' => 'plid', 'meta_value' => $user['plid']]);
        $found = $users[0];
        $profile = profile($found->ID);
    }



    return $profile;
}

/// ================================================================================================================
///
/// Debug Mode or Test Mode
///
/// set_phpunit_mode(bool) set or unset phpunit test mode.
/// get_phpunit_mode() return true or false. If it is true, then it is phpunit test mode.
///
/// ================================================================================================================
$_phpunit_mode = false;
/**
 * To set or unset phpunit test mode.
 *
 * Use this when you want to exclude(or include) some codes for php unit test mode.
 *
 * @param $b
 */
function set_phpunit_mode($b)
{
    global $_phpunit_mode;
    $_phpunit_mode = $b;
}
function get_phpunit_mode(): bool
{
    global $_phpunit_mode;
    return $_phpunit_mode;
}




/**
 *
 * 카테고리와 서브카테고리 구조를 유지하는 카테고리 목록 리턴.
 *
 * 부모 카테고리와 자식 카테고리의 관계를 표시하고자 할 때 사용.
 * @return array
 *
 * @example of return
Array
(
[0] => WP_Term Object
(
[term_id] => 1
[parent] => 0
)

[1] => WP_Term Object
(
[term_id] => 7
[name] => communities
[parent] => 0
)
[2] => WP_Term Object
(
[term_id] => 5
[name] => Discussion
[parent] => 7
)
 *
 */
function get_category_list()
{
    $categories = get_root_categories();
    $rets = [];
    foreach ($categories as $cat) {
        $rets[] = $cat;
        $children = get_child_categories($cat->term_id);
        foreach ($children as $child) {
            $rets[] = $child;
        }
    }
    return $rets;
}


/**
 * 계층적 카테고리 목록
 *
 * 카테고리를 계층적으로 recursive 하게 호출해서, 모든 카테고리를 리턴한다.
 * 만약, 필터링을 하고 싶으면, 모든 결과를 가져 온 다음, 필터링 하면 된다.
 *
 * @param int $category is the root category ( or the start category to show below )
 * @return array
 */
function get_category_tree($category=0, $depth=0) {
    $r = [];
    $args = array(
        'taxonomy' => 'category',
        'parent' => $category,
        'hide_empty' => false,
    );
    $next = get_terms($args);
    if ($next) {
        foreach ($next as $cat) {
            $cat->depth = $depth;
            $r[] = $cat;
            $r = array_merge($r, get_category_tree($cat->term_id, $depth + 1) );
        }
    }
    return $r;
}

/**
 * Returns an array of WP_Term Objects of categories that are the top categories.
 * @return array
 */
function get_root_categories()
{
    $args = array(
        'taxonomy' => 'category',
        'parent' => '0',
        'hide_empty' => false,
    );
    return get_categories($args);
}

function get_all_categories() {
    $args = array(
        'taxonomy' => 'category',
        'hide_empty' => false,
    );
    return get_categories($args);
}

/**
 * 현재 카테고리($term_id) 의 자식 카테고리를 리턴한다.
 * @param int $term_id
 * @param string $taxonomy
 * @return array
 */
function get_child_categories($term_id = 0, $taxonomy = 'category')
{
    $children = get_categories(array(
        'child_of'      => $term_id,
        'taxonomy'      => $taxonomy,
        'hide_empty' => false,
    ));
    return $children;
}


/**
 * @param string $sortby - 이 값이 CountryNameEn 이면, 국가 이름ㅇ르 영어 이름 정렬한다. 기본적으로 한글 정렬.
 * @return mixed
 */
function country_code($sortby='CountryNameKR') {
    $countries = json_decode(file_get_contents(THEME_DIR . '/etc/data/country-code.json'), true);
    usort($countries, function($a, $b) use ($sortby) {
        if ($a[$sortby] == $b[$sortby]) return 0;
        else if ( $a[$sortby] > $b[$sortby] ) return 1;
        else return -1;
    });

    return $countries;
}

function country_name($code, $lang="CountryNameKR") {
    $countries = json_decode(file_get_contents(THEME_DIR . '/etc/data/country-code.json'), true);
    return $countries[$code][$lang];
}


/**
 * Hook system
 */
$_hook_functions = [];
function add_hook($name, $function) {
    global $_hook_functions;
    if ( ! isset($_hook_functions[$name]) ) $_hook_functions[$name] = [];
    $_hook_functions[$name][] = $function;
}
function run_hook($name, &...$vars) {
    global $_hook_functions;
    if ( isset($_hook_functions[$name]) ) {
        foreach( $_hook_functions[$name] as $func ) $func(...$vars);
    }
}

