<?php

define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');

$factory = getFirebase();


$rows = $wpdb->get_results("SELECT * FROM " . PUSH_TOKEN_TABLE, ARRAY_A);

$tokens = [];
foreach($rows as $row) {
    $tokens[] = $row['token'];
}


$re = subscribeTopic('hello', $tokens);
print_r($re);

$result = sendMessageToTopic('hello', 'Yo!', 'How are you?', ['E' => 'Elephant']);
print_r($result);


$re = unsubscribeTopic('hello', $tokens);
print_r($re);

$result = sendMessageToTopic('hello', 'Title', 'No message!', ['F' => 'Fish']);
print_r($result);

