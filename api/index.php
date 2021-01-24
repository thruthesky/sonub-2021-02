<?php
/**
 * @file index.php
 * @description theme folder
 */
if ( ! defined( 'API_DIR' ) ) {
    define( 'API_DIR', dirname( __FILE__ ) );
}
require_once(API_DIR . '/api-load.php');


debug_log('----- theme begin', in());


list($instance, $methodName, $route) = end_if_error(get_route(in()));


$session_id = in('session_id');
// If session_id is not set(or empty) and the route is for public then, authentication is not necessary.
if ( (!isset($session_id) || empty($session_id)) && in_array($route, PUBLIC_ROUTES) ) {} else {
    $re = authenticate();
    if ( api_error($re) ) error($re);
}


$response = $instance->$methodName(in());
//if ( in('route') == 'app.query') {
    debug_log("response ---->", $response);
//}
success_or_error($response);



