<?php


define( 'THEME_DIR', __DIR__ );
define( 'API_DIR', THEME_DIR . '/api' );

require_once(API_DIR . '/lib/functions.php');


define( 'THEME_URL', get_request_home_url() . '/wp-content/themes/wigo');
define( 'API_URL', THEME_URL . '/api/index.php');



require_once(API_DIR . '/api-load.php');
include_once THEME_DIR . '/config.php';

