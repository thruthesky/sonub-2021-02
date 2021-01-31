<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');


wp_set_current_user(1);
echo get_session_id() . "\n";
