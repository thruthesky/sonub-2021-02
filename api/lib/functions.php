<?php

require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

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



    if (stripos($contentType, 'application/json') !== false ) {
        $_REQUEST = get_JSON_input();
    }

    if ($name === null) {
        return $_REQUEST;
    }
    if (isset($_REQUEST[$name])) {
        return $_REQUEST[$name];
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


function success_or_error($data) {
//    echo "===> data: \n";
//    print_r($data);
    if ( $data === null || $data == '' ) error(ERROR_EMPTY_RESPONSE);
    if ( is_string($data) ) error($data);
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
function replace_host_of_image_url_to_request_host($data, $apiUrl = null) {

    if(!defined('REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST') || REPLACE_HOST_OF_IMAGE_URL_TO_REQUEST_HOST == false) return $data;

    // Get current(API) url like "https://abc.com/v3/index.php"
    if ( $apiUrl ) $current_url = $apiUrl;
    else {
        $current_url = get_current_url();
    }


    debug_log("current_url: $current_url");

    // Get current(API) url of host only. like "https://abc.com"
    $arr = explode('/v3', $current_url);
    $current_home_url = $arr[0];
    debug_log("current_home_url: $current_home_url");
    array_walk_recursive($data, function(&$value, $key) use ($current_home_url) {

        // Is the value image URL?
        if ( is_string($value) && strpos($value, '/wp-content/uploads/') !== false ) {
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
 * This method let the user log-in with $_REQUEST['session_id'].
 *
 *
 * @return WP_User or Error object.
 *
 */
function authenticate()
{
    return session_login(in('session_id'));
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
//    debug_log("ID: $ID");
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
 * Returns a session_id (which never changes). This means, even if user changes his data, it can still validate the user's auth.
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
    if ( $user === null) {
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

function my($field) {
    if ( $field == 'ID' ) {
        return wp_get_current_user()->ID;
    } else if ( $field == 'email' ) {
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


    userUpdateMeta($user->ID, $data);

    wp_set_current_user($user->ID);

    return profile();
}




function profileUpdate($in) {
    userUpdateMeta(wp_get_current_user()->ID, $in);
    return profile();
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

    userUpdateMeta($user_ID, $in);

    wp_set_current_user($user_ID);

    return profile();
}

define('NOTIFY_POST', 'notifyPost');
define('NOTIFY_COMMENT', 'notifyComment');

function userUpdateMeta($user_ID, $data) {
    foreach ($data as $k => $v) {
        if (!in_array($k, USER_META_EXCEPTIONS)) {
            update_user_meta($user_ID, $k, $v);
            if( strpos($k, NOTIFY_POST) === 0 || strpos($k, NOTIFY_COMMENT) === 0) {
                // @TODO Subscribe here when meta was updated.
            }
        }
    }
}






function userMetas($user_ID)
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
 * @WARNING This must be the ONLY method to be used to return user information to client end.
 *
 *
 * @param number $user_ID - user ID or session id.
 *
 * @return array
 *  - if it cannot find user information, it return an empty array.
 */
function profile($user_ID=null)
{
    if ( $user_ID === null ) {
        $user_ID = wp_get_current_user()->ID;
    }
    if ( is_string($user_ID) && strpos($user_ID, '_') !== false) {
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

    $data = array_merge(userMetas($user_ID), $data);

    // profile photo url
    $data['profile_photo_url'] = get_gallery_featured_image_url($user_ID);


    foreach($data as $k => $v ) {
        if ( in_array($k, USER_META_EXCEPTIONS_FOR_CLIENT) ) unset($data[$k]);
    }

    if ( admin() ) $data['admin'] = true;

    return $data;
}


/**
 * Returns true if the input is error.
 * @param $obj
 * @return bool
 * @todo change name to api_error()
 */
function api_error($obj) {
    if ( $obj && is_string($obj) && strpos($obj, 'ERROR_') === 0 ) return true;
    else return false;
}


/**
 * @param $in
 * @return array|string
 * @todo change kebab case.
 */
function updateUserLocation($in) {
    global $wpdb;
    $data = [
        'user_ID' => $in['user_ID'] ??  wp_get_current_user()->ID,
        'latitude' => $in['latitude'],
        'longitude'=> $in['longitude'],
        'accuracy'=> $in['accuracy'] ?? 0,
        'altitude'=> $in['altitude'] ?? 0,
        'speed'=> $in['speed'] ?? 0,
        'heading'=> $in['heading'] ?? 0,
        'time'=> $in['time'] ?? 0,
    ];
    $re = $wpdb->replace(LOCATION_TABLE, $data);
    if ( $re ) return $data;
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
function userSearchByLocation($in) {

    $LATITUDE = $in['latitude'];
    $LONGITUDE = $in['longitude'];
    $DISTANCE_KILOMETERS = $in['km'];
    $LIMIT = $in['limit'] ?? 15;
    $TABLE = LOCATION_TABLE;
    $FIELDS = isset($in['fields']) ? $in['fields'] : '*';

    $sql=<<<EOS
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
    if ( $results ) return $results;
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
function get_route($in) {
    $route = $in['route'] ?? null;
    if ( empty($route) ) return ERROR_EMPTY_ROUTE;
    $arr = explode('.', $route);
    if ( count($arr) != 2 ) return ERROR_MALFORMED_ROUTE;

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

function end_if_error($code) {
    if ( api_error($code) ) error($code);
    return $code;
}

/**
 *
 * @param $in
 * @return array|mixed|string
 *
 *  - ['mode' => 'login'] will be returned if the user logged in
 *  - ['mode' => 'register'] will be returned if the user registered.
 */
function loginOrRegister($in) {
    $re = login($in);
    debug_log("login:", $re);
    if ( api_error($re) ) {
        if ( $re == ERROR_USER_NOT_FOUND_BY_THAT_EMAIL ) {
            $re = register($in);
            debug_log("register: ", $re);
            if ( api_error($re) ) return $re;
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
function get_token($token) {
    global $wpdb;
    return $wpdb->get_row("SELECT * FROM " . PUSH_TOKEN_TABLE .  " WHERE token='$token'", ARRAY_A);
}

/**
 * Returns tokens of login user in an array
 * @return array|object|void|null
 *
 * @example phpunit/SubscribeTopicTest.php
 */
function get_user_tokens($ID=null) {
    global $wpdb;
    if ( $ID ) $user_ID = $ID;
    else $user_ID = wp_get_current_user()->ID;
    $rows = $wpdb->get_results("SELECT * FROM " . PUSH_TOKEN_TABLE .  " WHERE user_ID='$user_ID'", ARRAY_A);
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
function update_token($in) {

    global $wpdb;
    if ( !isset($in['token']) ) return ERROR_EMPTY_TOKEN;
    $token = $in['token'];
    $user_ID = wp_get_current_user()->ID;
    debug_log("user_ID: $user_ID");

    if (empty($user_ID) ) $user_ID = 0;

    $record = get_token($token);



    if ( empty($record) ) {
        // insert
        debug_log(" ['user_ID' => $user_ID, 'token' => $token, 'stamp' => time()] ");
        $re = $wpdb->insert(PUSH_TOKEN_TABLE, ['user_ID' => $user_ID, 'token' => $token, 'stamp' => time()]);
        if ( $re === false ) {
            return ERROR_INSERT;
        }
    } else {
        // update
        $wpdb->update(PUSH_TOKEN_TABLE, ['user_ID' => $user_ID], ['token' => $token]);
    }


    if ( isset($in['topic']) ) {
        $topic = $in['topic'];
        $re = subscribeTopic($topic, $token);
        if ( $re && isset($re['results']) && count($re['results']) && isset($re['results'][0]['error']) ) {
            return ERROR_TOPIC_SUBSCRIPTION;
        }
    }

    return get_token($token);
}





/**
 * @param $in - Array of user id.
 *  $in['subscription'] is the subscription name. If it is set to 'Y', then message will be delivered to the user.
 *    if $in['subscription'] is missing, then it will send message to the user.
 *  For instance, if a user already have 'chat_room_123' with value 'Y' in this meta data,
 *   and $in['subscribe'] is set to 'chat_room_123', then the user will get message.
 *
 * @return mixed \Kreait\Firebase\Messaging\MulticastSendReport
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function send_message_to_users($in) {
    if ( !isset($in['users']) ) return ERROR_EMPTY_USERS;
    if ( !isset($in['title']) ) return ERROR_EMPTY_TITLE;
    if ( !isset($in['body']) ) return ERROR_EMPTY_BODY;
    $all_tokens = [];

    $users = explode(',', $in['users']);
    foreach($users as $ID) {
        if ( isset($in['subscription']) ) {
            $re = get_user_meta($ID, $in['subscription'], true);
            debug_log("ID: $ID, subscription ID: $in[subscription] re: $re");
            if ( $re != 'Y' ) continue;
        }
        $tokens = get_user_tokens($ID);
        $all_tokens = array_merge($all_tokens, $tokens);
    }
    /// If there are no tokens to send, then it will return empty array.
    if ( empty($all_tokens) ) return ERROR_EMPTY_TOKENS;
    if ( !isset($in['data'])) $in['data'] = [];
    if ( !isset($in['imageUrl'])) $in['imageUrl'] = '';
    return sendMessageToTokens($all_tokens, $in['title'], $in['body'], $in['imageUrl'], $in['click_action'], $in['data']);
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
    if (!$files) return;
    if (!is_array($files)) {
        $files = explode(',', $files);
    }
    foreach ($files as $file_ID) {
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
    $post['profile_photo_url'] = get_gallery_featured_image_url($post['post_author']);

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
        $cmt = comment_response($comment['comment_ID']);
        $cmt['depth'] = $comment['depth'];
        $updated_comments[] = $cmt;
    }
    $post['comments'] = $updated_comments;
    //                $post['comments'] = $comments;

    // Add meta datas.
    $metas = get_post_meta($post['ID'], '', true);
    $singles = [];
    foreach ($metas as $k => $v) {
        $singles[$k] = $v[0];
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
function image_exif_details($path) {
    $exif = @exif_read_data($path, 'COMPUTED', true);
    if ( ! $exif ) return [];
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
function image_path_from_url($url) {
    $arr = explode('/wp-content/', $url);
    if ( count($arr) == 1 ) return null;
    $path = ABSPATH . 'wp-content/' . $arr[1];
    return $path;
}





function comment_response($comment_id)
{
    $comment = get_comment($comment_id, ARRAY_A);
    $ret['comment_ID'] = $comment['comment_ID'];
    $ret['comment_post_ID'] = $comment['comment_post_ID'];
    $ret['comment_parent'] = $comment['comment_parent'];
    $ret['user_id'] = $comment['user_id'];
    $ret['comment_author'] = $comment['comment_author'];
    $ret['comment_content'] = $comment['comment_content'];

    /// TODO: for mobile app, comement_content_autop should be removed.
    $ret['comment_content_autop'] = wpautop(($comment['comment_content']));
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
 * General function to update a field of the login user's record.
 *
 * It can insert or update a field of any table.
 *
 * @requirement
 *  - The table must have `user_ID` field as unique index and its value must be the login user's ID.
 *  - The table must also have `createdAt` and `updatedAt` integer field.
 *  - The $in['field'] must exists in the table.
 *  - All the fields of the table should have default value, so it would not produce SQL error while inserting.
 *
 * @note
 *  - `createdAt` will have timestamp on inserting.
 *  - `updatedAt` will have new timestamp on every update.
 *
 * @param $in array
 *  $in['table'] is the table to update.
 *  $in['field'] is the field to update.
 *  $in['value'] is the value to update.
 *
 * @return array|string
 *  - returns an array of the record with ['action' => 'UPDATE'] on update.
 *  - returns an array of the record with ['action' => 'INSERT'] on insert.
 *  - ERROR_UPDATE on update error
 *  - ERROR_INSERT on insert error
 *
 *
 * @example
 *  - See tests/app.update.test.php
 *
 * @note user must login before this call.
 */
function table_update($in) {
    $user_ID = wp_get_current_user()->ID;
    global $wpdb;
    $row = table_get($in);
    if ( $row ) {
        $re = $wpdb->update($in['table'], [$in['field'] => $in['value'], 'updatedAt' => time()], ['user_ID' => $user_ID]);
        if ( $re === false ) return ERROR_UPDATE;
        else $action = ['action' => 'UPDATE'];
    } else {
        $re = $wpdb->insert($in['table'], ['user_ID' => $user_ID, $in['field'] => $in['value'], 'updatedAt'=>time(), 'createdAt'=>time()]);
        if ( $re === false ) return ERROR_INSERT;
        else $action = ['action' => 'INSERT'];
    }

    $row = table_get($in);
    return array_merge($action, $row);
}

/**
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
function table_updates($in) {
    $fields = $in;
    unset($fields['route'], $fields['user_ID'], $fields['session_id'], $fields['table']);

    if ( isset($in['user_ID']) ) {
        if ( admin() ) {
            $user_ID = $in['user_ID'];
        } else {
            return ERROR_PERMISSION_DENIED;
        }
    } else {
        $user_ID = wp_get_current_user()->ID;
    }

    debug_log("fields::", $fields);
    if ( count($fields) == 0 ) return ERROR_NO_FIELDS;

    global $wpdb;
    $row = $wpdb->get_row("SELECT user_ID FROM $in[table] WHERE user_ID=$user_ID", ARRAY_A);

    if ( $row ) {
        $fields['updatedAt'] = time();
        $re = $wpdb->update($in['table'], $fields, ['user_ID' => $user_ID]);
        if ( $re === false ) return sql_error(ERROR_UPDATE);
        else $action = ['action' => 'UPDATE'];
    } else {
        $fields['createdAt'] = time();
        $fields['updatedAt'] = time();
        $re = $wpdb->insert($in['table'], $fields);
        if ( $re === false ) return sql_error(ERROR_INSERT);
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
function table_get($in) {
    $user_ID = wp_get_current_user()->ID;
    global $wpdb;
    return $wpdb->get_row("SELECT * FROM $in[table] WHERE user_ID=$user_ID", ARRAY_A);
}


/**
 * @TODO gallery category 에서 나의 글 중, featured image url 을 가져오는데, 범용적이지 못하다. 훅으로 처리를 한다.
 * @param $user_ID
 * @return mixed|string
 */
function get_gallery_featured_image_url($user_ID) {
    $posts = get_posts(['category_name' => 'gallery', 'author' => $user_ID]);
    if ( $posts && count($posts) > 0 ) {
        $post = $posts[0];
        $post_thumbnail_id = get_post_thumbnail_id($post);
        if ($post_thumbnail_id) {
            return wp_get_attachment_image_url($post_thumbnail_id, 'full');
        }
    }
    return '';
}


/**
 * Returns requested url domain
 * @return mixed
 */
function get_host_name() {
    if ( isset($_SERVER['HTTP_HOST']) ) return $_SERVER['HTTP_HOST'];
    else return null;
}

function isCli() {
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
function get_requested_host_url() {

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    $url.= get_host_name();

    return $url;
}
/**
 * Returns get current URL that appears on web browser URL address bar.
 * @return string
 */
function get_current_url() {
    // Append the requested resource location to the URL
    return get_requested_host_url() . $_SERVER['REQUEST_URI'];
}


function sql_injection_test($sql) {
    if ( strpos($sql, ';') !== false ) return ERROR_SQL_INJECTION . ':;';
    if ( stripos($sql, 'INSERT ') !== false ) return ERROR_SQL_INJECTION . ':INSERT';
    if ( stripos($sql, 'REPLACE ') !== false ) return ERROR_SQL_INJECTION . ':REPLACE';
    if ( stripos($sql, 'UPDATE ') !== false ) return ERROR_SQL_INJECTION . ':UPDATE';
    if ( stripos($sql, 'DELETE ') !== false ) return ERROR_SQL_INJECTION . ':DELETE';
    if ( stripos($sql, 'SELECT ') !== false ) return ERROR_SQL_INJECTION . ':SELECT';
    if ( stripos($sql, 'WHERE ') !== false ) return ERROR_SQL_INJECTION . ':WHERE';
    if ( stripos($sql, 'FROM ') !== false ) return ERROR_SQL_INJECTION . ':FROM';
    if ( stripos($sql, 'CREATE ') !== false ) return ERROR_SQL_INJECTION . ':CREATE';
    if ( stripos($sql, 'DROP ') !== false ) return ERROR_SQL_INJECTION . ':DROP';
    if ( stripos($sql, 'JOIN ') !== false ) return ERROR_SQL_INJECTION . ':JOIN';
    if ( stripos($sql, ' TABLE ') !== false ) return ERROR_SQL_INJECTION . ':TABLE';
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
function sql_query($in) {

    global $wpdb;
    $table = $in['table'];
    $where = stripslashes($in['where']);

    if ( !in_array($table, PUBLIC_TABLES) ) return ERROR_PUBLIC_TABLES;

    $re = sql_injection_test($table);
    if ( $re ) return $re;
    $re = sql_injection_test($where);
    if ( $re ) return $re;

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
function sql_error($default_error = null) {
    global $wpdb;
    $last_error = $wpdb->last_error;
    if ( $last_error ) {
        if ( strpos($last_error, 'Unknown column') !== false ) {
            return ERROR_UNKNOWN_COLUMN;
        }
    }
    return $default_error;
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
function ids($users, $field='user_ID')
{
    $ret = [];
    foreach ($users as $u) {
        $ret[] = $u[$field];
    }
    return $ret;
}


function between($val, $min, $max) {
    return $val >= $min && $val <= $max;
}




function forum_search($in) {
    if (!$in['category_name']) return ERROR_EMPTY_CATEGORY;
    $posts = get_posts($in);
    $rets = [];
    foreach ($posts as $p) {
        $rets[] = post_response($p);
    }
    return $rets;
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
function get_post_from_guid( $guid ) {
    global $wpdb;
    $id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
    if ( $id ) return get_post($id);
    if ( stripos( $guid, 'http://') !== false ) {
        $guid = str_replace('http://', 'https://', $guid);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
    }
    if ( stripos( $guid, 'https://') !== false ) {
        $guid = str_replace('https://', 'http://', $guid);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid ) );
    }
    if ( $id ) return get_post( $id );
    return null;
}

/**
 * Returns an array of the names and slugs of categories of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_categories_of_post($post_ID) {
    $post_categories = wp_get_post_categories( $post_ID, ['fields' => 'all'] );
    $cats = [];

    foreach($post_categories as $c){
        $cat = get_category( $c );
        $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
    }
    return $cats;
}

/**
 * Returns an array of slugs of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_slugs_of_post($post_ID) {
    $post_categories = wp_get_post_categories( $post_ID, ['fields' => 'all'] );
    $cats = [];

    foreach($post_categories as $c){
        $cat = get_category( $c );
        $cats[] = $cat->slug;
    }
    return $cats;
}
/**
 * Returns an array of category IDs of the $post_ID
 * @param $post_ID
 * @return array
 */
function get_category_IDs_of_post($post_ID) {
    return wp_get_post_categories( $post_ID );
}

/**
 * @param $in
 *   - when $in['ID'] is set, post_title, post_content, category will be preserved even if they are not set.
 * @return array|mixed|string
 *
 *
 */
function api_edit_post($in) {

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
        $body = $in['post_content'];
        $post = get_post($ID, ARRAY_A);
        $slug = get_first_slug($post['post_category']);
        sendMessageToTopic(NOTIFY_POST . $slug, $title, $body, '', $post['guid'], $data = ['sender' => wp_get_current_user()->ID]);
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
function api_get_translations($in) {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " ORDER BY code ASC", ARRAY_A);
    
    $rets = [];
    // This is 'language-first' format for GetX translation.
    if ( isset($in['format']) && $in['format'] === 'language-first' ) {
        foreach($rows as $row) {
            if ( !isset($rets[$row['language']]) ) $rets[$row['language']] = [];
            $rets[$row['language']][$row['code']] = $row['value'];
        }
    } else {
        foreach($rows as $row) {
            if ( !isset($rets[$row['code']]) ) $rets[$row['code']] = [
                'code' => $row['code'],
            ];
            $rets[$row['code']][$row['language']] = $row['value'];
        }
    }

    return ['languages' => get_option(LANGUAGES), 'translations' => $rets];
}

function get_translation_by_code($code)
{
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " WHERE code='$code'", ARRAY_A);
}

function api_add_translation_language($in) {
    if (admin() === false) return ERROR_PERMISSION_DENIED;
    if (!isset($in['language'])) return ERROR_EMPTY_LANGUAGE;
    $languages = get_option(LANGUAGES, []);
    if (in_array($in['language'], $languages)) return ERROR_LANGUAGE_EXISTS;
    $languages[] = $in['language'];
    update_option(LANGUAGES, $languages, false);
    return $languages;
}

function api_edit_translation($in) {
    if ( admin() === false ) return ERROR_PERMISSION_DENIED;
    if (!isset($in['code'])) return ERROR_EMPTY_CODE;


    $data = $in;

    unset($data['route'], $data['session_id'], $data['code']);

    global $wpdb;
    foreach( $data as $ln => $val ) {
        $re = $wpdb->replace(TRANSLATIONS_TABLE, ['code' => $in['code'], 'language' => $ln, 'value' => $val ]);
        if ( $re === false ) return sql_error(ERROR_LANGUAGE_REPLACE);
    }
    api_notify_translation_update();
    return $data;
}

function api_change_translation_code ($in) {
    if ( admin() === false ) return ERROR_PERMISSION_DENIED;
    if (!isset($in['oldCode'])) return ERROR_EMPTY_OLD_CODE;
    if (!isset($in['newCode'])) return ERROR_EMPTY_NEW_CODE;
    global $wpdb;
    $re = $wpdb->update(TRANSLATIONS_TABLE, ['code' => $in['newCode'] ], ['code' => $in['oldCode']]);
    if ( $re === false ) return sql_error(ERROR_CHANGE_CODE);
    api_notify_translation_update();
    return $in;
}

function api_delete_translation($in) {
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
 * Update the 'notification/translation' document in Firebase RealTime Database.
 * Clients may listen the document change and update the translations.
 * @throws \Kreait\Firebase\Exception\DatabaseException
 */
function api_notify_translation_update() {
    $db = getDatabase();
    $reference = $db->getReference('notifications/translation');
    $stamp = time();
    $reference->set(['updatedAt' => $stamp]);
}


/**
 * Get domain theme name
 * @return string
 */
function getDomainTheme() {
    if ( API_CALL ) return null;
    global $domain_themes;
    if ( !isset($domain_themes) ) return null;
    $_host = get_host_name();
    $theme = 'default';
    foreach ($domain_themes as $_domain => $_theme) {
        if (stripos($_host, $_domain) !== false) {
            $theme = $_theme;
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
function d($obj) {
    echo "<pre>";
	$str = print_r($obj, true);
	$str = str_replace("<", "&lt;", $str);
	echo $str;
    echo "</pre>";
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
function getRoute($params) {
    $url = API_URL . "?" . http_build_query($params);
//    echo "url: $url\n";
    $re = file_get_contents($url);
    $json = json_decode($re, true);
    if ( !$json ) {
        print_r($re);
    }
    return $json;
}


/**
 * Returns true if the web is running on localhost (or developers computer).
 * @return bool
 */
function is_localhost() {
    if ( isCli() ) return false;
    $localhost = false;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || PHP_OS === 'Darwin') $localhost = true;
    else {
        $ip = $_SERVER['SERVER_ADDR'];
        if ( strpos($ip, '127.0.') !== false) $localhost = true;
        else if ( strpos($ip, '192.168.') !== false) $localhost = true;
    }
    return $localhost;
}


function onCommentCreateSendNotification($in) {
    /**
     * 1) check if post owner want to receive message from his post
     * 2) get notification comment ancestors
     * 3) make it unique
     * 4) get topic subscriber
     * 5) remove all subscriber from token users
     * 6) get users token
     * 7) send batch 500 is maximum
     */

    /**
     *  get all the user id of post and comment ancestors. - name as 'token users'
     *  get all the user id of topic subscribers. - named as 'topic subscribers'.
     *  remove users of 'topic subscribers' from 'token users'. - with array_diff($array1, $array2) return the array1 that has no match from array2
     *
     */

    // 1 post owner
    $post = get_post( $in['comment_post_ID'], ARRAY_A );
    $token_users = [];
    if ( !is_my_post( $post['post_author']) ) {
        $notifyPostOwner = get_user_meta( $post['post_author'], NOTIFY_POST, true );
        if ( $notifyPostOwner === 'Y' )  $token_users[] = $post['post_author'];   // check if this is needed since it will send via topic
    }

    //2 ancestors
    $comment = get_comment( $comment_id );
    if ( $comment->comment_parent ) {
        $token_users = array_merge($token_users, getAncestors($comment->comment_ID));
    }

    // 3 unique
    $token_users = array_unique( $token_users );

    // 4 get topic subscriber
    $slug = get_first_slug($post['post_category']);
    $topic_subscribers = getForumSubscribers( $slug, NOTIFY_COMMENT);

    // 5 remove all subscriber to token users
    $token_users = array_diff($token_users, $topic_subscribers);


    // 6 token
    $tokens = getTokensFromUserIDs($token_users, NOTIFY_COMMENT);

    //7 send notification to tokens and topic
    $title              = $post['post_title'];
    $body               = $in['comment_content'];


    sendMessageToTopic(NOTIFY_COMMENT . $slug, $title, $body, '', $post['guid'], $data = ['sender' => wp_get_current_user()->ID]);
    sendMessageToTokens( $tokens, $title, $body,  '', $post['guid'], $data = json_encode(['sender' => wp_get_current_user()->ID]));
}

function get_ancestor_tokens_for_push_notifications($comment_ID) {
    $asc = $this->getAncestors($comment_ID);
    return $this->getTokensFromUserIDs($asc);
}


/**
 * @param array $ids
 * @param null $filter 'notifyComment' || 'notifyPost'
 * @return array
 */
function getTokensFromUserIDs($ids = [], $filter = null) {
    $tokens = [];
    foreach( $ids as $user_id ) {
        $rows = $this->getUserTokens($user_id);
        if ($filter) {
            if ( get_user_meta($user_id, $filter, true) == 'Y' ) {
                foreach( $rows as $token ) {
                    $tokens[] = $token;
                }
            }
        } else {
            foreach( $rows as $token ) {
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
function getAncestors( $comment_ID ) {

    $comment = get_comment( $comment_ID );
    $asc     = [];

    while ( true ) {
        $comment = get_comment( $comment->comment_parent );
        if ( $comment ) {
            if ( $comment->user_id == login( 'ID' ) ) {
                continue;
            }
            $asc[] = $comment->user_id;
        } else {
            break;
        }
    }

    $asc = array_unique( $asc );

    return $asc;

}

function getUserTokens($user_ID) {
    global $wpdb;
    $rows =  $wpdb->get_results("SELECT token FROM " . PUSH_TOKENS ." WHERE user_ID=$user_ID", ARRAY_A);
    $tokens = [];
    foreach( $rows as $user ) {
        $tokens[] = $user['token'];
    }
    return $tokens;
}


/**
 * @param string $slug
 * @param null $mode 'post' | 'comment'
 * @return array
 */
function getForumSubscribers($slug = '', $mode = null ) {
    $topic = $mode ? "meta_key='{$mode}{$slug}'" : "meta_key LIKE 'notify%{$slug}'";
    global $wpdb;
    $rows = $wpdb->get_results("SELECT user_id FROM wp_usermeta WHERE $topic AND meta_value='Y' ", ARRAY_A);
    $ids = [];
    foreach( $rows as $user ) {
        $ids[] = $user['user_id'];
    }
    return $ids;
}

function getUserForumTopics($user_ID) {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT meta_key FROM wp_usermeta WHERE meta_key LIKE 'notify%' AND meta_value='Y' AND user_id=$user_ID ", ARRAY_A);
    $topics = [];
    foreach( $rows as $user ) {
        $topics[] = $user['meta_key'];
    }
    return $topics;
}

/**
 *
 * @param $in
 *  - $in['cat_ID'] is the category term id
 *  - $in['name'] is the category property to update.
 *      - 'cat_name' and 'category_description' are predefined for category name(or title) and description.
 *      - And any name & value can be saved as property and value
 *  - $in['value'] is the value.
 *
 * @return mixed
 *  - error code on error.
 *  - Array of WP_Term Object of the category term object. @see https://developer.wordpress.org/reference/classes/wp_term/
 */
function update_category($in) {

    if (!isset($in['cat_ID'])) return ERROR_EMPTY_CATEGORY_ID;
    $cat = get_category($in['cat_ID']);
    if ( $cat == null ) return ERROR_CATEGORY_NOT_EXIST_BY_THAT_ID;

    if (!isset($in['name'])) return ERROR_EMPTY_NAME;
    if (!isset($in['value'])) return ERROR_EMPTY_VALUE;


    if ( in_array($in['name'], ['cat_name', 'category_description']) ) {
        $re = wp_update_category(['cat_ID' => $in['cat_ID'], $in['name'] => $in['value']], true);
        if ( is_wp_error($re) ) {
            return $re->get_error_message();
        }
    } else {
        $re = update_term_meta($in['cat_ID'], $in['name'], $in['value']);
        if ( is_wp_error($re) ) {
            return $re->get_error_message();
        }
    }

    $ret = get_category($in['cat_ID'])->to_array();

    $metas = get_term_meta($in['cat_ID'], '', true);
    foreach($metas as $key => $values) {
        $ret[$key] = $values[0];
    }

    return $ret;
}


/**
 * Returns the slug of first category of the post categories
 * @param $categories
 *
 * @return string
 */
function get_first_slug($categories) {
    // get post slug as category name and pass
    if (count($categories)) {
        $cat = get_category($categories[0]);
        return $cat->slug;
    } else {
        return '';
    }
}