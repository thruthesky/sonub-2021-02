<?php
/**
 * @file cafe.config.php
 */

/// 카페 루트 도메인
define('CAFE_ROOT_DOMAINS', ['sonub.com', 'philov.com', 'anyvie.com']);

define('CAFE_DEFAULT_FORUM_LIST_WIDGET', 'forum-list/forum-list-default');
define('CAFE_COMPANY_BOOK_WIDGET', 'forum-list/forum-list-company-book');

define('NO_OF_WIDE_CAFE_MENU', 16);
define('NO_OF_NARROW_CAFE_MENU', 8);

/// 카페에서 본문, 사이드바에서 사용 할 최대 위젯 개수
define('NO_OF_CAFE_WIDGETS', 12);



/**
 * README.md 참고
 */
define('CAFE_DOMAIN_SETTING', [
    'philov.com' => [
        'countryCode' => 'PH',
    ],
    'philgo.net' => [
        'countryCode' => 'PH',
    ],
    'sonub.com' => [
        'countryCode' => 'KR',
    ]
]);


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
/**
 * @param $slug
 * @return string[]
 */
function get_cafe_category($slug): array {
    return CAFE_CATEGORIES[$slug];
}


/**
 * 카페의 게시판 목록 기본 위젯이 없다면, 여기서 설정을 해 준다.
 */
add_hook('category_meta', function($name, &$value) {
    if ( $name == 'forum_list_widget' ) {
        $slug = original_category(in('category'));
        if ( isset(CAFE_CATEGORIES[$slug]) &&  isset(CAFE_CATEGORIES[$slug]['forum_list_widget']) ) {
            $value = CAFE_CATEGORIES[$slug]['forum_list_widget'];
        }
        else $value = CAFE_DEFAULT_FORUM_LIST_WIDGET;
    }
});

/**
 * 국가별 게시판이 존재하지 않으면 생성한다.
 */
add_hook('category_not_exists', function( &$category) {
    $categorySlug = in('category');
    $orgCategory = original_category($categorySlug);
    if ( isset(CAFE_CATEGORIES[$orgCategory]) ) {
        $co = cafe_option();
        $countryCode = cafe_country_code();
        $countryCategory = get_category_by_slug($countryCode);
        $ID = wp_insert_category(['cat_name' => $categorySlug, 'category_parent' => $countryCategory->term_id]);
        $category = get_category($ID);
    }
});

add_hook('forum_category', function(&$category) {
    $orgCategory = original_category($category->slug);
    /// 카페 게시판인가?
    if ( isset(CAFE_CATEGORIES[$orgCategory]) ) {
        // 그렇다면 카페 이름 조정
        $cat = CAFE_CATEGORIES[$orgCategory];
        $category->cat_name = $cat['name'];

        // 글 쓰기인가?
        if ( in('page') == 'forum/edit' ) {
            // 공지사항 글 쓰기인가?
            if ( $orgCategory == 'reminder') {
                // 카페장이 아닌가?
                if ( is_cafe_admin() == false ) {
                    jsBack('앗, 카페 관리자가 아닙니다.');
                }
            }
        }

    }
});

/**
 * 게시판 헤더 밑 부분 훅
 *
 * - 전체 공유 공지 표시
 */
add_hook('forum_list_header_bottom', function(&$category) {
    $orgCategory = original_category($category->slug);
    if ( isset(CAFE_CATEGORIES[$orgCategory]) ) {
        $cat = CAFE_CATEGORIES[$orgCategory];
        $category->cat_name = $cat['name'];
        $country_name = cafe_country_name();
        return <<<EOH
이 {$category->cat_name} 게시판은 $country_name 전체에서 공유됩니다.
EOH;

    }
});


/**
 * 위젯 카테고리 설정에서, 카페의 카테고리를 넣어준다. 이 때, 국가별 코드도 같이 넣어 준다.
 */
add_hook('widget/config.category_name categories', function(&$categories) {
    $categories = [];
    foreach( CAFE_CATEGORIES as $slug => $opt ) {
        $obj = new stdClass();
        $obj->slug = $slug . '_' . cafe_country_code();
        $obj->cat_name = $opt['name'];
        $categories[] = $obj;
    }
});

/**
 * 위젯 카테고리 설정에서, 카테고리를 선택하지 않는 경우, 카페 국가로 제한 한다.
 */
add_hook('widget/config.category_name default_option', function(&$option) {
    $option['label'] = '전체 카테고리';
    $option['value'] = cafe_country_code();
});