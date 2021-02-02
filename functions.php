<?php
/**
 * @file functions.php
 */


/**
 * Directory(Folder) Constants.
 */
//define( 'THEME_DIR', __DIR__ );   /// WINDOWS \wp-content\themes\withcenter-backend-v3
define( 'THEME_DIR', get_template_directory() );  /// /wp-content/themes/withcenter-backend-v3

/**
 * THEME_FOLDER_NAME is the folder name of the theme.
 *
 * @example 'withcenter-backend-v3', 'wigo'
 */
$_paths = explode('/', THEME_DIR);
define( 'THEME_FOLDER_NAME', array_pop( $_paths ));
define( 'API_DIR', THEME_DIR . '/api' );

/**
 * Load API functions
 */
require_once(API_DIR .'/lib/api-functions.php');


/**
 * Other constants
 */
define( 'REQUESTED_HOME_URL', get_requested_host_url());
define( 'THEME_URL', REQUESTED_HOME_URL . '/wp-content/themes/' . THEME_FOLDER_NAME);

define('API_CALL', in('route') != null );

require_once(THEME_DIR . '/kill-wrong-routes.php');
require_once(THEME_DIR . '/pre-flight.php');

/**
 * Load definitions and configurations
 */
require_once(THEME_DIR .'/defines.php');
require_once(THEME_DIR .'/config.php');


/**
 * Login with session_id.
 *
 */
if ( isset($_COOKIE['session_id']) && $_COOKIE['session_id'] ) {
    authenticate($_COOKIE['session_id']);
}




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


/**
 * Returns theme script file path from the input $page
 * @param $theme
 * @param $page
 * @return string
 *  - an example of return string: /Users/thruthesky/www/wordpress/wp-content/themes/sonub/themes/forum/view.php
 */
function get_theme_page_path($theme, $page) {
	$script = THEME_DIR . "/themes/$theme/$page.php";

	if ( !file_exists($script) ) {
		$script = THEME_DIR . "/themes/default/$page.php";
	}
	if ( !file_exists($script) ) {
		$script = get_error_script('File not found', "file: $script<br>" . 'The file you are referring does not exists on server');
	}
	return $script;
}

/**
 * Returns the theme script filename from the http request.
 *
 * Example of returns would be
 *  - 'home' for home page or if there is no information from http request.
 *  - 'user/register' for user register page.
 *  - 'user/profile' for user profile page
 *  - 'user/login' for user login page
 *  - 'forum/view'
 *  - 'forum/list'
 */
function get_theme_page_file_name() {
    if ( isset($_REQUEST['page']) ) {
        $page = $_REQUEST['page'];
    } else {
        $uri = $_SERVER['REQUEST_URI'];
        if ( empty($uri) || $uri == '/' ) $page = 'home';
        else $page = 'forum/view';
    }
    return $page;
}

/**
 * Returns a string to be used as HTML 'class' attribute name.
 * @usage Use this as class name for the theme page.
 *
 * @return mixed|string|string[]
 */
function get_theme_page_class_name() {
    return str_replace('/', '-', get_theme_page_file_name());
}

/**
 * Returns theme script file path from the http request input
 * @return string
 *  - see get_theme_page_path()
 */
function get_theme_page_script_path() {
	return get_theme_page_path(DOMAIN_THEME, get_theme_page_file_name());
}

/**
 * Error script with title and description
 * @param $title
 * @param $description
 *
 * @return string
 */
global $_error_title;
global $_error_description;
function get_error_script($title, $description) {
	global $_error_title, $_error_description;
	$_error_title = $title;
	$_error_description = $description;
	return THEME_DIR . "/themes/default/error.php";
}
function get_error_title() {
	global $_error_title;
	return $_error_title;
}
function get_error_description() {
	global $_error_description;
	return $_error_description;
}


/**
 * Extract Style Tags since Vue.js does not support style tags inside template.
 *
 * Vue.js 에서 template 에 <style>...</style> 태그를 지원하지 않으므로, script 파일 내에 기록된 style 태그를
 * 추출해서 따로 모은 다음, 필요한 곳에 출력한다.
 */
global $_extracted_styles_from_script;
function insert_extracted_styles_from_script() {
	global $_extracted_styles_from_script;
	echo $_extracted_styles_from_script;
}
function begin_capture_script_style() {
	ob_start();
}
function end_capture_script_style() {
	global $_extracted_styles_from_script;
	$_extracted_styles_from_script = null;
	$content = ob_get_clean();
	$re = preg_match_all("/\<style\>[^(\<)]*\<\/style\>/s", $content, $m);
	if ( $re ) {
		$styles = $m[0];
		foreach($styles as $style) {
			$content = str_replace($style, '', $content);
		}
		$_extracted_styles_from_script = implode("\n", $styles);
	}
	echo $content;
}


/**
 * Build version
 *
 *
 */
function build_version() {
    if (is_localhost()) return time();
    else return api_version();
}

/**
 * This will insert some javascript inside <HEAD> tags.
 *
 * Put javascript codes that are mandatory for the app.
 *
 * @todo always minify the javascript code.
 */
function insert_initial_javascript() {
    $js = <<<EOJ
<script>
const _components = {};
function addComponent(name, obj) {
    _components[name] = obj;
}
function getComponents() {
    return _components;
}
function later(fn) {
    window.addEventListener('load', fn);
}
</script>
EOJ;

    echo $js;
//    echo '<script>const _components={};function addComponent(n,o){_components[n]=o}function getComponents(){return _components}</script>';

}


/**
 * Returns true if the theme page is in forum related pages like 'forum/list', 'forum/view', or any other pages in forum.
 */
function is_forum_page() {
    $page = get_theme_page_file_name();
    return strpos($page, 'forum') !== false;
}



/**
 * Includes a widget script
 *
 * If a widget script exists under `cms/pages/[domain]/widgets` folder, then it will load this first.
 * Or it will look for the widget script under `cms/widgets` folder.
 *
 * @param $name
 *
 * @return string
 */
$__widget_options = null;

function set_widget_options( $options ) {
    global $__widget_options;
    $__widget_options = $options;
}

function get_widget_options() {
    global $__widget_options;

    return $__widget_options;
}


/**
 * @param $name
 * @param null $options
 *
 * @return string - PHP script path for widget loading
 *
 * @code
 *  <?php include widget('social-login/icons/index') ?>
 *  <?php include widget('social-login.icons') ?>
 *  <?php include widget('social-login') ?>
 * @endcode
 */
function widget( $name, $options = null ) {

    set_widget_options( $options );

    $domain = 'default';

    if ( strpos( $name, '/' ) !== false ) {
        $rel_path = "/widgets/$name.php";
    } else if ( strpos( $name, '.' ) !== false ) {
        $arr      = explode( '.', $name );
        $rel_path = "/widgets/$arr[0]/$arr[1].php";
    } else {
        $rel_path = "/widgets/$name/$name.php";
    }
    $p = THEME_DIR . "/theme/$domain$rel_path";
    if ( file_exists( $p ) ) {
        $widget_path = $p;
    } else {
        $widget_path = THEME_DIR . $rel_path;
    }


    global $__included_files;
    $__included_files[] = $widget_path;

    return $widget_path;
}


/**
 * Returns cookie domain
 * @return mixed|string|null
 */
function get_cookie_domain() {
    if ( defined('BROWSER_COOKIE_DOMAIN') && BROWSER_COOKIE_DOMAIN ) return BROWSER_COOKIE_DOMAIN;
    else return get_domain();
}


function jsAlert($msg) {
    echo "
    <script>
        alert('$msg');
    </script>
    ";
}


function get_files($in) {
    $q = [
        'numberposts' => -1,
        'post_type' => 'attachment',
    ];

    if (isset($in['category_name'])) {
        $q['category_name'] = $in['category_name'];
    }

    $posts = get_posts($q);
    $rets = [];
    foreach ($posts as $p) {
        $rets[] = post_response($p);
    }
    return $rets;
}