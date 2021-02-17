<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../../../wp-load.php");

final class PointUpdateTest extends TestCase
{
    public function testGetInput(): void
    {
        $re = point_update(['point' => 3]);
        self::assertTrue($re === ERROR_FROM_USER_ID_NOT_SET, $re);
        $re = point_update(['point' => 3, 'from_user_ID' => 5000]);
        self::assertTrue($re === ERROR_TO_USER_ID_NOT_SET, $re);
        $re = point_update(['point' => 3, 'from_user_ID' => 5000, 'to_user_ID' => 6000]);
        self::assertTrue($re === ERROR_REASON_NOT_SET, $re);
        $re = point_update(['point' => 3, 'from_user_ID' => 5000, 'to_user_ID' => 6000, 'reason' => 'test']);
        self::assertTrue($re === ERROR_FROM_USER_NOT_EXISTS, $re);


        $re = point_update(['point' => 3, 'from_user_ID' => 1, 'to_user_ID' => 6000, 'reason' => 'test']);
        self::assertTrue($re === ERROR_TO_USER_NOT_EXISTS, $re);

        $re = point_update(['point' => 3, 'from_user_ID' => 1, 'to_user_ID' => 2, 'reason' => 'test']);
        self::assertTrue(api_error($re) === false, '');

        $re = point_update(['from_user_ID' => 1, 'to_user_ID' => 1, 'reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_CANNOT_LIKE_OWN_POST, $re);


        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 2,
            'reason' => POINT_UPDATE,
            ]);
        self::assertTrue($re === ERROR_PERMISSION_DENIED, "re: $re");


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
        self::assertTrue($re === ERROR_WRONG_INPUT, "re: $re");


        $re = point_update([
            'from_user_ID' => 1,
            'to_user_ID' => 1,
            REASON => POINT_UPDATE,
            POINT => 0,
        ]);
        self::assertTrue($re === ERROR_POINT_IS_NOT_SET, "re: $re");



        wp_set_current_user(0);



//        $re = point_update(['from_user_ID' => 1, 'to_user_ID' => 1, 'reason' => POINT_LIKE]);
//        self::assertTrue($re === ERROR_POST_ID, $re);
    }


    public function testAdminUpdatePoint(): void {
        $to_user_ID = 2;
        point_reset($to_user_ID);
        wp_set_current_user(1);
        $re = point_update([
            'from_user_ID' => wp_get_current_user()->ID,
            'to_user_ID' => $to_user_ID,
            REASON => POINT_UPDATE,
            POINT => 10,
        ]);
        self::assertTrue(get_user_point($to_user_ID) === 10, "re: $re");
    }

    public function testLikeLackOfPoint(): void {
        point_reset(1);
        update_option(POINT_REGISTER, 0);
        update_option(POINT_LOGIN, 0);
        update_option(POINT_LIKE, 1);
        update_option(POINT_DISLIKE, 1);
        update_option(POINT_LIKE_DEDUCTION, -1);
        update_option(POINT_DISLIKE_DEDUCTION, -1);
        update_option(POINT_LIKE_HOUR_LIMIT, 0);
        update_option(POINT_LIKE_COUNT_LIMIT, 0);
        $re = point_update(['from_user_ID' => 1, 'to_user_ID' => 2, 'reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);
    }


    public function testDislikeLackOfPoint(): void {
        point_reset(1);
        update_option(POINT_REGISTER, 0);
        update_option(POINT_LOGIN, 0);
        update_option(POINT_LIKE, 1);
        update_option(POINT_DISLIKE, -1);
        update_option(POINT_LIKE_DEDUCTION, -1);
        update_option(POINT_DISLIKE_DEDUCTION, -1);
        update_option(POINT_LIKE_HOUR_LIMIT, 0);
        update_option(POINT_LIKE_COUNT_LIMIT, 0);
        $re = point_update(['from_user_ID' => 1, 'to_user_ID' => 2, 'reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);
    }



//    public function testTransfer(): void {
//
//    }
}



