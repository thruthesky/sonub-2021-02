<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR .'/defines.php');
require_once(V3_DIR .'/config.php');
require_once(V3_DIR . '/functions.php');
require_once(V3_DIR . '/router.php');
require_once(V3_DIR . '/test.helper.php');

date_default_timezone_set('Asia/Seoul');

/// Clear test data



clearDatabase();

/// Input tests
$re = giveJewelry([]);
isError($re, ERROR_EMPTY_JEWELRY);

$re = giveJewelry(['jewelry']);
isError($re, ERROR_EMPTY_JEWELRY);

$re = giveJewelry(['jewelry'=>'ruby']);
isError($re, ERROR_WRONG_JEWELRY);

$re = giveJewelry(['jewelry'=>'diamond']);
isError($re, ERROR_EMPTY_COUNT);

$re = giveJewelry(['jewelry'=>'diamond', 'count' => 'abc']);
isError($re, ERROR_WRONG_COUNT);

$re = giveJewelry(['jewelry'=>'diamond', 'count' => '123']);
isError($re, ERROR_EMPTY_USER_ID);

$re = giveJewelry(['jewelry'=>'diamond', 'count' => 12, 'user_ID' => 99912345]);
isError($re, ERROR_USER_NOT_FOUND);

$re = giveJewelry(['jewelry'=>'diamond', 'count' => 12, 'user_ID' => 1]);
isError($re, ERROR_LOGIN_FIRST);

$re = updateBonusJewelry(0, 'gold', 1);
isError($re, ERROR_EMPTY_USER_ID);

$re = updateBonusJewelry(1, 'ruby', 1);
isError($re, ERROR_WRONG_JEWELRY);

/// Login
wp_set_current_user(1);



/// Generate daily bonus
///
$user_ID = 1;
$other_ID = 2;
$he = $he_3 = 3;
$she = $she_4 = 4;
$you = $you_5 = 5;
$me = $me_6 = 6;
$jenny = 7;
$male = 8;
$female = 9;
$male2 = 10;


update_user_meta($male, 'gender', 'M');
update_user_meta($female, 'gender', 'F');
update_user_meta($male2, 'gender', 'M');



$re = updateBonusJewelry($user_ID, 'gold', 1);
isError($re, ERROR_DAILY_BONUS_NOT_GENERATED,  "updateBonusJewelry(1, 'gold', 1)");

$bonus = generateDailyBonus($user_ID);
updateBonusJewelry($user_ID, 'gold', 1);
$after_update = getMyBonusJewelry();
isTrue($after_update['diamond'] == $bonus['diamond'], 'diamond did not change');
isTrue($after_update['gold'] == 1, 'gold 1');
isTrue($after_update['silver'] == $bonus['silver'], 'silver did not change');

/// Generate daily bonus by changing date and test the bonus jewelry amount.

for( $i = 0; $i <= 30; $i ++ ) {
    $stamp = mktime(0, 0, 0, date('m'), date('d') - $i - 3, date('Y'));
    $testDate = date('Ymd', $stamp);
    setTodate($testDate);
    $test_gen = generateDailyBonus($user_ID);
    $test_bonus = getBonusJewelry($user_ID);
    if($test_bonus['date'] != $testDate) isTrue(false, 'Test date erorr');

    if($test_bonus['diamond'] == $test_gen['diamond'] && $test_bonus['diamond'] >= MIN_DIAMOND && $test_bonus['diamond'] <= MAX_DIAMOND ) {
    } else {
        isTrue(false, "Test bonus gen error. diamond");

    }
    if($test_bonus['gold'] == $test_gen['gold'] && $test_bonus['gold'] >= MIN_GOLD && $test_bonus['gold'] <= MAX_GOLD ) {
    } else {
        isTrue(false, "Test bonus gen error. gold");

    }
    if($test_bonus['silver'] == $test_gen['silver'] && $test_bonus['silver'] >= MIN_SILVER && $test_bonus['silver'] <= MAX_SILVER ) {
    } else {
        isTrue(false, "Test bonus gen error. Silver");

    }
}



/// Update bonus jewelry test
setTodate(20191212);
$bonus = generateDailyBonus($user_ID);
updateBonusJewelry($user_ID, 'diamond', 31);
$b3 = getBonusJewelry($user_ID);
isTrue($b3['diamond'] == 31, "Update jewelry test: {$b3['diamond']} == 31");


/// Give silver to user ID: she
wp_set_current_user($he_3);
generateDailyBonus($he_3);
$re = giveJewelry(['jewelry' => 'silver', 'count' => 1, 'user_ID' => $other_ID]);
isSuccess($re, "Give 1 silver to user $other_ID");


/// Give gold from he to her
clearDatabase();
addJewelry($he_3, 0, 1, 0, 'test');
$re = giveJewelry(['jewelry' => 'gold', 'count' => 1, 'user_ID' => $other_ID]);
isError($re, ERROR_EMPTY_GOLD_ITEM);


$re = giveJewelry(['jewelry' => 'gold', 'count' => 1, 'user_ID' => $you_5, 'item' => 'bag']);
isSuccess($re, "Give 1 gold to user $you");
isTrue($re['diamond'] == 0 && $re['gold'] == 0 && $re['silver'] == 0, 'Give 1 gold to user $you and no more jewelry left.');

/// Give many silver from he to her
updateJewelry($he, 'silver', 12);
$re = giveJewelry(['user_ID'=>$she, 'jewelry' => 'silver', 'count' => 12]);
isTrue($re['diamond'] == 0 && $re['gold'] == 0 && $re['silver'] == 0, 'Give 12 silver to user $you and no more jewelry left.');


/// 자기 자신에게는 보석 전송 불가
clearDatabase();
wp_set_current_user($male);
generateDailyBonus($male);
$re = giveJewelry(['user_ID'=>$male, 'jewelry' => SILVER, 'count'=>1]);
isError($re, ERROR_TRANSFER_MYSELF, ERROR_TRANSFER_MYSELF);

/// 같은 성별(남자끼리, 여자끼리) 보석 전송 불가.
$re = giveJewelry(['user_ID'=>$male2, 'jewelry' => SILVER, 'count'=>1]);
isError($re, ERROR_TRANSFER_SAME_GENDER, ERROR_TRANSFER_SAME_GENDER);

$re = giveJewelry(['user_ID'=>$female, 'jewelry' => SILVER, 'count'=>1]);
isSuccess($re, '같은 성별 전송 테스트');



/// Give jewelry test and check history.
clearDatabase();
wp_set_current_user($me);
// 테스트 시작 할 때, 유료 보석
addJewelry($me, 100, 200, 300, REASON_TEST);

/// Diamond
giveJewelry(['user_ID' => $you, 'jewelry' => 'diamond', 'count' => 5]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'diamond', 'count' => 1]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'diamond', 'count' => 1]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'diamond', 'count' => 1]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'diamond', 'count' => 8]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'diamond', 'count' => 80]);

/// Over hit
$re = giveJewelry(['user_ID' => $jenny, 'jewelry' => 'diamond', 'count' => 10]);
isError($re, ERROR_NOT_ENOUGH_JEWELRY, "Giving diamonds over 100.");


/// Giving Silver
giveJewelry(['user_ID' => $he, 'jewelry' => 'silver', 'count' => 1]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'silver', 'count' => 100]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'silver', 'count' => 99]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'silver', 'count' => 99]);
giveJewelry(['user_ID' => $you, 'jewelry' => 'silver', 'count' => 1]);

/// Over hit
$re = giveJewelry(['user_ID' => $you, 'jewelry' => 'silver', 'count' => 1]);
isError($re, ERROR_NOT_ENOUGH_JEWELRY, "Giving silvers over 300.");


/// Over hit
$re = giveJewelry(['user_ID' => $you, 'jewelry' => 'silver', 'count' => 0]);
isError($re, ERROR_EMPTY_COUNT, "Giving 0 silver.");



/// Giving Golds
giveJewelry(['user_ID' => $he, 'jewelry' => 'gold', 'count' => 10, 'item' => JEWELRY_ITEM_WATCH]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'gold', 'count' => 20, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'gold', 'count' => 30, 'item' => JEWELRY_ITEM_RING]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'gold', 'count' => 40, 'item' => JEWELRY_ITEM_BAG]);
$after = giveJewelry(['user_ID' => $you, 'jewelry' => 'gold', 'count' => 50, 'item' => JEWELRY_ITEM_BAG]);

//// 제한 걸림.
$re = giveJewelry(['user_ID' => $you, 'jewelry' => 'gold', 'count' => 51, 'item' => JEWELRY_ITEM_BAG]);
isError($re, ERROR_NOT_ENOUGH_JEWELRY, "골드 전송 제한. 50개 남았는데. 51개 전송 시도.");

// 무료 보너스 생성. 그러면 제한이 풀림.
$generatedBonus = generateDailyBonus($me);

// 금이 50개 남았는데, 무료 보석 보너스를 타서, 금을 더 추가한 후, 전송
$re = giveJewelry(['user_ID' => $you, 'jewelry' => 'gold', 'count' => 51, 'item' => JEWELRY_ITEM_BAG]);
isSuccess($re, "보석 보너스 추가하여, 골드 전송 200 개 이상 전송.");


// 더 많이 추천. 단, 보석이 모자라 에러가 날 수 있으나, 결과에는 영향이 미치지 않으며, 로그 합산도 일치해야 함.
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'diamond', 'count' => 1, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'silver', 'count' => 3]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'gold', 'count' => 4, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'silver', 'count' => 14]);

giveJewelry(['user_ID' => $jenny, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'silver', 'count' => 5]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'gold', 'count' => 6, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'diamond', 'count' => 12]);

giveJewelry(['user_ID' => $he, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'silver', 'count' => 5]);
giveJewelry(['user_ID' => $you, 'jewelry' => 'gold', 'count' => 6, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'diamond', 'count' => 7]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'silver', 'count' => 8]);


giveJewelry(['user_ID' => $he, 'jewelry' => 'diamond', 'count' => 1]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'diamond', 'count' => 2]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'diamond', 'count' => 5]);
giveJewelry(['user_ID' => $you, 'jewelry' => 'diamond', 'count' => 3]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'diamond', 'count' => 7]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'diamond', 'count' => 8]);


giveJewelry(['user_ID' => $he, 'jewelry' => 'silver', 'count' => 1]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'silver', 'count' => 1]);
giveJewelry(['user_ID' => $you, 'jewelry' => 'silver', 'count' => 6]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'silver', 'count' => 7]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'silver', 'count' => 32]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'silver', 'count' => 5]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'silver', 'count' => 12]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'silver', 'count' => 11]);
giveJewelry(['user_ID' => $you, 'jewelry' => 'silver', 'count' => 7]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'silver', 'count' => 9]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'silver', 'count' => 3]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'silver', 'count' => 5]);



giveJewelry(['user_ID' => $you, 'jewelry' => 'gold', 'count' => 1, 'item' => JEWELRY_ITEM_RING]);
giveJewelry(['user_ID' => $he, 'jewelry' => 'gold', 'count' => 2, 'item' => JEWELRY_ITEM_WATCH]);
giveJewelry(['user_ID' => $she, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $jenny, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $other_ID, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);
giveJewelry(['user_ID' => $user_ID, 'jewelry' => 'gold', 'count' => 3, 'item' => JEWELRY_ITEM_BAG]);




$logs = getJewelryLogs($me, REASON_SEND);
isTrue(count($logs), "No of logs: " . count($logs));
$dec = ['diamond' => 0, 'gold'=> 0, 'silver' => 0];
$bonus = ['diamond' => 0, 'gold'=> 0, 'silver' => 0];
foreach($logs as $log) {
    if ( $log['before_diamond'] + $log['before_bonus_diamond'] - $log['apply_diamond'] == ($log['after_diamond'] + $log['after_bonus_diamond']) ) {} else { isTrue(false, 'log error'); }
    if ( $log['before_gold'] + $log['before_bonus_gold'] - $log['apply_gold'] == ($log['after_gold'] + $log['after_bonus_gold']) ) {} else { isTrue(false, 'log error'); }
    if ( $log['before_silver'] + $log['before_bonus_silver'] - $log['apply_silver'] == ($log['after_silver'] + $log['after_bonus_silver']) ) {} else {
        print_r($log);
        isTrue(false, 'log error');
    }
    $dec['diamond'] += $log['apply_diamond'];
    $dec['gold'] += $log['apply_gold'];
    $dec['silver'] += $log['apply_silver'];

    // 무료 보석이 사용되었으면, 무료 보석 수 카운트
    if ( $log['apply_diamond']) $bonus['diamond'] += $log['bonus_count'];
    if ( $log['apply_gold']) $bonus['gold'] += $log['bonus_count'];
    if ( $log['apply_silver']) $bonus['silver'] += $log['bonus_count'];
}


$credit = getMyCreditJewelry();
$bonus = getMyBonusJewelry() ?? $this->_empty_jewelry();
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
isTrue($original[DIAMOND] == $dec[DIAMOND] + $current[DIAMOND], 'diamond log match');
isTrue($original[GOLD] == $dec[GOLD] + $current[GOLD], 'gold log match');
isTrue($original[SILVER] == $dec[SILVER] + $current[SILVER], 'silver log match');

displayTestSummary();
