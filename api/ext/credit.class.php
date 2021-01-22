<?php


use ReceiptValidator\iTunes\Validator as iTunesValidator;

/// Move this to jewelry route.


define('REASON_SEND', 'send');
define('REASON_RECV', 'recv');
define('REASON_PAYMENT', 'payment');
define('REASON_TEST', 'test');

define('JEWELRY_ITEM_WATCH', 'watch');
define('JEWELRY_ITEM_BAG', 'bag');
define('JEWELRY_ITEM_RING', 'ring');

define('ERROR_DAILY_BONUS_GENERATED_ALREADY', 'ERROR_DAILY_BONUS_GENERATED_ALREADY');
define('ERROR_DAILY_BONUS_NOT_GENERATED', 'ERROR_DAILY_BONUS_NOT_GENERATED');

define('ERROR_FAILED_JEWELRY_TRANSFER', 'ERROR_FAILED_JEWELRY_TRANSFER');

define('JEWELRIES', ['diamond', 'gold', 'silver']);
define('DIAMOND', 'diamond');
define('GOLD', 'gold');
define('SILVER', 'silver');
define('STAMP', 'samp');
define('ERROR_WRONG_JEWELRY', 'ERROR_WRONG_JEWELRY');
define('ERROR_EMPTY_GOLD_ITEM', 'ERROR_EMPTY_GOLD_ITEM');
define('ERROR_TRANSFER_SAME_GENDER', 'ERROR_TRANSFER_SAME_GENDER');
define('ERROR_TRANSFER_MYSELF', 'ERROR_TRANSFER_MYSELF');
define('ERROR_RECEIPT_INVALID', 'ERROR_RECEIPT_INVALID');
define('EMPTY_PRODUCT_ID', 'EMPTY_PRODUCT_ID');
define('EMPTY_SERVER_VERIFICATION_DATA', 'EMPTY_SERVER_VERIFICATION_DATA');
define('ERROR_INSERT_PURCHASE_HISTORY', 'ERROR_INSERT_PURCHASE_HISTORY');
define('ERROR_EMPTY_PRODUCT_ID', 'ERROR_EMPTY_PRODUCT_ID');


define('ERROR_EMPTY_PURCHASE_ID', 'ERROR_EMPTY_PURCHASE_ID');
define('ERROR_EMPTY_PRODUCT_PRICE', 'ERROR_EMPTY_PRODUCT_PRICE');
define('ERROR_EMPTY_PRODUCT_TITLE', 'ERROR_EMPTY_PRODUCT_TITLE');
define('ERROR_EMPTY_PRODUCT_DESCRIPTION', 'ERROR_EMPTY_PRODUCT_DESCRIPTION');
define('ERROR_EMPTY_TRANSACTION_DATE', 'ERROR_EMPTY_TRANSACTION_DATE');
//define('ERROR_EMPTY_APPLICATION_USERNAME', 'ERROR_EMPTY_APPLICATION_USERNAME');
define('ERROR_EMPTY_PACKAGE_NAME', 'ERROR_EMPTY_PACKAGE_NAME');
define('ERROR_EMPTY_PRODUCT_IDENTIFIER', 'ERROR_EMPTY_PRODUCT_IDENTIFIER');
define('ERROR_EMPTY_QUANTITY', 'ERROR_EMPTY_QUANTITY');
define('ERROR_EMPTY_TRANSACTION_IDENTIFIER', 'ERROR_EMPTY_TRANSACTION_IDENTIFIER');
define('ERROR_EMPTY_TRANSACTION_TIMESTAMP', 'ERROR_EMPTY_TRANSACTION_TIMESTAMP');
define('ERROR_EMPTY_LOCAL_VERIFICATION_DATA', 'ERROR_EMPTY_LOCAL_VERIFICATION_DATA');
define('ERROR_EMPTY_SERVER_VERIFICATION_DATA', 'ERROR_EMPTY_SERVER_VERIFICATION_DATA');



define('JEWELRY_DAILY_BONUS_TABLE', 'jewelry_daily_bonus');
define('JEWELRY_CREDIT_TABLE', 'jewelry_credit');
define('JEWELRY_LOG_TABLE', 'jewelry_log');


define('PURCHASE_HISTORY_TABLE', 'purchase_history');





class Credit {


    public $todate;

    public function __construct()
    {
        $this->todate =  date('Ymd');
    }

    /**
     * @deprecated Use generateTodayBonus()
     * @param $x
     * @return array|mixed
     */
    public function generateDailyBonus($x) { return $this->generateTodayBonus($x); }

    /**
     * Generate daily bonus jewelry
     *
     * @param $user_ID
     * @param array $options
     *  Use options for test.
     * @return mixed
     *   - Error code will be returned if there is any error or if the bonus for today has already generated.
     *   - Returns the record of generated dail bonus.
     */
    public function generateTodayBonus($user_ID) {
        global $wpdb;

        $bonus = $this->getBonusJewelry($user_ID);
        if ( $bonus ) return ERROR_DAILY_BONUS_GENERATED_ALREADY;


        $diamond= random_int(MIN_BONUS_DIAMOND, MAX_BONUS_DIAMOND);
        $gold = random_int(MIN_BONUS_GOLD, MAX_BONUS_GOLD);
        $silver = random_int(MIN_BONUS_SILVER, MAX_BONUS_SILVER);


        $data = [
            'user_ID' => $user_ID,
            'date' => $this->todate,
            'stamp' => time(),
            'silver' => $silver,
            'gold' => $gold,
            'diamond' => $diamond,
            'history_silver' => $silver,
            'history_gold' => $gold,
            'history_diamond' => $diamond,
        ];
        $re = $wpdb->insert(JEWELRY_DAILY_BONUS_TABLE, $data);
        if ( $re === false ) return ERROR_INSERT;
        return $data;
    }

    /**
     * 인앱결제 유료 구매 한 경우, 보석을 랜덤 생성하고 기록을 남긴다.
     * @param $productId
     * @param $purchaseHistoryId int 결제를 한 후, 그 결과를 purchase_history 테이블에 저장하는데, 그 저장된 ID 값.
     *   즉, 결제 purchase_history ID 값. 이 ID 값으로 언제 어떤 결제를 했는지 알 수 있으며, 그 결제로 얼마의 랜덤 보석이 생성되었는지 알 수 있다.
     * @return array - 보석을 추가하고 난 후의 결과 레코드(diamond, gold, silver)를 리턴한다.
     * @throws Exception
     */
    public function generatePurchasedJewelry($productId, $purchaseHistoryId) {

        if ( $productId == 'goldbox') $rate = GOLDBOX_RATE;
        else if ( $productId == 'diamondbox' ) $rate = DIAMONDBOX_RATE;

        $diamond= random_int($rate['min_diamond'], $rate['max_diamond']);
        $gold = random_int($rate['min_gold'], $rate['max_gold']);
        $silver = random_int($rate['min_silver'], $rate['max_silver']);

        return $this->addJewelry(wp_get_current_user()->ID, $diamond, $gold, $silver, REASON_PAYMENT, $purchaseHistoryId);
    }


    /**
     * @return mixed|string
     *  null - is returned if there is no bonus jewelry generated for today in `jewelry_daily_bonus` table.
     *
     * @attention it returns `null` if there is no bonus for today. Not an empty array.
     */
    public function getMyBonusJewelry() {
        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
        return $this->getBonusJewelry(wp_get_current_user()->ID);
    }


    /**
     * Returns today's daily bonus jewelry of the user.
     * @param $user_ID
     * @return mixed
     *
     */
    public function getBonusJewelry($user_ID) {

        if ( empty($user_ID) ) return ERROR_EMPTY_USER_ID;
        $user = get_user_by('id', $user_ID);
        if ( ! $user ) return ERROR_USER_NOT_FOUND;

        global $wpdb;
        $todate = $this->todate;
        return $wpdb->get_row("SELECT * FROM ".JEWELRY_DAILY_BONUS_TABLE." WHERE user_ID=$user_ID and `date`=$todate", ARRAY_A);
    }



    /**
     * Returns my (paid) jewelry credits.
     * @return mixed
     */
    public function getMyCreditJewelry() {
        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
        return $this->getCreditJewelry(wp_get_current_user()->ID);
    }




    /**
     * Returns credit(paid or received) jewelry of the user.
     * @param $user_ID
     * @return array|object|void|null
     *
     * @note 만약, 유료 보석 레코드가 없으면 생성한다.
     */
    private function getCreditJewelry($user_ID) {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM ".JEWELRY_CREDIT_TABLE." WHERE user_ID=$user_ID", ARRAY_A);
        if ( !$row ) {
            $wpdb->insert(JEWELRY_CREDIT_TABLE, ['user_ID'=>$user_ID, 'diamond'=>0, 'gold'=>0, 'silver'=>0]);
            $row = $wpdb->get_row("SELECT * FROM ".JEWELRY_CREDIT_TABLE." WHERE user_ID=$user_ID", ARRAY_A);
        }
        return $row;
    }


    /**
     * Updates my daily bonus jewelry.
     * @param $jewelry
     * @param $amount
     * @return bool|int
     */
    private function updategetMyBonusJewelry($jewelry, $amount) {
        return $this->updateBonusJewelry(wp_get_current_user()->ID, $jewelry, $amount);
    }



    /**
     * Updates user's paid jewelry credit.
     * @param $user_ID
     * @param $jewelry
     * @param $amount
     * @return bool|int
     *
     * @note 유료 보석 레코드가 없으면 생성한다.
     */
    public function updateJewelry($user_ID, $jewelry, $amount) {

        /// Input check
        if ( !in_array($jewelry, JEWELRIES) ) return ERROR_WRONG_JEWELRY;
        if ( empty($user_ID) ) return ERROR_USER_NOT_FOUND;
        $other_user = get_user_by('id', $user_ID);
        if ( ! $other_user ) return ERROR_USER_NOT_FOUND;

        /// Update
        global $wpdb;
        $credit = $this->getCreditJewelry($user_ID);
        if ( $credit ) {
            return $wpdb->update(JEWELRY_CREDIT_TABLE, [$jewelry => $amount], ['user_ID' => $user_ID]);
        } else {
            return $wpdb->insert(JEWELRY_CREDIT_TABLE, ['user_ID'=>$user_ID, $jewelry=>$amount]);
        }
    }




    /**
     * Updates today's daily bonus of the user.
     * @param $user_ID
     * @param $jewelry
     * @param $amount
     * @return bool|int
     *   0 - if there is no error.
     */
    public function updateBonusJewelry($user_ID, $jewelry, $amount) {

        /// Input check
        if ( !in_array($jewelry, JEWELRIES) ) return ERROR_WRONG_JEWELRY;
        if ( empty($user_ID) ) return ERROR_EMPTY_USER_ID;
        $other_user = get_user_by('id', $user_ID);
        if ( ! $other_user ) return ERROR_USER_NOT_FOUND;

        $bonus = $this->getBonusJewelry($user_ID);
        if ( empty($bonus) ) return ERROR_DAILY_BONUS_NOT_GENERATED;


        global $wpdb;
        $todate = $this->todate;
        $re = $wpdb->update(JEWELRY_DAILY_BONUS_TABLE, [$jewelry=>$amount], ['user_ID'=>$user_ID, 'date'=>$todate]);

        if ( $re === false ) return ERROR_UPDATE;
        else if ( $re === 0 ) return ERROR_NONE_UPDATE;
        else return 0;
    }



    /**
     * Update login user's paid jewelry credit.
     *
     * @note this is private member. Can't be invoked directly by client for security reason.
     * @param $jewelry
     * @param $amount
     * @return bool|int
     */
    private function updateMyJewelry($jewelry, $amount) {
        return $this->updateJewelry(wp_get_current_user()->ID, $jewelry, $amount);
    }




    /**
     * Add jewelries to user credit.
     *
     * @usage Use this method to add randomly generated jewelries after user pay.
     *
     * Don't do transaction here since it wouldn't fall into race condition.
     * Mostly men will buy point and they would less receive jewelry.
     *
     * @param $user_ID
     * @param $diamond
     * @param $gold
     * @param $silver
     * @param $reason
     * @param $purchaseHistoryId int 결제 기록을 저장한 레코드 ID. purchase_history.ID
     *
     * @return array - 보석을 추가하고 난 후의 결과 레코드(diamond, gold, silver)를 리턴한다.
     *  - Returns credit after add.
     *
     * @attention 구매를 해서 랜덤 생성된 보석을 추가하는 경우, 'item' 에 purchase_history.ID 값이 저장된다. 이 값으로 어떤 결제로 인해서, 보석이 추가되는지 추적 할 수 있다.
     *
     */
    public function addJewelry($user_ID, $diamond, $gold, $silver, $reason, $purchaseHistoryId = '') {
        global $wpdb;
        $credit = $this->getCreditJewelry($user_ID);
        if ( $credit ) {
            $wpdb->update(JEWELRY_CREDIT_TABLE, [
                'diamond' => $credit['diamond'] + $diamond,
                'gold' => $credit['gold'] + $gold,
                'silver' => $credit['silver'] + $silver,
            ],
                ['user_ID' => $user_ID]
            );
        } else {
            $wpdb->insert(JEWELRY_CREDIT_TABLE, ['user_ID'=>$user_ID, 'diamond' => $diamond, 'gold'=>$gold, 'silver'=>$silver]);
        }

        $after_jewelry = $this->getCreditJewelry($user_ID);


        $wpdb->insert(JEWELRY_LOG_TABLE, [
            'stamp' => time(),
            'bonus_count' => 0,
            'from_user_ID' => '',
            'to_user_ID' => $user_ID,
            'before_diamond' => $credit['diamond'] ? $credit['diamond'] : 0,
            'before_gold' => $credit['gold'] ? $credit['gold'] : 0,
            'before_silver' => $credit['silver'] ? $credit['silver'] : 0,
            'apply_diamond' => $diamond,
            'apply_gold' => $gold,
            'apply_silver' => $silver,
            'after_diamond' => $after_jewelry['diamond'],
            'after_gold' => $after_jewelry['gold'],
            'after_silver' => $after_jewelry['silver'],
            'reason' => $reason,
            'item' => $purchaseHistoryId,
        ]);

        return $after_jewelry;
    }

    public function _empty_jewelry() {
        return [ DIAMOND => 0, GOLD => 0, SILVER => 0 ];
    }





    /**
     * Transfer my Jewelry to other user (by recommending)
     *
     * @attention This function is designed to work with Race Condition.
     *
     * @param $in
     *  $in['jewelry'] is one of diamond, gold, silver
     *  $in['item'] is one of the watch, ring, bag. it is only needed when jewelry is gold.
     *  $in['count'] is the number of jewelry to give.
     *  $in['user_ID'] is the user who gets jewelry.
     *
     * @return array|string
     *  보낸 사람을 기준으로 유료 보석과 무료 보석을 정보를 배열로 리턴한다.
     */
    public function giveJewelry($in) {
        if ( empty($in['jewelry']) ) return ERROR_EMPTY_JEWELRY;
        $jewelry_name = $in['jewelry'];
        if ( !in_array($jewelry_name, JEWELRIES) ) return ERROR_WRONG_JEWELRY;

        if ( $jewelry_name == 'gold' && !isset($in['item']) ) return ERROR_EMPTY_GOLD_ITEM;

        if ( empty($in['count']) ) return ERROR_EMPTY_COUNT;
        $count = $in['count'];
        if ( !is_numeric($count)) return ERROR_WRONG_COUNT;

        if ( empty($in['user_ID']) ) return ERROR_EMPTY_USER_ID;
        $other_ID = $in['user_ID'];
        $other_user = get_user_by('id', $other_ID);
        if ( ! $other_user ) return ERROR_USER_NOT_FOUND;

        $my_ID = wp_get_current_user()->ID;

        if ( $other_ID == $my_ID ) return ERROR_TRANSFER_MYSELF;

        // 같은 성별끼리 전송 불가
        // 주의: 두 사용자 모두 gender(성별) 값이 존재하지 않으면, 통과한다. 애초부터 gender 값은 필수적으로 들어 가도록 해야 하며, 클라이언트에서 gender 값이 없으면 추천을 못하도록 해야 한다.
        $other_gender = get_user_meta($other_ID, 'gender', true);
        $my_gender = get_user_meta($my_ID, 'gender', true);
        if ( $my_gender && $my_gender == $other_gender ) return ERROR_TRANSFER_SAME_GENDER;

        $bonus = $this->getMyBonusJewelry() ?? $this->_empty_jewelry();
        if ( api_error($bonus) ) return $bonus;

        $credit = $this->getMyCreditJewelry();

        /// 무료 보석과 유료 보석을 합쳐서 계산
        $total_jewelry_count = $bonus[$jewelry_name] + $credit[$jewelry_name];
        if ( $total_jewelry_count < $count ) return ERROR_NOT_ENOUGH_JEWELRY;



        global $wpdb;
        /// Test without transaction
        $wpdb->query("START TRANSACTION");

        /// Decrease my jewelry from jewelry_credit table
        $my_bonus_jewelry = $this->getMyBonusJewelry() ?? $this->_empty_jewelry();
        $my_credit_jewelry = $this->getMyCreditJewelry();


//    print_r("before bonus jewelry:\n");
//    print_r($my_bonus_jewelry);
//    print_r("before credit jewelry:\n");
//    print_r($my_credit_jewelry);


        if ( $my_bonus_jewelry[ $jewelry_name ] >= $count ) {
            $bonus_count = $count;
            $decreased = $my_bonus_jewelry[ $jewelry_name ] - $count;
            $this->updategetMyBonusJewelry($jewelry_name, $decreased);
        } else {
            $bonus_count = $my_bonus_jewelry[ $jewelry_name ];
            $this->updategetMyBonusJewelry($jewelry_name, 0);
            $decreased = $my_credit_jewelry[$jewelry_name] - ( $count - $my_bonus_jewelry[ $jewelry_name ] );
            $this->updateMyJewelry($jewelry_name, $decreased);
        }

//    print_r("bonus_count: $bonus_count\n");


        /// Increase other user's jewelry into jewelry_credit table
        $other_jewelry = $this->getCreditJewelry($other_ID);
        $increased = $other_jewelry[$jewelry_name] + $count;
        $updateResult = $this->updateJewelry($other_ID, $jewelry_name, $increased);

        $after_my_bonus_jewelry = $this->getMyBonusJewelry() ?? $this->_empty_jewelry();
        $after_my_credit_jewelry = $this->getMyCreditJewelry();
        $after_other_jewelry = $this->getCreditJewelry($other_ID);

//    print_r("after_my_bonus_jewelry:\n");
//    print_r($after_my_bonus_jewelry);
//    print_r("after_my_credit_jewelry:\n");
//    print_r($after_my_credit_jewelry);
//    print_r("after_other_jewelry:\n");
//    print_r($after_other_jewelry);



        /// Is increase & decrease is okay?
        $my_after_sum_with_count = $after_my_credit_jewelry[$jewelry_name] + $after_my_bonus_jewelry[$jewelry_name] + $count;
        $my_before_sum = $my_bonus_jewelry[$jewelry_name] + $my_credit_jewelry[$jewelry_name];
//    echo("before_sum == after_sum_with_count : ($my_after_sum_with_count == $my_before_sum)\n");

        $my_before = $my_credit_jewelry[$jewelry_name];
        $my_after_minus_count = $after_my_credit_jewelry[$jewelry_name] + ($count - $bonus_count);
//    echo "my_before == my_after_minus_count: $my_before == $my_after_minus_count\n";

        if (
            ($my_after_sum_with_count == $my_before_sum)
            &&
            ($my_before == $my_after_minus_count )
            &&
            ($other_jewelry[$jewelry_name] + $count == $after_other_jewelry[$jewelry_name] )
        ) {
            // then, log & commit.
            $wpdb->insert(JEWELRY_LOG_TABLE, [
                'stamp' => time(),
                'bonus_count' => $bonus_count,
                'from_user_ID' => $my_ID,
                'to_user_ID' => $other_ID,

                'before_diamond' => $my_credit_jewelry['diamond'] ? $my_credit_jewelry['diamond'] : 0,
                'before_gold' => $my_credit_jewelry['gold'] ? $my_credit_jewelry['gold'] : 0,
                'before_silver' => $my_credit_jewelry['silver'] ? $my_credit_jewelry['silver'] : 0,


                'before_bonus_diamond' => $my_bonus_jewelry['diamond'] ? $my_bonus_jewelry['diamond'] : 0,
                'before_bonus_gold' => $my_bonus_jewelry['gold'] ? $my_bonus_jewelry['gold'] : 0,
                'before_bonus_silver' => $my_bonus_jewelry['silver'] ? $my_bonus_jewelry['silver'] : 0,

                'apply_diamond' => $jewelry_name == 'diamond' ? $count : 0,
                'apply_gold' => $jewelry_name == 'gold' ? $count : 0,
                'apply_silver' => $jewelry_name == 'silver' ? $count : 0,

                'after_diamond' => $after_my_credit_jewelry['diamond'],
                'after_gold' => $after_my_credit_jewelry['gold'],
                'after_silver' => $after_my_credit_jewelry['silver'],

                'after_bonus_diamond' => $after_my_bonus_jewelry['diamond'],
                'after_bonus_gold' => $after_my_bonus_jewelry['gold'],
                'after_bonus_silver' => $after_my_bonus_jewelry['silver'],

                'reason' => REASON_SEND,
                'item' => isset($in['item']) ? $in['item'] : '',
            ]);

            $wpdb->insert(JEWELRY_LOG_TABLE, [
                'stamp' => time(),
                'bonus_count' => $bonus_count,
                'from_user_ID' => $my_ID,
                'to_user_ID' => $other_ID,
                'before_diamond' => $other_jewelry['diamond'] ? $other_jewelry['diamond'] : 0,
                'before_gold' => $other_jewelry['gold'] ? $other_jewelry['gold'] : 0,
                'before_silver' => $other_jewelry['silver'] ? $other_jewelry['silver'] : 0,
                'apply_diamond' => $jewelry_name == 'diamond' ? $count : 0,
                'apply_gold' => $jewelry_name == 'gold' ? $count : 0,
                'apply_silver' => $jewelry_name == 'silver' ? $count : 0,
                'after_diamond' => $after_other_jewelry['diamond'],
                'after_gold' => $after_other_jewelry['gold'],
                'after_silver' => $after_other_jewelry['silver'],
                'reason' => REASON_RECV,
                'item' => isset($in['item']) ? $in['item'] : '',
            ]);
            $wpdb->query("COMMIT");

            return [
                'user_ID' => $other_ID, // To whom it was sent
                'jewelry' => $jewelry_name, //
                'count' => $count,
                'item' => $in['item'] ?? '',
                DIAMOND => $after_my_credit_jewelry[DIAMOND], // paid diamond after sent
                GOLD => $after_my_credit_jewelry[GOLD], // paid gold after sent
                SILVER => $after_my_credit_jewelry[SILVER], // paid silver after sent
                'bonus_diamond' => $after_my_bonus_jewelry[DIAMOND], // bonus diamond after sent
                'bonus_gold' => $after_my_bonus_jewelry[GOLD], // bonus gold after sent
                'bonus_silver' => $after_my_bonus_jewelry[SILVER], // bonus silver after sent.
            ];
        } else {
            $wpdb->query("ROLLBACK");
            return ERROR_FAILED_JEWELRY_TRANSFER;
        }

    }


    /**
     * 보석 변경 기록 레코드를 가져온다.
     *
     * 가장 최근 유료 결제 한 보석 랜덤 생성 기록을 가져오려면,
     * @param $user_ID
     * @param $reason
     * @param array $options
     * @return array|object|string|null
     */
    public function getJewelryLogs($user_ID, $reason, $options = ['limit' => 1000, 'orderBy'=>'ID', 'sort'=>'DESC']) {
        global $wpdb;

        $q = "SELECT * FROM " . JEWELRY_LOG_TABLE . " WHERE";
        switch ( $reason ) {
            case REASON_SEND: $q .= " (from_user_ID=$user_ID AND reason='".REASON_SEND."')"; break;
            case REASON_RECV: $q .= " (to_user_ID=$user_ID AND reason='".REASON_RECV."')"; break;
            case REASON_PAYMENT: $q .= " (to_user_ID=$user_ID AND reason='".REASON_PAYMENT."')"; break;
            default: return ERROR_WRONG_QUERY;
        }

        $q .= " ORDER BY $options[orderBy] $options[sort]";
        $q .= " LIMIT $options[limit]";

//    echo "$q\n";
        return $wpdb->get_results($q, ARRAY_A);
    }



    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     *
     *
    Array
    (
    [original_purchase_date_pst] => 2012-04-30 08:05:55 America/Los_Angeles
    [original_transaction_id] => 1000000046178817
    [original_purchase_date_ms] => 1335798355868
    [transaction_id] => 1000000046178817
    [quantity] => 1
    [product_id] => com.mindmobapp.download
    [bvrs] => 20120427
    [purchase_date_ms] => 1335798355868
    [purchase_date] => 2012-04-30 15:05:55 Etc/GMT
    [original_purchase_date] => 2012-04-30 15:05:55 Etc/GMT
    [purchase_date_pst] => 2012-04-30 08:05:55 America/Los_Angeles
    [bid] => com.mindmobapp.MindMob
    [item_id] => 521129812
    )
    Receipt data = 1
    getProductId: com.mindmobapp.download
    getTransactionId: 1000000046178817
    getPurchaseDate: 2012-04-30T15:05:56+00:00
     */
    public function verifyIOSPurchase($in) {


        $validator = new iTunesValidator(iTunesValidator::ENDPOINT_PRODUCTION); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing


//        $receiptBase64Data = 'ewoJInNpZ25hdHVyZSIgPSAiQXBNVUJDODZBbHpOaWtWNVl0clpBTWlKUWJLOEVkZVhrNjNrV0JBWHpsQzhkWEd1anE0N1puSVlLb0ZFMW9OL0ZTOGNYbEZmcDlZWHQ5aU1CZEwyNTBsUlJtaU5HYnloaXRyeVlWQVFvcmkzMlc5YVIwVDhML2FZVkJkZlcrT3kvUXlQWkVtb05LeGhudDJXTlNVRG9VaFo4Wis0cFA3MHBlNWtVUWxiZElWaEFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09IjsKCSJwdXJjaGFzZS1pbmZvIiA9ICJld29KSW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRjSE4wSWlBOUlDSXlNREV5TFRBMExUTXdJREE0T2pBMU9qVTFJRUZ0WlhKcFkyRXZURzl6WDBGdVoyVnNaWE1pT3dvSkltOXlhV2RwYm1Gc0xYUnlZVzV6WVdOMGFXOXVMV2xrSWlBOUlDSXhNREF3TURBd01EUTJNVGM0T0RFM0lqc0tDU0ppZG5KeklpQTlJQ0l5TURFeU1EUXlOeUk3Q2draWRISmhibk5oWTNScGIyNHRhV1FpSUQwZ0lqRXdNREF3TURBd05EWXhOemc0TVRjaU93b0pJbkYxWVc1MGFYUjVJaUE5SUNJeElqc0tDU0p2Y21sbmFXNWhiQzF3ZFhKamFHRnpaUzFrWVhSbExXMXpJaUE5SUNJeE16TTFOems0TXpVMU9EWTRJanNLQ1NKd2NtOWtkV04wTFdsa0lpQTlJQ0pqYjIwdWJXbHVaRzF2WW1Gd2NDNWtiM2R1Ykc5aFpDSTdDZ2tpYVhSbGJTMXBaQ0lnUFNBaU5USXhNVEk1T0RFeUlqc0tDU0ppYVdRaUlEMGdJbU52YlM1dGFXNWtiVzlpWVhCd0xrMXBibVJOYjJJaU93b0pJbkIxY21Ob1lYTmxMV1JoZEdVdGJYTWlJRDBnSWpFek16VTNPVGd6TlRVNE5qZ2lPd29KSW5CMWNtTm9ZWE5sTFdSaGRHVWlJRDBnSWpJd01USXRNRFF0TXpBZ01UVTZNRFU2TlRVZ1JYUmpMMGROVkNJN0Nna2ljSFZ5WTJoaGMyVXRaR0YwWlMxd2MzUWlJRDBnSWpJd01USXRNRFF0TXpBZ01EZzZNRFU2TlRVZ1FXMWxjbWxqWVM5TWIzTmZRVzVuWld4bGN5STdDZ2tpYjNKcFoybHVZV3d0Y0hWeVkyaGhjMlV0WkdGMFpTSWdQU0FpTWpBeE1pMHdOQzB6TUNBeE5Ub3dOVG8xTlNCRmRHTXZSMDFVSWpzS2ZRPT0iOwoJImVudmlyb25tZW50IiA9ICJTYW5kYm94IjsKCSJwb2QiID0gIjEwMCI7Cgkic2lnbmluZy1zdGF0dXMiID0gIjAiOwp9';

//        $receiptBase64Data = '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';
        $receiptBase64Data = $in['serverVerificationData'];
        try {
            $response = $validator->setReceiptData($receiptBase64Data)->validate();

//            print_r($response);

            // $sharedSecret = '1234...'; // Generated in iTunes Connect's In-App Purchase menu
            // $response = $validator->setSharedSecret($sharedSecret)->setReceiptData($receiptBase64Data)->validate(); // use setSharedSecret() if for recurring subscriptions
        } catch (Exception $e) {
            $errmsg = 'got error = ' . $e->getMessage() . PHP_EOL;
            debug_log($errmsg);
            return $errmsg;
        }

        if ($response->isValid()) {
            $json = json_encode($response->getReceipt());
            foreach ($response->getPurchases() as $purchase) {
                $productId = $purchase->getProductId();
                $transactionId = $purchase->getTransactionId();
            }

            $history = $this->savePurchaseHistory($in);
            $jewelry = $this->generatePurchasedJewelry($in['productID'], $history['ID']);

            return [
                'productId' => $productId ?? '',
                'transactionId' => $transactionId ?? '',
                'history' => $history,
                'jewelry' => $jewelry
            ];
        } else {
            return ERROR_RECEIPT_INVALID . ':' . $response->getResultCode();
        }
    }


    /**
     * @see see the note on `savePurchaseHistory` for the input properties.
     * @param $in
     * @return array|string
     * @throws \Google\Exception
     */
    public function verifyAndroidPurchase($in) {


        $googleClient = new \Google_Client();
        $googleClient->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
        $googleClient->setApplicationName('Your_Purchase_Validator_Name');
        $googleClient->setAuthConfig(SERVICE_ACCOUNT_LINK_TO_APP_JSON_FILE_PATH);

        $googleAndroidPublisher = new \Google_Service_AndroidPublisher($googleClient);
        $validator = new \ReceiptValidator\GooglePlay\Validator($googleAndroidPublisher);


        try {
            $response = $validator->setPackageName($in['localVerificationData_packageName'])
                ->setProductId($in['productID'])
                ->setPurchaseToken($in['serverVerificationData'])
                ->validatePurchase();
            debug_log('response->developerPayload', $response->getDeveloperPayload());

            print("\n\nresult: ");
            echo "\nresponse->getAcknowledgementState()"; print_r($response->getAcknowledgementState());
            echo "\nresponse->getConsumptionState()"; print_r($response->getConsumptionState());
            echo "\nresponse->getPurchaseState()"; print_r($response->getPurchaseState());
            echo "\nresponse->getRawResponse()"; print_r($response->getRawResponse());

            print_r("success?");
            print_r($response);

            $history = $this->savePurchaseHistory($in);
            $jewelry = $this->generatePurchasedJewelry($in['productID'], $history['ID']);

            return [
                'productId' => $in['productID'],
                'transactionId' => $in['purchaseID'],  /// if the response has transactionID update this variable
                'history' => $history,
                'jewelry' => $jewelry
            ];
        } catch (\Exception $e){
            $msg = $e->getMessage();

            return ERROR_RECEIPT_INVALID . ':' . $msg;
//            var_dump($e->getMessage());
            // example message: Error calling GET ....: (404) Product not found for this application.
        }
    }

    /**
     * 결제 기록을 저장한다.
     * @note for Android,
     *      locationVerificationData.orderId is same as purchaseID,
     *      locationVerificationData.productId is same as productID.
     *      locationVerificationData.purchaseTime is same as transactionDate.
     *      locationVerificationData.packageName is saved as locationVerificationData_packageName
     *      locationVerificationData.purchaseToken is saved as localVerificationData which has same value of serverVerificationData.
     * @param $in
     * @return mixed 결제 기록을 저장한 레코드
     */
    public function savePurchaseHistory($in) {

        $data = [
            "stamp" => time(),
            "platform"=> $in['platform'],
            "status" => "success",
            "user_ID" => wp_get_current_user()->ID,
            "productID" => $in["productID"],
            "purchaseID" => $in["purchaseID"],
            "price" => $in["price"],
            "title" => $in["title"] ?? '',
            "description" => $in["description"] ?? '',
            "transactionDate" => $in["transactionDate"],
            "applicationUsername" => $in["applicationUsername"] ?? "",
            "productIdentifier" => $in["productIdentifier"],
            "quantity" => $in["quantity"],
            "transactionIdentifier" => $in["transactionIdentifier"],
            "transactionTimeStamp" => $in["transactionTimeStamp"],
            "localVerificationData" => $in["localVerificationData"],
            "serverVerificationData" => $in["serverVerificationData"],
            "localVerificationData_packageName" => $in["localVerificationData_packageName"] ?? '',
        ];

        global $wpdb;
        $re = $wpdb->insert(PURCHASE_HISTORY_TABLE, $data);
        if ( $re === false ) return ERROR_INSERT_PURCHASE_HISTORY;
        $insert_id = $wpdb->insert_id;
        return $wpdb->get_row("SELECT * FROM " . PURCHASE_HISTORY_TABLE . " WHERE ID=$insert_id", ARRAY_A);
    }


}