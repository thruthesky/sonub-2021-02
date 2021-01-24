<?php

require_once(API_DIR . '/../../../../wp-load.php');

require_once(API_DIR .'/config.php');

require_once(API_DIR . '/lib/functions.php');
require_once(API_DIR .'/defines.php');

// TODO: Make this not required on Production mode.
require_once(API_DIR . '/lib/test.helper.php');

require __DIR__.'/vendor/autoload.php';

require_once(API_DIR . '/lib/firebase.php');



/**
 * Preflight for CORS
 *
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
