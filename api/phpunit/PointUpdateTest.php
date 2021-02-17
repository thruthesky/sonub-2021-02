<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../../../wp-load.php");

final class PointUpdateTest extends TestCase
{
    private $A = 1;
    private $B = 2;
    private $C = 3;


    /// 사용자 A, B, C 의 포인트 0으로 처리, 모든 포인트 설정 0으로 처리, 기록 삭제
    private function clear() {
        global $wpdb;
        $wpdb->query("truncate api_point_history");
        set_user_point($this->A, 0);
        set_user_point($this->B, 0);
        set_user_point($this->C, 0);
        update_option(POINT_REGISTER, 0);
        update_option(POINT_LOGIN, 0);
        update_option(POINT_LIKE, 0);
        update_option(POINT_DISLIKE, 0);
        update_option(POINT_LIKE_DEDUCTION, 0);
        update_option(POINT_DISLIKE_DEDUCTION, 0);
        update_option(POINT_LIKE_HOUR_LIMIT, 0);
        update_option(POINT_LIKE_COUNT_LIMIT, 0);
        update_option(POINT_LIKE_DAILY_LIMIT, 0);
    }

    public function testGetInput(): void
    {
        $this->clear();
        $re = point_update(['point' => 3, 'from_user_ID' => 50000, 'to_user_ID' => 60000]);
        self::assertTrue($re === ERROR_REASON_NOT_SET, $re);

        $re = point_update(['point' => 3, REASON => POINT_TEST]);
        self::assertTrue($re === ERROR_FROM_USER_ID_NOT_SET, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 50000, REASON => POINT_TEST]);
        self::assertTrue($re === ERROR_TO_USER_ID_NOT_SET, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 50000, 'to_user_ID' => 60000, 'reason' => POINT_TEST]);
        self::assertTrue($re === ERROR_FROM_USER_NOT_EXISTS, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 50000, 'to_user_ID' => 60000, 'reason' => POINT_TEST]);
        self::assertTrue($re === ERROR_FROM_USER_NOT_EXISTS, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 1, 'to_user_ID' => 60000, 'reason' => POINT_TEST]);
        self::assertTrue($re === ERROR_TO_USER_NOT_EXISTS, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 1, 'to_user_ID' => 2, 'reason' => 'wrong']);
        self::assertTrue($re === ERROR_WRONG_POINT_REASON, 'RE: $re');
    }

    public function testAdminUpdatePoint(): void {


        /// ERROR TEST
        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 2,
            'reason' => POINT_UPDATE,
        ]);
        self::assertTrue($re === ERROR_PERMISSION_DENIED, "expect: ERROR_PERMISSION_DENIED, re: $re");

        wp_set_current_user(1);
        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 1,
            'reason' => POINT_UPDATE,
        ]);
        self::assertTrue($re === ERROR_POINT_IS_NOT_SET, "re: $re");

        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 1,
            'reason' => POINT_UPDATE,
            POINT => -5,
            'post_ID' => 1]);
        self::assertTrue($re === ERROR_POINT_CANNOT_BE_SET_LESS_THAN_ZERO, "re: $re");


        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 1,
            REASON => POINT_UPDATE,
            POINT => 10,
            'post_ID' => 5,
        ]);
        self::assertTrue($re === ERROR_WRONG_INPUT, "expect: ERROR_WRONG_INPUT, re: $re");


        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 1,
            REASON => POINT_UPDATE,
            POINT => 0,
        ]);
        self::assertTrue($re === ERROR_POINT_IS_NOT_SET, "expect: ERROR_POINT_IS_NOT_SET, re: $re");
        wp_set_current_user(0);


        /// SUCCESS TEST
        point_reset($this->B);
        wp_set_current_user(1);
        $re = point_update([
            'from_user_ID' => wp_get_current_user()->ID,
            'to_user_ID' => $this->B,
            REASON => POINT_UPDATE,
            POINT => 10,
        ]);
        self::assertTrue(get_user_point($this->B) === 10, "re: $re");
    }

    public function testWithoutPointDeduction() {
        $this->clear();
        update_option(POINT_LIKE, 100);
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) == 0, "");
        self::assertTrue(get_user_point($this->C) == get_option(POINT_LIKE), "");
    }

    public function testDisikeWith00(): void {
        $this->clear();
        update_option(POINT_DISLIKE, 0);
        update_option(POINT_DISLIKE_DEDUCTION, 0);
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) == 0, "");
        self::assertTrue(get_user_point($this->C) == get_option(POINT_LIKE), "");
    }

    public function testLikeHimself(): void {

        /// input test
        $re = point_update(['from_user_ID' => 1, 'to_user_ID' => 1, 'reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_CANNOT_LIKE_OWN_POST, $re);

    }

    public function testLikeLackOfPoint(): void {
        $this->clear();
        update_option(POINT_LIKE, 100);
        update_option(POINT_DISLIKE, 50);
        update_option(POINT_LIKE_DEDUCTION, -20);
        update_option(POINT_DISLIKE_DEDUCTION, -10);


        /// 포인트가 모자라서 추천이 안됨
        $re = point_update([FROM_USER_ID => $this->A, TO_USER_ID => $this->B, REASON => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);
    }

    public function testLikePointCheck(): void {

        $this->clear();
        update_option(POINT_LIKE, 100);
        update_option(POINT_DISLIKE, 50);
        update_option(POINT_LIKE_DEDUCTION, -20);
        update_option(POINT_DISLIKE_DEDUCTION, -10);

        // 추천
        set_user_point($this->A, 1000);
        $re = point_update([FROM_USER_ID => $this->A, TO_USER_ID => $this->B, REASON => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");

        // 추천 후 나의 포인트 감소 확인
        self::assertTrue( get_user_point($this->A) == 1000 + get_option(POINT_LIKE_DEDUCTION), "A point after deduction:" . get_user_point($this->A) );
        // 추천 후 상대방의 포인트 증가 확인
        self::assertTrue( get_user_point($this->B) == get_option(POINT_LIKE), "B point after like: " . get_user_point($this->B));
    }

    public function testDislikeLackOfPoint(): void {
        $this->clear();
        update_option(POINT_DISLIKE, -100);
        update_option(POINT_DISLIKE_DEDUCTION, -15);

        $re = point_update([FROM_USER_ID => $this->B, TO_USER_ID => $this->C, REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);

        set_user_point($this->B, 14);
        $re = point_update([FROM_USER_ID => $this->B, TO_USER_ID => $this->C, REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, "RE: $re");
    }

    public function testDislike(): void {
        $this->clear();
        point_reset($this->B);
        point_reset($this->C);
        update_option(POINT_DISLIKE, -100);
        update_option(POINT_DISLIKE_DEDUCTION, -15);
        set_user_point($this->B, 15);
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) === 0, "RE: $re");
        self::assertTrue(get_user_point($this->C) === 0, "C Point: " . get_user_point($this->C));

        // 포인트가 15 있었는데, 한번 dislike 하고, 그 다음에 포인트가 0이 된 후, dislike 할 때 deduction 포인트가 없음.
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, "RE: $re");


        // 포인트 재 충전하고, C 포인트를 101 로 주고, 비추천하면, C 포인트는 1이 남아야 함.
        set_user_point($this->B, 30);
        set_user_point($this->C, 101);
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue(get_user_point($this->B) === 15, "RE: $re");
        self::assertTrue(get_user_point($this->C) === 1, "C Point: " . get_user_point($this->C));


        // 다시 비추 하면 C 포인트는 0 이 되어야 함
        $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) === 0, "RE: $re");
        self::assertTrue(get_user_point($this->C) === 0, "C Point: " . get_user_point($this->C));
    }

    public function testRegisterPoint(): void {
        $this->clear();
        update_option(POINT_REGISTER, 1000);

        $email = time() . "@point-test.com";
        $profile = login_or_register(['user_email' => $email, 'user_pass' => $email]);
        self::assertTrue( get_user_point($profile['ID']) == get_option(POINT_REGISTER), "$email's point: " . get_user_point($profile['ID']));
    }

    public function testLoginPoint(): void {
        update_option(POINT_REGISTER, 1000);
        update_option(POINT_LOGIN, 150);
        $email = time() . "@login-point-test.com";
        $profile = login_or_register(['user_email' => $email, 'user_pass' => $email]);
        self::assertTrue( get_user_point($profile['ID']) == get_option(POINT_REGISTER), "$email's point: " . get_user_point($profile['ID']));

        $profile = login_or_register(['user_email' => $email, 'user_pass' => $email]);
        self::assertTrue( get_user_point($profile['ID']) == (get_option(POINT_REGISTER) + get_option(POINT_LOGIN)), "$email's point: " . get_user_point($profile['ID']));

        $profile = login_or_register(['user_email' => $email, 'user_pass' => $email]);
        self::assertTrue( get_user_point($profile['ID']) == (get_option(POINT_REGISTER) + get_option(POINT_LOGIN)), "$email's point: " . get_user_point($profile['ID']));
    }

//
//
//
//    public function testLikeLimit() {
//        $this->clear();
//        update_option(POINT_LIKE, 100);
//        update_option(POINT_LIKE_DEDUCTION, -15);
//        update_option(POINT_DISLIKE, -100);
//        update_option(POINT_DISLIKE_DEDUCTION, -15);
//        set_user_point($this->B, 10000);
//        set_user_point($this->C, 10000);
//
//
//        for($i=0; $i<99; $i++) {
//            $re = point_update(['from_user_ID' => $this->B, 'to_user_ID' => $this->C, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
//            self::assertTrue($re > 0, '99 times test');
//        }
//        self::assertTrue(get_user_point($this->B) == 10000 - (15 * 99), 'C point is : ' . (10000 - (15 * 99)));
//        self::assertTrue(get_user_point($this->C) == 100, 'C point is 100');
//
//
//    }

}



