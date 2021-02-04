<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;



require_once("../../../wp-load.php");
require_once(API_DIR . '/routes/notification.route.php');

class SubscribeTopicTest extends TestCase{

    /**
     * @group firebase
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function testSubscribeTopicTest() {

        set_phpunit_mode(true);

        $noti = new NotificationRoute();

        $re = $noti->updateToken(['']);
        self::assertTrue($re === ERROR_EMPTY_TOKEN);

        /// clear existing tokens

        global $wpdb;
        $wpdb->delete(PUSH_TOKENS_TABLE, ['user_ID' => 2]);

        wp_set_current_user(2);
        $re = $noti->updateToken(['token' => 'A']);
        self::assertTrue($re['token'] === 'A');

        $re = $noti->subscribeTopic(['topic' => 'T']);
        self::assertTrue(count($re['T']) === 1);



        $re = $noti->updateToken(['token' => 'B']);
        self::assertTrue($re['token'] === 'B');

        $tokens = get_user_tokens();

        self::assertTrue(count($tokens) === 2);
        self::assertTrue(in_array('A', $tokens));
        self::assertTrue(in_array('B', $tokens));



        $re = $noti->subscribeTopic(['topic' => 'T']);
        self::assertTrue(count($re['T']) === 2);

    }
}
