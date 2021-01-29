<?php

class NotificationRoute {

    /**
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     *
     * @example how to access
     *
     * http://local.wordpress.org/wordpress-api-v2/php/api.php?method=pushNotification.tokenUpdate&token=c36jia8q_4qMm05fRhQ0_r:APA91bGB0en7xx3h1QF8jGaGF84qalp1JDzbfI5Kt9Klx02y3BUfaEloP57sfyYOXXpuTTMU3Fw7DJ-kNsf5qkGnA2V1NqwhLH7vlLQCCpeJgz-kqfhYBauhycOwVkkEIx6Z8yVO7nWe&topic=abc
     *
     * @note expected result
     *
     * {"code":0,"data":{"token":"c36jia8q_4qMm05fRhQ0_r:APA91bGB0en7xx3h1QF8jGaGF84qalp1JDzbfI5Kt9Klx02y3BUfaEloP57sfyYOXXpuTTMU3Fw7DJ-kNsf5qkGnA2V1NqwhLH7vlLQCCpeJgz-kqfhYBauhycOwVkkEIx6Z8yVO7nWe","user_ID":"0","type":"","stamp":"1579611552"},"method":"pushNotification.tokenUpdate"}
     *
     */
    public function updateToken($in)
    {
        return update_token($in);
    }

    /**
     *
     * $in['tokens'] can be a string of a token or an array of tokens
     *
     * @return \Kreait\Firebase\Messaging\MulticastSendReport
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function sendMessageToTokens($in) {
        if ( !isset($in['data'])) $in['data'] = [];
        if ( !isset($in['imageUrl'])) $in['imageUrl'] = '';
        return sendMessageToTokens($in['tokens'], $in['title'], $in['body'], $in['click_action'], $in['data'], $in['imageUrl']);
    }


    /**
     * @param $in
     * @return array|string
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function sendMessageToTopic($in) {
        if ( !isset($in['topic']) ) return ERROR_EMPTY_TOPIC;
        if ( !isset($in['data'])) $in['data'] = [];
        if ( !isset($in['imageUrl'])) $in['imageUrl'] = '';
        return sendMessageToTopic($in['topic'], $in['title'], $in['body'], $in['click_action'], $in['data'], $in['imageUrl']);
    }


    /**
     * @param $in
     * @return \Kreait\Firebase\Messaging\MulticastSendReport
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function sendMessageToUsers($in) {
        return send_message_to_users($in);
    }

    /**
     * @param $in
     *  $in['tokens'] can be a string of tokens or an array of tokens.
     *  If $in['tokens'] is not provided, then it will subscribe all the tokens of login user to the topic.
     * @return array|string
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function subscribeTopic($in) {
        if ( isset($in['tokens'] ) ) $tokens = $in['tokens'];
        else {
            $tokens = get_user_tokens();
        }
        if ( empty($tokens) ) return ERROR_EMPTY_TOKENS;
        return subscribeTopic($in['topic'], $tokens);
    }


    /**
     * @param $in
     *  $in['tokens'] can be a string of tokens or an array of tokens.
     *  If $in['tokens'] is not provided, then it will unsubscribe all the tokens of login user from the topic.
     * @return array
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function unsubscribeTopic($in) {
        if ( isset($in['tokens'] ) ) $tokens = $in['tokens'];
        else {
            $tokens = get_user_tokens();
        }
        return unsubscribeTopic($in['topic'], $tokens);
    }

}