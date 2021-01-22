<?php


define( 'THEME_DIR', __DIR__ );
define( 'THEME_URL', '/wp-content/themes/wigo');


if ( ! defined( 'V3_DIR' ) ) {
    define( 'V3_DIR', ABSPATH . 'v3' );
}

require_once(V3_DIR . '/api-load.php');

include_once THEME_DIR . '/config.php';

