<?php


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
        exit;
    }

    /// Force to hide error message on API CALL
    $wpdb->show_errors = false;
}
