<?php
if ( ! defined( 'V3_DIR' ) ) {
    define( 'V3_DIR', dirname( __FILE__ ) );
}
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


date_default_timezone_set('Asia/Seoul');

debug_log('----- v3 begin', in());


list($instance, $methodName, $route) = end_if_error(get_route(in()));


$session_id = in('session_id');
// If session_id is not set(or empty) and the route is for public then, authentication is not necessary.
if ( (!isset($session_id) || empty($session_id)) && in_array($route, PUBLIC_ROUTES) ) {} else {
    $re = authenticate();
    if ( _is_error($re) ) error($re);
}


$response = $instance->$methodName(in());
//if ( in('route') == 'app.query') {
    debug_log("response ---->", $response);
//}
success_or_error($response);



