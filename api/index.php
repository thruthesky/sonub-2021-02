<?php
/**
 * @file index.php
 * @description theme folder
 */


require_once('../../../../wp-load.php');

//add_filter('comment_flood_filter', '__return_false');
//add_filter('duplicate_comment_id', '__return_false');
//add_filter('allow_empty_comment', '__return_true')


debug_log('----- api begin', in());


list($instance, $methodName, $route) = end_if_error(get_route(in()));


/// Login with session_id
$session_id = in('session_id');
// If session_id is not set(or empty) and the route is for public then, authentication is not necessary.
if ( isset($session_id) && $session_id ) {
    $re = authenticate($session_id);
    if ( api_error($re) ) error($re);
}

$response = $instance->$methodName(in());
//if ( in('route') == 'app.query') {
    debug_log("api response ---->", $response);
//}
success_or_error($response);



