<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
/**
 * @return Factory
 *
 * @example
 *  $factory = getFirebase();
 */
function getFirebase() {
    return (new Factory)->withServiceAccount(V3_DIR . '/firebase_credentials.json');
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
 * @param $tokens
 * @param $title
 * @param $body
 * @param array $data
 * @param string $imageUrl
 * @return \Kreait\Firebase\Messaging\MulticastSendReport
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function sendMessageToTokens($tokens, $title, $body, $data = [], $imageUrl="https://philgo.com/theme/philgo/img/logo-small.png") {

    $message = CloudMessage::fromArray([
        'notification' => [
            'title' => $title,
            'body' => $body,
            'image' => $imageUrl,
        ],
        'data' => $data,
    ]);

    return getMessaging()->sendMulticast($message, $tokens);

}

/**
 * @param $topic
 * @param $title
 * @param $body
 * @param array $data
 * @param string $imageUrl
 * @return array
 * @throws \Kreait\Firebase\Exception\FirebaseException
 * @throws \Kreait\Firebase\Exception\MessagingException
 */
function sendMessageToTopic($topic, $title, $body, $data = [], $imageUrl="https://philgo.com/theme/philgo/img/logo-small.png") {

    $message = CloudMessage::fromArray([
        'topic' => $topic,
        'notification' => [
            'title' => $title,
            'body' => $body,
            'image' => $imageUrl,
        ], // optional
        'data' => $data, // optional
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


