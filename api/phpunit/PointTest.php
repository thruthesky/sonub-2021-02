<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../../../wp-load.php");

// 설정. 사용자 A,B,C,D. A 는 루트이다. 글/코멘트 연속 쓰기 가능.
define('A', 1);
define('B', 2);
define('C', 3);
define('D', 4);


final class PointTest extends TestCase
{
    private $category = 'point_test';

    // 포인트 테스트 게시판에 D 사용자로 글 3개 생성
    private $post1 = null;
    private $post2 = null;
    private $post3 = null;

    // 글 10개를 읽어서 저장함.
    private $posts = [];


    /// 사용자 A, B, C 의 포인트 0으로 처리, 모든 포인트 설정 0으로 처리, 기록 삭제
    private function clear()
    {
        set_phpunit_mode(true);
        global $wpdb;

        $wpdb->query("truncate api_point_history");
        $wpdb->query("truncate api_forum_vote_history");
        set_user_point(A, 0);
        set_user_point(B, 0);
        set_user_point(C, 0);
        set_user_point(D, 0);

        set_register_point(0);
        set_login_point(0);

        set_like_point(0);
        set_like_deduction_point(0);
        set_dislike_point(0);
        set_dislike_deduction_point(0);


        set_like_daily_limit_count(0);
        set_like_hour_limit(0);
        set_like_hour_limit_count(0);

        $cat = get_category_by_slug($this->category);
        if (!$cat) {
            wp_insert_category(['cat_name' => $this->category, 'category_description' => 'point_test'], true);
        }


        set_post_create_point($this->category, 0);
        set_comment_create_point($this->category, 0);
        set_post_delete_point($this->category, 0);
        set_comment_delete_point($this->category, 0);
        set_category_hour_limit($this->category, 0);
        set_category_hour_limit_count($this->category, 0);
        set_category_daily_limit_count($this->category, 0);



        $this->login(D);
        $this->post1 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        $this->post2 = api_create_post(['category' => $this->category, 'post_title' => 'post 2']);
        $this->post3 = api_create_post(['category' => $this->category, 'post_title' => 'post 3']);


        $this->posts = get_posts(['posts_per_page' => 10]);



    }

    private function login($ID, $point=0) {
        wp_set_current_user($ID);
        set_user_point($ID, $point);
    }


    public function testLike(): void {
        $this->clear();
        set_like_point(100);
        set_like_deduction_point(-20);
        set_dislike_point(-50);
        set_dislike_deduction_point(-30);

        self::assertTrue(get_like_point() == 100, "Like 100 : " . get_like_point());
        self::assertTrue(get_like_deduction_point() == -20, "Like Deduction -20 : " . get_like_deduction_point());

        // B 포인트가 없다. 그래서 0 이하로 저장되지 않으므로 0 이다.
        $this->login(B);
        $post = api_vote(['post_ID' => $this->post1['ID'], 'choice' => 'Y']);
        self::assertTrue($post['ID'] == $this->post1['ID'], 'vote success');
        self::assertTrue(get_user_point(B) == 0, 'B point: -20 vs : ' . get_user_point(B));


        // 포인트가 30에서 10으로 감소했다.
        set_user_point(B, 30);
        $post = api_vote(['post_ID' => $this->post2['ID'], 'choice' => 'Y']);
        self::assertTrue(get_user_point(B) == 10, 'B point: 10 vs : ' . get_user_point(B));

        // 다시 동일한 게시물에 다시 추천하면, 동일한 글/코멘트 두번째 추천/비추천에는 포인트 증/감 영량이 없다.
        $post = api_vote(['post_ID' => $this->post2['ID'], 'choice' => 'N']);
        self::assertTrue(get_user_point(B) == 10, 'B point: 10 vs : ' . get_user_point(B));

        // D 는 두번 추천 받았으므로 포인트 200
        self::assertTrue(get_user_point(D) == 200, 'D piont 200: vs: ' . get_user_point(D));

        // B 가, D에게 비추천하면,
        // B 의 포인트가 -30포인트 차감인데 10포인트 밖에 없으므로 0.
        // D 는 -50 차감되어 150
        $post = api_vote(['post_ID' => $this->post3['ID'], 'choice' => 'N']);
        self::assertTrue(get_user_point(B) == 0, 'B point: 0 vs : ' . get_user_point(B));
        self::assertTrue(get_user_point(D) == 150, 'D piont 150: vs: ' . get_user_point(D));
    }

    public function testLikeHourlyLimit(): void {
        $this->clear();
        set_like_point(1000);
        set_like_deduction_point(-1000);
        set_dislike_point(-1000);
        set_dislike_deduction_point(-1000);

        // 포인트 시간/수 제한 없음.
        $this->login(B, 10000);
        for($i=0; $i<10; $i++) {
            $post = api_vote(['post_ID' => $this->posts[$i]->ID, 'choice' => 'N']);
        }
        self::assertTrue(get_user_point(B) == 0, 'B point 0');




        // 시간/수 = 2시간에 11번.
        $this->clear();
        set_like_point(1000);
        set_like_deduction_point(-1000);
        set_dislike_point(-1000);
        set_dislike_deduction_point(-1000);

        set_like_hour_limit(2);
        set_like_hour_limit_count(11);

        // 충분함.
        $this->login(B, 10000);
        for($i=0; $i<10; $i++) {
            $post = api_vote(['post_ID' => $this->posts[$i]->ID, 'choice' => 'N']);
        }
        self::assertTrue(get_user_point(B) == 0, 'B point 0: vs: ' . get_user_point(B));


        // 시간/수 = 2시간에 9번.
        $this->clear();
        set_like_point(1000);
        set_like_deduction_point(-1000);
        set_dislike_point(-1000);
        set_dislike_deduction_point(-1000);

        set_like_hour_limit(2);
        set_like_hour_limit_count(9);

        // 마지막 1번은 안됨.
        $this->login(B, 10000);
        for($i=0; $i<10; $i++) {
            $post = api_vote(['post_ID' => $this->posts[$i]->ID, 'choice' => 'N']);
        }
        self::assertTrue(get_user_point(B) == 1000, 'B point 1000: vs: ' . get_user_point(B));

    }


    public function testLikeDailyLimit(): void {
        $this->clear();
        set_like_point(200);
        set_like_deduction_point(-100);
        set_dislike_point(-150);
        set_dislike_deduction_point(-50);

        // 포인트 일/수 제한

        set_like_daily_limit_count(4);

        $this->login(B, 1000);
        $post = api_vote(['post_ID' => $this->post1['ID'], 'choice' => 'Y']);
        self::assertTrue(get_user_point(B) == 900, 'B point 900: vs: ' . get_user_point(B));
        self::assertTrue(get_user_point(D) == 200, 'D point 200: vs: ' . get_user_point(D));

        $post = api_vote(['post_ID' => $this->post2['ID'], 'choice' => 'N']);
        self::assertTrue(get_user_point(B) == 850, 'B point 750: vs: ' . get_user_point(B));
        self::assertTrue(get_user_point(D) == 50, 'D point 50: vs: ' . get_user_point(D));


        $post = api_vote(['post_ID' => $this->post3['ID'], 'choice' => 'N']);
        self::assertTrue(get_user_point(B) == 800, 'B point 800: vs: ' . get_user_point(B));
        self::assertTrue(get_user_point(D) == 0, 'D point 0: vs: ' . get_user_point(D));

        $post = api_vote(['post_ID' => $this->post3['ID'], 'choice' => 'Y']);
        self::assertTrue(get_user_point(B) == 800, 'No change: B point 800: vs: ' . get_user_point(B));
        self::assertTrue(get_user_point(D) == 0, 'No change: D point 0: vs: ' . get_user_point(D));

    }



    public function testPostCreateDelete(): void {
        $this->clear();
        set_post_create_point($this->category, 1000);
        set_post_delete_point($this->category, -1200);
        set_comment_create_point($this->category, 200);
        set_comment_delete_point($this->category, -300);

        self::assertTrue(get_post_create_point($this->category) == 1000);
        self::assertTrue(get_post_delete_point($this->category) == -1200);
        self::assertTrue(get_comment_create_point($this->category) == 200);
        self::assertTrue(get_comment_delete_point($this->category) == -300);

        /// 게시글 생성
        $this->login(A);
        $post1 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue(get_user_point(A) == 1000, 'A point must be 1000: ' . get_user_point(A));

        $post2 = api_create_post(['category' => $this->category, 'post_title' => 'post 2']);
        self::assertTrue(get_user_point(A) == 2000, 'A point must be 2000: ' . get_user_point(A));


        // 게시글 삭제
        $re = api_delete_post([ 'ID' => $post2['ID'], 'session_id' => get_session_id() ]);
        self::assertTrue($re['ID'] > 0, 'post delete');
        self::assertTrue(get_user_point(A) == 800, 'A point must be 800: ' . get_user_point(A));

        $re = api_delete_post([ 'ID' => $post1['ID'], 'session_id' => get_session_id() ]);
        self::assertTrue($re['ID'] > 0, 'post delete');
        self::assertTrue(get_user_point(A) == 0, 'A point must be 0: ' . get_user_point(A));
    }

    public function testCommentCreateDelete(): void {
        $this->clear();
        set_comment_create_point($this->category, 200);
        set_comment_delete_point($this->category, -300);


        /// 코멘트 생성
        $this->login(A, 1000);
        $re = api_edit_comment(['comment_post_ID' => $this->post1['ID'], 'comment_content' => 'comment ' . time()]);
        self::assertTrue($re['comment_ID'] > 0, 'comment create');
        self::assertTrue(get_user_point(A) == 1200, 'A point must be 1200: ' . get_user_point(A));


        /// 코멘트 삭제
        $re = api_delete_comment(['comment_ID' => $re['comment_ID'] ]);
        self::assertTrue($re['comment_ID'] > 0, 'comment delete');
        self::assertTrue(get_user_point(A) == 900, 'A point must be 900: ' . get_user_point(A));
    }


    public function testPostCommentCreateHourlyLimit(): void {
        $this->clear();

        // 2시간에 3번 제한
        set_category_hour_limit($this->category, 2);
        set_category_hour_limit_count($this->category, 3);


        $this->login(A);
        $post1 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '첫번째 글 쓰기 성공');


        $post2 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '두번째 글 쓰기 성공');

        $post3 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '세번째 글 쓰기 성공');

        $re = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($re == ERROR_HOURLY_LIMIT, '네번째 글 쓰기 실패');


        $re = api_edit_comment(['comment_post_ID' => $post3['ID'], 'comment_content' => 'Yo! hourly limit test.']);
        self::assertTrue($re == ERROR_HOURLY_LIMIT, '코멘트 쓰기 실패');
    }

    public function testPostCommentCreateDailyLimit(): void {
        $this->clear();

        // 하루에 3번 제한
        set_category_daily_limit_count($this->category, 3);


        $this->login(A);
        $post1 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '첫번째 글 쓰기 성공');


        $post2 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '두번째 글 쓰기 성공');

        $post3 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($post1['ID'] > 0, '세번째 글 쓰기 성공');

        $re = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        self::assertTrue($re == ERROR_DAILY_LIMIT, '네번째 글 쓰기 실패: ' . $re);

        $re = api_edit_comment(['comment_post_ID' => $post3['ID'], 'comment_content' => 'Yo! hourly limit test.']);
        self::assertTrue($re == ERROR_DAILY_LIMIT, '코멘트 쓰기 실패: ' . $re);
    }


}
