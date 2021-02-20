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

        $this->login(D);
        $this->post1 = api_create_post(['category' => $this->category, 'post_title' => 'post 1']);
        $this->post2 = api_create_post(['category' => $this->category, 'post_title' => 'post 2']);
        $this->post3 = api_create_post(['category' => $this->category, 'post_title' => 'post 3']);

        set_post_create_point($this->category, 0);
        set_comment_create_point($this->category, 0);
        set_post_delete_point($this->category, 0);
        set_comment_delete_point($this->category, 0);

        set_category_hour_limit($this->category, 0);
        set_category_hour_limit_count($this->category, 0);
        set_category_daily_limit_count($this->category, 0);

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


}
