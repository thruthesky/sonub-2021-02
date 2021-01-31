<?php

define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');

$factory = getFirebase();

$auth = $factory->createAuth();

$userProperties = [
    'email' => 'user2@example.com',
    'emailVerified' => false,
    'phoneNumber' => '+15555550101',
    'password' => 'secretPassword',
    'displayName' => 'John Doe',
    'photoUrl' => 'http://www.example.com/12345678/photo.png',
    'disabled' => false,
];

try {
    $createdUser = $auth->createUser($userProperties);
    print_r($createdUser);
} catch (Exception $e) {
    echo "=======> Got exception\n";
    echo "Error code: " . $e->getCode() . ", message: " .  $e->getMessage() . " at " . $e->getFile() . ':' . $e->getLine();
}
