<?php

define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');

$factory = getFirebase();


$rows = $wpdb->get_results("SELECT * FROM " . PUSH_TOKEN_TABLE, ARRAY_A);

$tokens = [];
foreach($rows as $row) {
    $tokens[] = $row['token'];
}

$result = sendMessageToTokens($tokens, 'Title from PHP', 'Content');
print_r($result);
