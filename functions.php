<?php
/**
 * @file functions.php
 */




/**
 * Directory(Folder) Constants.
 */
define( 'THEME_DIR', __DIR__ );
define( 'API_DIR', THEME_DIR . '/api' );

/**
 * Load API functions
 */
require_once(API_DIR .'/lib/functions.php');


/**
 * Other constants
 */
define( 'REQUESTED_HOME_URL', get_requested_host_url());
define( 'THEME_URL', REQUESTED_HOME_URL . '/wp-content/themes/wigo');

define('API_CALL', in('route') != null );




/**
 * Preflight for Client
 *
 * CORS
 */
if ( API_CALL ) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        echo ''; // No return data for preflight.
        return;
    }
}

/**
 * Load definitions and configurations
 */
require_once(THEME_DIR .'/defines.php');
require_once(THEME_DIR .'/config.php');


/**
 * API_URL.
 *
 * Must be called after config.php
 */
if ( isCli() ) {
    define( 'API_URL', API_URL_ON_CLI);
}
else {
    define( 'API_URL', THEME_URL . '/api/index.php');
}

/**
 * Choose theme for domain
 *
 * Must be called after configuration.
 */
define('DOMAIN_THEME', getDomainTheme());
define('DOMAIN_THEME_URL', THEME_URL . '/themes/' . DOMAIN_THEME );


/**
 * Composer auto load (PSR)
 */
require API_DIR . '/vendor/autoload.php';

/**
 * Firebase routines
 */
require_once(API_DIR . '/lib/firebase.php');







/**
 * Filter 404 response code to 200.
 * @return bool
 */
function wpd_do_stuff_on_404(){
    if( is_404() ){
        global $wp_query;
        status_header( 200 );
        $wp_query->is_404=false;
        return false;
    }
}
add_action( 'template_redirect', 'wpd_do_stuff_on_404' );

function remove_redirect_guess_404_permalink( $redirect_url ) {
    if ( is_404() )
        return false;
    return $redirect_url;
}
add_filter( 'redirect_canonical', 'remove_redirect_guess_404_permalink' );

function live_reload_js() {
    /// TODO print this only for localhost(local dev)
    echo <<<EOH
   <script src="https://local.sonub.com:12345/socket.io/socket.io.js"></script>
   <script>
       var socket = io('https://local.sonub.com:12345');
       socket.on('reload', function (data) {
           console.log(data);
           // window.location.reload(true);
           location.reload();
       });
   </script>
EOH;
}


function load_theme_js($script) {
    $path = str_replace(".php", ".js", $script);
    if ( file_exists($path) ) {
        $path = str_replace(THEME_DIR, THEME_URL, $path);
        echo <<<EOJ
<script src="$path"></script>

EOJ;
    }
}
function load_theme_css($script) {
    $path = str_replace(".php", ".css", $script);
    if ( file_exists($path) ) {
        $path = str_replace(THEME_DIR, THEME_URL, $path);
        echo <<<EOJ
<link rel="stylesheet" href="$path">

EOJ;
    }
}

function extract_styles_from_script($script) {

}