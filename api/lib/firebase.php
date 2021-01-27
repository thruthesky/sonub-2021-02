<?php
/**
 * @file firebase
 */

use Kreait\Firebase\Database;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;

/**
 * This returns the firebase factory (instance)
 * @return Factory
 *
 * @example
 *  $factory = getFirebase();
 *
 */
function getFirebase() {
    return (new Factory)->withServiceAccount(SERVICE_ACCOUNT_FIREBASE_JSON_FILE_PATH)->withDatabaseUri(FIREBASE_DATABASE_URI);
}


/**
 * @return \Kreait\Firebase\Messaging
 *
 * @example
 *  $messaging = getMessaging()
 */
function getMessaging() {
    return getFirebase()->createMessaging();
}

/**
 * Returns Firebase Realtime Database instance.
 *
 * @return Database
 */
function getDatabase() {
    return getFirebase()->createDatabase();
}




/**
 * @param $tokens
 * @param $title
 * @param $body
 * @param $click_action
 * @param array $data
 * @param string $imageUrl
 * @return \Kreait\Firebase\Messaging\MulticastSendReport
 */
function sendMessageToTokens($tokens, $title, $body, $imageUrl="https://philgo.com/theme/philgo/img/logo-small.png", $click_action, $data = []) {
    $message = CloudMessage::fromArray([
        'notification' => getNotificationData($title, $body, $imageUrl, $click_action, $data),
        'webpush' => getWebPushData($title, $body, $imageUrl, $click_action, $data),
        'android' => getAndroidPushData(),
        'data' => $data,
    ]);
    return getMessaging()->sendMulticast($message, $tokens);
}

/**
 * @param $topic
 * @param $title
 * @param $body
 * @param $click_action
 * @param array $data
 * @param string $imageUrl
 * @return array
 */
function sendMessageToTopic($topic, $title, $body, $imageUrl="https://philgo.com/theme/philgo/img/logo-small.png", $click_action, $data = [] ) {
    $message = CloudMessage::fromArray([
        'topic' => $topic,
        'notification' => getNotificationData($title, $body, $imageUrl, $click_action, $data),
        'webpush' => getWebPushData($title, $body, $imageUrl, $click_action, $data),
        'android' => getAndroidPushData(),
        'data' => $data,
    ]);

    return getMessaging()->send($message);
}

/**
 * @param $topic
 * @param $tokens - a token or an array of tokens
 * @return array
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function subscribeTopic($topic, $tokens) {
    return getMessaging()->subscribeToTopic($topic, $tokens);
}

/**
 * @param $topic
 * @param $tokens - a token or an array of tokens
 * @return array
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function unsubscribeTopic($topic, $tokens) {
    return getMessaging()->unsubscribeFromTopic($topic, $tokens);
}

/**
 * it look like data and notification is redundant but this is needed here specially for onResume and onLaunch
 * because onResume and onLaunch notification became empty. so we can rely on data to display on ui
 *
 * @param $title
 * @param $body
 * @param $imageUrl
 * @param $clickUrl
 * @param $data
 * @return array
 */
function getData($title, $body, $imageUrl, $clickUrl, $data) {
    $notification = [
        'title' => $title,
        'body' => $body,
        'image' => $imageUrl,
        'click_action' => $clickUrl,
        'data' => $data
    ];
    return $notification;
}

function getNotificationData($title, $body, $imageUrl, $clickUrl, $data) {
    $notification = Notification::fromArray([
        'title' => $title,
        'body' => $body,
        'image' => $imageUrl,
        'click_action' => $clickUrl,
        'data' => $data
    ]);
    return $notification;
}

function getWebPushData($title, $body, $iconUrl, $clickUrl, $data) {
    $title = mb_strcut($title, 0, 64);
    $body = mb_strcut($body, 0, 128);
    return [
        'notification' => [
            'title' => $title,
            'body' => $body,
            'icon' => $iconUrl,
            'click_action' => $clickUrl ?? "/",
            'data' => $data
        ],
        'fcm_options' => [
            'link' => $clickUrl ?? "/",
        ],
    ];
}


function getAndroidPushData() {
    return AndroidConfig::fromArray([
        'notification' => [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ],
    ]);
}




