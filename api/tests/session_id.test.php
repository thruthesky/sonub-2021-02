<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


wp_set_current_user(1);
echo get_session_id() . "\n";
