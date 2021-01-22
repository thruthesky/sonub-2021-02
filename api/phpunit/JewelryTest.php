<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;


if ( !defined('API_DIR') ) define('API_DIR', '.');
require_once(API_DIR . '/api-load.php');
require_once(API_DIR . '/ext/credit.class.php');
require_once(API_DIR . '/ext/nalia.route.php');



class JewelryTest extends TestCase {

    private $credit;
    private $user_ID = 1;
    private $other_ID = 2;
    private $he_3 = 3;
    private $she_4 = 4;
    private $you_5 = 5;
    private $me_6 = 6;

    private $jenny = 7;

    private $male = 8;
    private $female = 9;
    private $male2 = 10;



    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->credit = new Credit();
        $this->clearTestTables();


        update_user_meta($this->male, 'gender', 'M');
        update_user_meta($this->female, 'gender', 'F');
        update_user_meta($this->male2, 'gender', 'M');

    }

    private function clearTestTables() {
        global $wpdb;
        $wpdb->query("TRUNCATE " . JEWELRY_LOG_TABLE);
        $wpdb->query("TRUNCATE " . JEWELRY_DAILY_BONUS_TABLE);
        $wpdb->query("TRUNCATE " . JEWELRY_CREDIT_TABLE);
    }


    public function testParams(): void {
        $re = $this->credit->giveJewelry([]);
        self::assertEquals($re, ERROR_EMPTY_JEWELRY);

        $re = $this->credit->giveJewelry(['jewelry']);
        self::assertEquals($re, ERROR_EMPTY_JEWELRY);

        $re = $this->credit->giveJewelry(['jewelry'=>'ruby']);
        self::assertEquals($re, ERROR_WRONG_JEWELRY);

        $re = $this->credit->giveJewelry(['jewelry'=>'diamond']);
        self::assertEquals($re, ERROR_EMPTY_COUNT);

        $re = $this->credit->giveJewelry(['jewelry'=>'diamond', 'count' => 'abc']);
        self::assertEquals($re, ERROR_WRONG_COUNT);

        $re = $this->credit->giveJewelry(['jewelry'=>'diamond', 'count' => '123']);
        self::assertEquals($re, ERROR_EMPTY_USER_ID);

        $re = $this->credit->giveJewelry(['jewelry'=>'diamond', 'count' => 12, 'user_ID' => 99912345]);
        self::assertEquals($re, ERROR_USER_NOT_FOUND);

        $re = $this->credit->giveJewelry(['jewelry'=>'diamond', 'count' => 12, 'user_ID' => 1]);
        self::assertEquals($re, ERROR_LOGIN_FIRST);

        $re = $this->credit->updateBonusJewelry(0, 'gold', 1);
        self::assertEquals($re, ERROR_EMPTY_USER_ID);

        $re = $this->credit->updateBonusJewelry(1, 'ruby', 1);
        self::assertEquals($re, ERROR_WRONG_JEWELRY);
    }

    public function testUpdateBonusJewelry() {
        $re = $this->credit->updateBonusJewelry($this->user_ID, 'gold', 1);
        self::assertEquals($re, ERROR_DAILY_BONUS_NOT_GENERATED,  "Got: $re");
    }

    public function testGetMyBonusJewelry() {
        wp_set_current_user($this->user_ID);
        $bonus = $this->credit->getMyBonusJewelry();
        self::assertTrue($bonus === null);
    }



    public function testUpdateBonusJewelryAfterGenerate() {
        wp_set_current_user($this->user_ID);
        $bonus = $this->credit->generateTodayBonus($this->user_ID);
        $this->credit->updateBonusJewelry($this->user_ID, 'gold', 1);
        $after_update = $this->credit->getMyBonusJewelry();
        self::assertTrue($after_update['diamond'] == $bonus['diamond'], 'diamond did not change');
        self::assertTrue($after_update['gold'] == 1, 'gold 1');
        self::assertTrue($after_update['silver'] == $bonus['silver'], 'silver did not change');
    }

    /**
     * Generate daily bonus by changing date and test the bonus jewelry amount.
     */
    public function testGenerate() {

        $user_ID = $this->user_ID;
        for( $i = 0; $i <= 30; $i ++ ) {
            $stamp = mktime(0, 0, 0, intval(date('m')), intval(date('d')) - $i - 3, intval(date('Y')));
            $this->credit->todate = date('Ymd', $stamp);
            $test_gen = $this->credit->generateTodayBonus($user_ID);
            $test_bonus = $this->credit->getBonusJewelry($user_ID);

            if($test_bonus['date'] != $this->credit->todate) isTrue(false, 'Test date erorr');

            if($test_bonus['diamond'] == $test_gen['diamond'] && $test_bonus['diamond'] >= MIN_BONUS_DIAMOND && $test_bonus['diamond'] <= MAX_BONUS_DIAMOND ) {
                self:self::assertTrue(true);
            } else {
                self::assertTrue(false, "Test bonus gen error. diamond");

            }
            if($test_bonus['gold'] == $test_gen['gold'] && $test_bonus['gold'] >= MIN_BONUS_GOLD && $test_bonus['gold'] <= MAX_BONUS_GOLD ) {
                self::assertTrue(true);
            } else {
                self::assertTrue(false, "Test bonus gen error. gold");

            }
            if($test_bonus['silver'] == $test_gen['silver'] && $test_bonus['silver'] >= MIN_BONUS_SILVER && $test_bonus['silver'] <= MAX_BONUS_SILVER ) {
                self::assertTrue(true);
            } else {
                self::assertTrue(false, "Test bonus gen error. Silver");

            }
        }
    }


/// Update bonus jewelry test
    public function testUpdateBonusJewelryTest() {
        $this->credit->todate = 20191212;
        $user_ID = $this->user_ID;
        $genBonus = $this->credit->generateTodayBonus($user_ID);
        $this->credit->updateBonusJewelry($this->user_ID, 'diamond', 31);
        $gotBonus = $this->credit->getBonusJewelry($user_ID);
        \PHPUnit\Framework\assertTrue($gotBonus['date'] == 20191212, "date: $gotBonus[date]");
        \PHPUnit\Framework\assertTrue($gotBonus['diamond'] == 31, "Update jewelry test: {$gotBonus['diamond']} == 31");
    }


    /**
     * give jewelry to she
     */
    public function testGiveJewelryToShe() {
        wp_set_current_user($this->he_3);

        // bonus
        $before = $this->credit->generateTodayBonus($this->he_3);
        self::assertTrue($before['date'] == date('Ymd'), 'Date should be today');
        $after = $this->credit->giveJewelry(['jewelry' => 'silver', 'count' => 1, 'user_ID' => $this->other_ID]);
//        print_r($before);
//        print_r($after);
        self::assertTrue(is_array($after), "Give 1 silver to user {$this->other_ID}");
        self::assertTrue($before[SILVER] == $after['bonus_silver']+1);

    }


    /**
     * Add paid jewelry
     */
    public function testAddAndGiveJewelry() {
        $this->clearTestTables();
        $this->credit->addJewelry($this->he_3, 0, 1, 0, 'test');
        $re = $this->credit->giveJewelry(['jewelry' => 'gold', 'count' => 1, 'user_ID' => $this->other_ID]);
        self::assertSame($re, ERROR_EMPTY_GOLD_ITEM);

        $re = $this->credit->giveJewelry(['jewelry' => 'gold', 'count' => 1, 'user_ID' => $this->you_5, 'item' => 'bag']);
        self::assertTrue(is_array($re), "Give 1 gold to user $this->you_5");
        self::assertTrue($re['diamond'] == 0 && $re['gold'] == 0 && $re['silver'] == 0, 'Give 1 gold to user $this->you_5 and no more jewelry left.');


        /// Give many silver from he to her
        $this->credit->updateJewelry($this->he_3, 'silver', 12);
        $re = $this->credit->giveJewelry(['user_ID'=>$this->she_4, 'jewelry' => 'silver', 'count' => 12]);
        self::assertTrue($re['diamond'] == 0 && $re['gold'] == 0 && $re['silver'] == 0, 'Give 12 silver to user $this->you_5 and no more jewelry left.');
    }

    /**
     * Cannot send jewelry to himself
     */
    public function testCannotSendJewelryHimself() {
        clearDatabase();
        wp_set_current_user($this->male);
        $this->credit->generateTodayBonus($this->male);
        $re = $this->credit->giveJewelry(['user_ID'=>$this->male, 'jewelry' => SILVER, 'count'=>1]);
        self::assertSame($re, ERROR_TRANSFER_MYSELF, ERROR_TRANSFER_MYSELF);
    }

    /**
     * 같은 성별(남자끼리, 여자끼리) 보석 전송 불가.
     */
    public function testGender() {
        clearDatabase();
        wp_set_current_user($this->male);
        $this->credit->generateTodayBonus($this->male);
        $re = $this->credit->giveJewelry(['user_ID'=>$this->male2, 'jewelry' => SILVER, 'count'=>1]);
        self::assertSame($re, ERROR_TRANSFER_SAME_GENDER, ERROR_TRANSFER_SAME_GENDER);

        $re = $this->credit->giveJewelry(['user_ID'=>$this->female, 'jewelry' => SILVER, 'count'=>1]);
        self::assertTrue(is_array($re), '같은 성별 전송 테스트');
    }


    public function testRaceAndHistory() {

/// Give jewelry test and check history.
        clearDatabase();
        wp_set_current_user($this->me_6);
// 테스트 시작 할 때, 유료 보석
        $this->credit->addJewelry($this->me_6, 100, 200, 300, REASON_TEST);

/// Diamond
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'diamond', 'count' => 5]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'diamond', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'diamond', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'diamond', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'diamond', 'count' => 8]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'diamond', 'count' => 80]);

/// Over hit
        $re = $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'diamond', 'count' => 10]);
        self::assertSame($re, ERROR_NOT_ENOUGH_JEWELRY, "Giving diamonds over 100.");


/// Giving Silver
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'silver', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'silver', 'count' => 100]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'silver', 'count' => 99]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'silver', 'count' => 99]);
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'silver', 'count' => 1]);

/// Over hit
        $re = $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'silver', 'count' => 1]);
        self::assertSame($re, ERROR_NOT_ENOUGH_JEWELRY, "Giving silvers over 300.");


/// Over hit
        $re = $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'silver', 'count' => 0]);
        self::assertSame($re, ERROR_EMPTY_COUNT, "Giving 0 silver.");



/// Giving Golds
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'gold', 'count' => 10, 'item' => JEWELRY_ITEM_WATCH]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'gold', 'count' => 20, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'gold', 'count' => 30, 'item' => JEWELRY_ITEM_RING]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'gold', 'count' => 40, 'item' => JEWELRY_ITEM_BAG]);
        $after = $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'gold', 'count' => 50, 'item' => JEWELRY_ITEM_BAG]);

//// 제한 걸림.
        $re = $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'gold', 'count' => 51, 'item' => JEWELRY_ITEM_BAG]);
        self::assertSame($re, ERROR_NOT_ENOUGH_JEWELRY, "골드 전송 제한. 50개 남았는데. 51개 전송 시도.");

// 무료 보너스 생성. 그러면 제한이 풀림.
        $generatedBonus = $this->credit->generateTodayBonus($this->me_6);

// 금이 50개 남았는데, 무료 보석 보너스를 타서, 금을 더 추가한 후, 전송
        $re = $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'gold', 'count' => 51, 'item' => JEWELRY_ITEM_BAG]);
        self::isTrue(is_array($re), "보석 보너스 추가하여, 골드 전송 200 개 이상 전송.");


// 더 많이 추천. 단, 보석이 모자라 에러가 날 수 있으나, 결과에는 영향이 미치지 않으며, 로그 합산도 일치해야 함.
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'diamond', 'count' => 1, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'silver', 'count' => 3]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'gold', 'count' => 4, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'silver', 'count' => 14]);

        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'silver', 'count' => 5]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'gold', 'count' => 6, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'diamond', 'count' => 12]);

        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'silver', 'count' => 5]);
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'gold', 'count' => 6, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'diamond', 'count' => 7]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'silver', 'count' => 8]);


        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'diamond', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'diamond', 'count' => 2]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'diamond', 'count' => 5]);
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'diamond', 'count' => 3]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'diamond', 'count' => 7]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'diamond', 'count' => 8]);


        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'silver', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'silver', 'count' => 1]);
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'silver', 'count' => 6]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'silver', 'count' => 7]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'silver', 'count' => 32]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'silver', 'count' => 5]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'silver', 'count' => 12]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'silver', 'count' => 11]);
        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'silver', 'count' => 7]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'silver', 'count' => 9]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'silver', 'count' => 3]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'silver', 'count' => 5]);



        $this->credit->giveJewelry(['user_ID' => $this->you_5, 'jewelry' => 'gold', 'count' => 1, 'item' => JEWELRY_ITEM_RING]);
        $this->credit->giveJewelry(['user_ID' => $this->he_3, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_WATCH]);
        $this->credit->giveJewelry(['user_ID' => $this->she_4, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->jenny, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->other_ID, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
        $this->credit->giveJewelry(['user_ID' => $this->user_ID, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);




        $logs = $this->credit->getJewelryLogs($this->me_6, REASON_SEND);
        self::assertTrue(count($logs)>0, "No of logs: " . count($logs));
        $dec = $this->credit->_empty_jewelry(); // ['diamond' => 0, 'gold'=> 0, 'silver' => 0];
        $bonus = $this->credit->_empty_jewelry(); // ['diamond' => 0, 'gold'=> 0, 'silver' => 0];
        foreach($logs as $log) {
            if ( $log['before_diamond'] + $log['before_bonus_diamond'] - $log['apply_diamond'] == ($log['after_diamond'] + $log['after_bonus_diamond']) ) {
                self::assertTrue(true);
            } else {
                self::assertTrue(false, 'log error');
            }
            if ( $log['before_gold'] + $log['before_bonus_gold'] - $log['apply_gold'] == ($log['after_gold'] + $log['after_bonus_gold']) ) {
                self::assertTrue(true);
            } else {
                self::assertTrue(false, 'log error');
            }
            if ( $log['before_silver'] + $log['before_bonus_silver'] - $log['apply_silver'] == ($log['after_silver'] + $log['after_bonus_silver']) ) {
                self::assertTrue(true);
            } else {
                self::assertTrue(false, 'log error');
            }
            $dec['diamond'] += $log['apply_diamond'];
            $dec['gold'] += $log['apply_gold'];
            $dec['silver'] += $log['apply_silver'];

            // 무료 보석이 사용되었으면, 무료 보석 수 카운트
            if ( $log['apply_diamond']) $bonus['diamond'] += $log['bonus_count'];
            if ( $log['apply_gold']) $bonus['gold'] += $log['bonus_count'];
            if ( $log['apply_silver']) $bonus['silver'] += $log['bonus_count'];
        }


        $credit = $this->credit->getMyCreditJewelry();
        $bonus = $this->credit->getMyBonusJewelry() ?? $this->credit->_empty_jewelry();
        $current = [
            DIAMOND => $credit[DIAMOND] + $bonus[DIAMOND],
            GOLD => $credit[GOLD] + $bonus[GOLD],
            SILVER => $credit[SILVER] + $bonus[SILVER],
        ];
        $original = [
            DIAMOND => 100 + $generatedBonus[DIAMOND],
            GOLD => 200 + $generatedBonus[GOLD],
            SILVER => 300 + $generatedBonus[SILVER],
        ];
        self::assertTrue($original[DIAMOND] == $dec[DIAMOND] + $current[DIAMOND], 'diamond log match');
        self::assertTrue($original[GOLD] == $dec[GOLD] + $current[GOLD], 'gold log match');
        self::assertTrue($original[SILVER] == $dec[SILVER] + $current[SILVER], 'silver log match');

    }
}