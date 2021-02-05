<?php

/**
 * @file functions.php
 */


/**
 * Directory(Folder) Constants.
 */
//define( 'THEME_DIR', __DIR__ );   /// WINDOWS \wp-content\themes\withcenter-backend-v3
define('THEME_DIR', get_template_directory());  /// /wp-content/themes/withcenter-backend-v3

/**
 * THEME_FOLDER_NAME is the folder name of the theme.
 *
 * @example 'withcenter-backend-v3', 'wigo'
 */
$_paths = explode('/', THEME_DIR);
define('THEME_FOLDER_NAME', array_pop($_paths));
define('API_DIR', THEME_DIR . '/api');

/**
 * Load API functions
 */
require_once(API_DIR . '/lib/api-functions.php');


/**
 * Other constants
 */
define('REQUESTED_HOME_URL', get_requested_host_url());
define('THEME_URL', REQUESTED_HOME_URL . '/wp-content/themes/' . THEME_FOLDER_NAME);

define('API_CALL', in('route') != null);

require_once(THEME_DIR . '/kill-wrong-routes.php');
require_once(THEME_DIR . '/pre-flight.php');

/**
 * Load definitions and configurations
 */
require_once(THEME_DIR . '/defines.php');


require_once(THEME_DIR . '/config.php');


/**
 * Login with session_id.
 *
 */
if (isset($_COOKIE['session_id']) && $_COOKIE['session_id']) {
    authenticate($_COOKIE['session_id']);
}




/**
 * API_URL.
 *
 * Must be called after config.php
 */
if (isCli()) {
    define('API_URL', API_URL_ON_CLI);
} else {
    define('API_URL', THEME_URL . '/api/index.php');
}

/**
 * Choose theme for domain
 *
 * Must be called after configuration.
 */
define('DOMAIN_THEME', get_domain_theme());
define('DOMAIN_THEME_URL', THEME_URL . '/themes/' . DOMAIN_THEME);


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
function wpd_do_stuff_on_404()
{
    if (is_404()) {
        global $wp_query;
        status_header(200);
        $wp_query->is_404 = false;
        return false;
    }
}
add_action('template_redirect', 'wpd_do_stuff_on_404');

function remove_redirect_guess_404_permalink($redirect_url)
{
    if (is_404())
        return false;
    return $redirect_url;
}
add_filter('redirect_canonical', 'remove_redirect_guess_404_permalink');

function live_reload_js()
{
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


function load_theme_js($script)
{
    $path = str_replace(".php", ".js", $script);
    if (file_exists($path)) {
        $path = str_replace(THEME_DIR, THEME_URL, $path);
        echo <<<EOJ
<script src="$path"></script>

EOJ;
    }
}
function load_theme_css($script)
{
    $path = str_replace(".php", ".css", $script);
    if (file_exists($path)) {
        $path = str_replace(THEME_DIR, THEME_URL, $path);
        echo <<<EOJ
<link rel="stylesheet" href="$path">

EOJ;
    }
}


/**
 * Returns theme script file path from the input $page
 *
 * @note if the user is in 'admin' dashboard page, then 'admin' theme is used and there is no default script for admin page script.
 *
 * @param $theme
 * @param $page
 * @return string
 *  - an example of return string: /Users/thruthesky/www/wordpress/wp-content/themes/sonub/themes/forum/view.php
 */
function get_theme_page_path($theme, $page)
{

    if ( is_admin_page() ) {
        $page = str_replace("admin/", "", $page);
        return THEME_DIR . "/themes/admin/$page.php";
    }

    $script = THEME_DIR . "/themes/$theme/$page.php";


    if (!file_exists($script)) {
        $script = THEME_DIR . "/themes/default/$page.php";
    }
    if (!file_exists($script)) {
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
function get_theme_page_file_name()
{
    if (isset($_REQUEST['page'])) {
        $page = $_REQUEST['page'];
    } else {
        $uri = $_SERVER['REQUEST_URI'];
        if (empty($uri) || $uri == '/') $page = 'home';
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
function get_theme_page_class_name()
{
    return str_replace('/', '-', get_theme_page_file_name());
}

/**
 * Returns theme script file path from the http request input
 * @return string
 *  - see get_theme_page_path()
 */
function get_theme_page_script_path()
{
    return get_theme_page_path(DOMAIN_THEME, get_theme_page_file_name());
}
function get_theme_header_path()
{
    return get_theme_page_path(DOMAIN_THEME, 'header');
}
function get_theme_footer_path()
{
    return get_theme_page_path(DOMAIN_THEME, 'footer');
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
function get_error_script($title, $description)
{
    global $_error_title, $_error_description;
    $_error_title = $title;
    $_error_description = $description;
    return THEME_DIR . "/themes/default/error.php";
}
function get_error_title()
{
    global $_error_title;
    return $_error_title;
}
function get_error_description()
{
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
function insert_extracted_styles_from_script()
{
    global $_extracted_styles_from_script;
    echo $_extracted_styles_from_script;
}
function begin_capture_script_style()
{
    ob_start();
}
function end_capture_script_style()
{
    global $_extracted_styles_from_script;
    $_extracted_styles_from_script = null;
    $content = ob_get_clean();
    $re = preg_match_all("/\<style\>[^(\<)]*\<\/style\>/s", $content, $m);
    if ($re) {
        $styles = $m[0];
        foreach ($styles as $style) {
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
function build_version()
{
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
function insert_initial_javascript()
{
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
function is_forum_page()
{
    $page = get_theme_page_file_name();
    return strpos($page, 'forum') !== false;
}



/// Widget System ------------------------------------------------------------------------------------------------------
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
/// Global widget option variable
$__widget_options = null;
function set_widget_options( $options ) {
    global $__widget_options;
    $__widget_options = $options;
}

function get_widget_options()
{
    global $__widget_options;
    return $__widget_options;
}


/**
 * @param string $path is the 'widget_type/folder_name' to load the widget.
 * @param $options array
 *
 * @return string - PHP script path for widget loading
 *
 * @code
 *   include widget('social-login'); // will load 'widgets/social-login/social-login.php'
 * @endcode
 */
function widget( string $path, array $options = [] ) {
    set_widget_options( $options );
    $arr = explode('/', $path);
    return THEME_DIR . "/widgets/$arr[0]/$arr[1]/$arr[1].php";
}

/// EO Widget System ---------------------------------------------------------------------------------------------------


/**
 * Returns cookie domain
 * @return mixed|string|null
 */
function get_cookie_domain()
{
    if (defined('BROWSER_COOKIE_DOMAIN') && BROWSER_COOKIE_DOMAIN) return BROWSER_COOKIE_DOMAIN;
    else return get_domain();
}


function jsAlert($msg)
{
    echo "
    <script>
        alert('$msg');
    </script>
    ";
    return 0;
}
function jsGo($url)
{
    echo "
    <script>
        location.href='$url';
    </script>
    ";
    return 0;
}

function jsBack($msg) {
    echo "
    <script>
        alert('$msg');
        history.go(-1);
    </script>
    ";
    exit;
}



function get_files($in)
{
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

/**
 * Returns true if the user is in admin page.
 * @return bool
 */
function is_admin_page() {
    return strpos(in('page'), 'admin') === 0;
}


/**
 * Display widget selection box on admin site(form)
 *
 *
 *
 * @param $cat_ID
 * @param $folder_name
 * @param $config_name
 */
function select_list_widgets($cat_ID, $type, $config_name) {



    $default_selected = category_meta($cat_ID, $config_name, $type . '-default');


    echo "<select name='$config_name'>";
    foreach( glob(THEME_DIR . "/widgets/$type/**/*.ini") as $file ) {
        $arr = explode('/', $file);
        array_pop($arr);
        $widget_name = array_pop($arr);
        $ini_file = str_replace(".php", ".ini", $file);

            $re = parse_ini_file($ini_file);
            $description = $re['description'];


            $value = "$type/$widget_name";
        if ( $default_selected == $value ) $selected = "selected";
        else $selected = "";

        echo "<option value='$value' $selected>$description</option>";
    }
    echo "</select>";


}

/**
 * Set login cookies
 *
 * When user login, the session_id must be saved in cookie. And it is shared with Javascript.
 * @param $profile
 */
function set_login_cookies($profile) {
    setcookie ( 'session_id' , $profile['session_id'] , time() + 365 * 24 * 60 * 60 , '/' , BROWSER_COOKIE_DOMAIN);
    if ( isset($profile['nickname']) ) setcookie ( 'nickname' , $profile['nickname'] , time() + 365 * 24 * 60 * 60 , '/' , BROWSER_COOKIE_DOMAIN);
    if ( isset($profile['profile_photo_url']) ) setcookie ( 'profile_photo_url' , $profile['profile_photo_url'] , time() + 365 * 24 * 60 * 60 , '/' , BROWSER_COOKIE_DOMAIN);
}



class App {
    static function page(string $folder_and_name): bool {
        return strpos(in('page'), $folder_and_name) !== false;
    }
}

