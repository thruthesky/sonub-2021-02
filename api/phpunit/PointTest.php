<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../../../wp-load.php");

final class PointTest extends TestCase
{
    private $A = 1;
    private $B = 2;
    private $C = 3;


    /// 사용자 A, B, C 의 포인트 0으로 처리, 모든 포인트 설정 0으로 처리, 기록 삭제
    private function clear()
    {
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
        update_option(POINT_LIKE_DAILY_LIMIT, 0);

        $cat = get_category_by_slug('point_test');
        if (!$cat) {
            wp_insert_category(['cat_name' => 'point_test', 'category_description' => 'point_test'], true);
            $cat = get_category_by_slug('point_test');
        }
        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_POST_CREATE, 'value' => 0]);
        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_COMMENT_CREATE, 'value' => 0]);
        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_POST_DELETE, 'value' => 0]);
        update_category_meta(['cat_ID' => $cat->term_id, 'field' => POINT_COMMENT_DELETE, 'value' => 0]);
        update_category(['category' => 'point_test', POINT_DAILY_LIMIT => 0, POINT_HOUR_LIMIT => 0, POINT_HOUR_LIMIT_COUNT => 0]);
    }


    private function testLike(): void {
        $this->clear();
        set_like_point(100);
    }

}
