<?php
/**
 * @file cafe.config.php
 */

/// 카페 루트 도메인
define('CAFE_ROOT_DOMAINS', ['sonub.com', 'philov.com', 'anyvie.com']);

define('CAFE_DEFAULT_FORUM_LIST_WIDGET', 'forum-list/forum-list-default');
define('CAFE_COMPANY_BOOK_WIDGET', 'forum-list/forum-list-company-book');

/**
 * 여기서 메뉴 이름을 바꾸면, 실제 홈페이지에서도 바뀌어 나타난다.
 */
define('CAFE_CATEGORIES', [
    'discussion' => [
        'name' => '자유게시판',
        'forum_list_widget' => CAFE_DEFAULT_FORUM_LIST_WIDGET,
    ],
    'qna' => [
        'name' => '질문게시판',
        'forum_list_widget' => CAFE_DEFAULT_FORUM_LIST_WIDGET,
    ],
    'reminder' => [
        'name' => '공지사항'
    ],
    'greeting' => [
        'name' => '가입인사'
    ],
    'knowhow' => [
        'name' => '경험담'
    ],
    'job' => [
        'name' => '구인구직'
    ],
    'international_marriage' => [
        'name' => '국제결혼'
    ],
    'caution' => [
        'name' => '주의사항'
    ],
    'looking_for' => [
        'name' => '사람찾기'
    ],
    'company_book' => [
        'name' => '업소록',
        'forum_list_widget' => CAFE_COMPANY_BOOK_WIDGET,
    ],
    'buyandsell' => [
        'name' => '회원장터',
    ],
    'cars' => [
        'name' => '중고차매매',
        'parent' => 'buyandsell',
    ],
    'rentcar' => [
        'name' => '렌트카',
        'parent' => 'buyandsell',
    ],
]);




add_hook('category_meta', function($name, &$value) {
    if ( $name == 'forum_list_widget' ) {
        $slug = original_category(in('category'));
        if ( isset(CAFE_CATEGORIES[$slug]) ) $value = CAFE_CATEGORIES[$slug]['forum_list_widget'];
        else $value = CAFE_DEFAULT_FORUM_LIST_WIDGET;
    }
});


add_hook('category_not_exists', function( &$category) {
    $categorySlug = in('category');
    $orgCategory = original_category($categorySlug);
    if ( isset(CAFE_CATEGORIES[$orgCategory]) ) {
        $co = cafe_option();
        $countryCode = $co['countryCode'];
        $countryCategory = get_category_by_slug($countryCode);
        $ID = wp_insert_category(['cat_name' => $categorySlug, 'category_parent' => $countryCategory->term_id]);
        $category = get_category($ID);
    }
});

