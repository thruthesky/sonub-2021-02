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
        set_phpunit_mode(true);
        global $wpdb;
        $wpdb->query("truncate api_point_history");
        $wpdb->query("truncate api_forum_vote_history");
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
        update_option(POINT_LIKE_HOUR_LIMIT_COUNT, 0);
        update_option(POINT_LIKE_DAILY_LIMIT_COUNT, 0);

        $cat = get_category_by_slug('point_test');
        if ( !$cat ) {
            wp_insert_category( ['cat_name'=> 'point_test', 'category_description'=> 'point_test' ], true );
            $cat = get_category_by_slug('point_test');
        }
//        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_POST_CREATE, 'value' => 0]);
//        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_COMMENT_CREATE, 'value' => 0]);
//        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_POST_DELETE, 'value' => 0]);
//        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_COMMENT_DELETE, 'value' => 0]);
        update_category(['category' => 'point_test', POINT_DAILY_LIMIT_COUNT => 0, POINT_HOUR_LIMIT => 0, POINT_HOUR_LIMIT_COUNT => 0]);
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
        wp_set_current_user($this->B);
        $re = point_update(['reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) == 0, "");
        $post = get_post(1);
        self::assertTrue(get_user_point($post->ID) == get_option(POINT_LIKE), "");
    }

    public function testDisikeWith00(): void {
        $this->clear();
        update_option(POINT_DISLIKE, 0);
        update_option(POINT_DISLIKE_DEDUCTION, 0);
        wp_set_current_user($this->B);
        $re = point_update(['reason' => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) == 0, "");
        self::assertTrue(get_user_point($this->C) == get_option(POINT_LIKE), "");
    }

    public function testLikeHimself(): void {

        /// input test
        $post = get_post(1);
        wp_set_current_user($post->ID);
        $re = point_update(['reason' => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_CANNOT_LIKE_OWN_POST, $re);

    }

    public function testLikeLackOfPoint(): void {
        $this->clear();
        update_option(POINT_LIKE, 100);
        update_option(POINT_DISLIKE, 50);
        update_option(POINT_LIKE_DEDUCTION, -20);
        update_option(POINT_DISLIKE_DEDUCTION, -10);


        /// 포인트가 모자라서 추천이 안됨
        wp_set_current_user($this->B);
        $re = point_update([REASON => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);
    }

    public function testLikePointCheck(): void {

        $this->clear();
        update_option(POINT_LIKE, 100);
        update_option(POINT_DISLIKE, 50);
        update_option(POINT_LIKE_DEDUCTION, -20);
        update_option(POINT_DISLIKE_DEDUCTION, -10);

        // 추천
        set_user_point($this->B, 1000);
        wp_set_current_user($this->B);
        $re = point_update([REASON => POINT_LIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");

        // 추천 후 나의 포인트 감소 확인
        self::assertTrue( get_user_point($this->B) == 1000 + get_option(POINT_LIKE_DEDUCTION), "A point after deduction:" . get_user_point($this->B) );

        // 추천 후 상대방의 포인트 증가 확인
        $post = get_post(1);
        self::assertTrue(get_user_point($post->ID) == get_option(POINT_LIKE), "추천 후, 상대방의 포인트 변화");
    }

    public function testDislikeLackOfPoint(): void {
        $this->clear();
        update_option(POINT_DISLIKE, -100);
        update_option(POINT_DISLIKE_DEDUCTION, -15);

        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, $re);

        wp_set_current_user($this->B);
        set_user_point($this->B, 14);
        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, "RE: $re");
    }

    public function testDislike(): void {
        $this->clear();
        update_option(POINT_DISLIKE, -100);
        update_option(POINT_DISLIKE_DEDUCTION, -15);

        wp_set_current_user($this->B);
        set_user_point($this->B, 15);
        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) === 0, "RE: $re");

        // 비추천 받은 사람은 원래 0 이었는데, 비추천 받고도 0
        $post = get_post(1);
        self::assertTrue(get_user_point($post->ID) === 0, "C Point: " . get_user_point($this->C));

        // 포인트가 15 있었는데, 한번 dislike 하고, 그 다음에 포인트가 0이 된 후, dislike 할 때 deduction 포인트가 없음.
        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re === ERROR_LACK_OF_POINT, "RE: $re");


        // 포인트 재 충전하고, C 포인트를 101 로 주고, 비추천하면, C 포인트는 1이 남아야 함.
        set_user_point($this->B, 30);
        set_user_point($post->ID, 101);
        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue(get_user_point($this->B) === 15, "RE: $re");
        self::assertTrue(get_user_point($post->ID) === 1, "C Point: " . get_user_point($post->ID));


        // 다시 비추 하면 C 포인트는 0 이 되어야 함
        $re = point_update([REASON => POINT_DISLIKE, 'post_ID' => 1]);
        self::assertTrue($re > 0, "RE: $re");
        self::assertTrue(get_user_point($this->B) === 0, "RE: $re");
        self::assertTrue(get_user_point($post->ID) === 0, "ID Point: " . get_user_point($post->ID));
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

    public function testPostCreate(): void {
        $this->clear();
        update_category( ['slug' => 'point_test',
            POINT_POST_CREATE => 100,
            POINT_COMMENT_CREATE => 50,
            POINT_POST_DELETE => -80,
            POINT_COMMENT_DELETE => -40]
        );

        wp_set_current_user($this->A);

        /// 포인트가 없는 게시판에, 글 작성을 해서, 포인트가 추가 안됨
        $re = point_update(['reason' => POINT_POST_CREATE, 'post_ID' => 1]);
        self::assertTrue($re > 0);
        self::assertTrue( get_user_point($this->A) == 0, 'Point after post create is ' . get_user_point($this->A) );

        /// 글 쓰기 포인트 100 추가
        $re = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue(api_error($re) === false, "Api_error() true? " . api_error($re) === true ? 'y' : 'n');
        self::assertTrue( get_user_point($this->A) == 100, 'Point after post create is ' . get_user_point($this->A) );

        /// 글 쓰기 포인트를 1,200 으로 하고, 다시 글 쓰면 총 1,300 이 됨.
        update_category(['slug' => 'point_test', POINT_POST_CREATE => 1200]);
        $re = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue( get_user_point($this->A) == 1300, 'Point after post create is ' . get_user_point($this->A) );
    }

    /**
     * @group api
     */
    public function testCommentCreate(): void {
        $this->clear();
        update_category( ['slug' => 'point_test',
                POINT_POST_CREATE => 100,
                POINT_COMMENT_CREATE => 50,
                POINT_POST_DELETE => -80,
                POINT_COMMENT_DELETE => -40]
        );

        wp_set_current_user($this->A);
        /// 글 쓰기 포인트 100 추가
        $re = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue(api_error($re) === false, "Api_error() true? " . api_error($re) === true ? 'y' : 'n');
        self::assertTrue( get_user_point($this->A) == 100, 'Point after post create is ' . get_user_point($this->A) );

        $res = getRoute(['route'=>'forum.editComment', 'comment_post_ID' => $re['ID'], 'comment_content' => 'point test', 'session_id' => get_session_id()]);
        self::assertTrue( get_user_point($this->A) == 150, 'point of B must be 150: ' . get_user_point($this->A));
    }

    /**
     * @group api
     */
    public function testPostDeleteAndCommentDelete(): void {
        $this->clear();
        update_category( ['slug' => 'point_test',
                POINT_POST_CREATE => 100,
                POINT_COMMENT_CREATE => 50,
                POINT_POST_DELETE => -80,
                POINT_COMMENT_DELETE => -40]
        );

        wp_set_current_user($this->B);
        /// 글 쓰기 포인트 100 추가
        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue(api_error($post) === false, "post create");
        self::assertTrue( get_user_point($this->B) == 100, 'Point after post create is ' . get_user_point($this->B) );

        wp_set_current_user($this->A);
        $comment = getRoute(['route'=>'forum.editComment', 'comment_post_ID' => $post['ID'], 'comment_content' => 'point test', 'session_id' => get_session_id()]);
        self::assertTrue( get_user_point($this->A) == 50, 'Comment create ' . get_user_point($this->A));
        $re = getRoute(['route' => 'forum.deleteComment', 'comment_ID' => $comment['data']['comment_ID'], 'session_id' => get_session_id()]);
        self::assertTrue( get_user_point($this->A) == 10, 'Comment delete ' . get_user_point($this->A));


        wp_set_current_user($this->B);
        $re = getRoute(['route' => 'forum.deletePost', 'ID' => $post['ID'], 'session_id' => get_session_id()]);
        self::assertTrue( get_user_point($this->B) == 20, 'Post delete ' . get_user_point($this->B));

    }


    /**
     * @group limit
     *
     */
    public function testLikeTimeLimit(): void {
        $this->clear();

        update_option(POINT_LIKE, 100);
        update_option(POINT_LIKE_DEDUCTION, -50);
        update_option(POINT_LIKE_HOUR_LIMIT, 1);
        update_option(POINT_LIKE_HOUR_LIMIT_COUNT, 5);
        update_category( ['slug' => 'point_test',  POINT_POST_CREATE => 100] );
        update_category(['category' => 'point_test', POINT_HOUR_LIMIT => 1]);
        update_category(['category' => 'point_test', POINT_HOUR_LIMIT_COUNT => 5]);

        set_user_point($this->B, 1000);


        for( $i =0; $i < category_meta('point_test', POINT_HOUR_LIMIT_COUNT); $i ++ ) {

            // 루트로 글 쓰고
            wp_set_current_user($this->A);
            $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
            self::assertTrue(api_error($post) === false, '');

            // 일반 사용자가 추천하고
            wp_set_current_user($this->B);
            $re = api_vote(['post_ID' => $post['ID'], 'choice' => 'Y']);
        }

        self::assertTrue( get_user_point($this->A) == 1000, 'A 포인트: 1000 => 글쓰기 5번: 500점. 추천 5번 받음 500점: ' . get_user_point($this->A));
        self::assertTrue( get_user_point($this->B) == 1000 + (5 * get_option(POINT_LIKE_DEDUCTION)), 'B 포인트 차감 확인: ' . get_user_point($this->B));


        // 루트로 글 쓰고, 포인트 증/감 초과.
        wp_set_current_user($this->A);
        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue($post['ID'] > 0, '글 쓰기 성공');
        self::assertTrue( get_user_point($this->A) == 1000, '루트 포인트 증/감 제한 초과. 포인트 변화 없음: 포인트는 1천이어야 함. ' . get_user_point($this->A));

        // 일반 사용자가 추천하면, 에러. 시간/수 초과.
        wp_set_current_user($this->B);
        $re = api_vote(['post_ID' => $post['ID'], 'choice' => 'Y']);
        self::assertTrue( get_user_point($this->B) == 1000 + (5 * get_option(POINT_LIKE_DEDUCTION)), 'B 포인트 차감 안됨. 제한 걸림. 750 이어야 함: ' . get_user_point($this->B));
    }

    public function testLikeDailyLimit(): void {
        $this->clear();
        update_option(POINT_DISLIKE, -100);
        update_option(POINT_DISLIKE_DEDUCTION, -50);

        // 하루 12개 추천을 제한
        update_option(POINT_LIKE_DAILY_LIMIT_COUNT, 12);

        // 사용자 B 로 로그인하고, 1천 포인트 세팅
        wp_set_current_user($this->B);
        set_user_point($this->B, 1000);

        $posts = get_posts(['posts_per_page' => 13]);


        // 글 12 개에 비추천
        foreach( $posts as $post ) {
            $re = api_vote(['post_ID' => $post->ID, 'choice' => 'N']);
//            d($re);
//            d('--------> ' . get_user_point($this->B));
            self::assertTrue(api_error($re) === false, "글 12개 비추천:");
        }
        self::assertTrue( get_user_point($this->B) == 1000 + (12 * -50), "포인트는 400 이어야 함: " . get_user_point($this->B));


        // 일/수 제한에 걸려서, 비추천은 되지만, 포인트/증감은 없다.
        $post_ID = end($posts)->ID;
        $re = api_vote(['post_ID' => $post_ID, 'choice' => 'N']);
        self::assertTrue( get_user_point($this->B) == 1000 + (12 * -50), "포인트 변화가 없어야 함: " . (1000 + (12 * -50)) );



    }


    /**
     * @group api
     */
    public function testPostCommentDailyLimit(): void {
        $this->clear();

        // 글 작성시 -100 점 감점. 하루에 총 25 개 글 작성 가능.
        update_category([
            'category' => 'point_test',
            POINT_POST_CREATE => -100,
            POINT_COMMENT_CREATE => -10,
            POINT_DAILY_LIMIT_COUNT => 25]);

        set_user_point($this->A, 10000);

        // 루트로 글 쓰고 글 24개 쓰고,
        wp_set_current_user($this->A);
        for( $i =0; $i < 24; $i ++ ) {
            $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
            self::assertTrue(api_error($post) === false, '');
        }

        self::assertTrue( get_user_point($this->A) == 7600, 'A 포인트는 7500 점이 되어야 함:' . get_user_point($this->A));


        // 코멘트 하나 더 쓰고,
        $res = getRoute(['route'=>'forum.editComment', 'comment_post_ID' => $post['ID'], 'comment_content' => 'point test', 'session_id' => get_session_id()]);
        self::assertTrue( get_user_point($this->A) == 7590, 'A 포인트는 7590 점이 되어야 함:' . get_user_point($this->A));


        // 글 하나 더 쓰면, 제한에 걸려서 포인트 증감 없다.
        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue( get_user_point($this->A) == 7590, 'A 포인트는 7590 점이 되어야 함:' . get_user_point($this->A));

    }


    // 글 쓰기 막기. 제한에 걸리면 아예 글/코멘트를 쓰지 못하게 막는다.
    public function testBanOnLimit(): void {
        $this->clear();

        // point_test 게시판에 하루에 글 2개 까지만 포인트 인정.
        update_category([
            'category' => 'point_test',
            POINT_POST_CREATE => 100,
            POINT_DAILY_LIMIT_COUNT => 2]);


        wp_set_current_user($this->A);
        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue($post['ID'] > 0);
        self::assertTrue( get_user_point($this->A) == 100, 'A 포인트는 100 점이 되어야 함:' . get_user_point($this->A));


        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue($post['ID'] > 0);
        self::assertTrue( get_user_point($this->A) == 200, 'A 포인트는 200 점이 되어야 함:' . get_user_point($this->A));

        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        self::assertTrue($post['ID'] > 0);
        self::assertTrue( get_user_point($this->A) == 200, 'A 포인트는 그대로 200 점이 되어야 함:' . get_user_point($this->A));


        // 글 쓰기 막기. 제한에 걸리면 아예 글/코멘트를 쓰지 못하게 막는다.
        update_category(['category' => 'point_test', BAN_ON_LIMIT => 'Y']);


        $post = api_create_post(['category' => 'point_test', 'post_title' => 'abc']);
        d($post);
        self::assertTrue(api_error($post), "글 쓰기 에러나야 함");
        self::assertTrue( get_user_point($this->A) == 200, 'A 포인트는 그대로 200 점이 되어야 함:' . get_user_point($this->A));


    }
//
//    public function testPostCommentBanOnLackOfPoint(): void {
//
//    }

}



