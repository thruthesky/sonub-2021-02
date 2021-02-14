<?php
/**
 * @file cafe.config.php
 */


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
        'countryName' => '필리핀',
        'siteName' => '필러브',
    ],
    'tellvi.com' => [
        'countryCode' => 'VN',
        'countryName' => '베트남',
        'siteName' => '텔비',
    ],
    'sonub.com' => [
        'countryCode' => '',
        'countryName' => '전세계',
        'siteName' => '소너브'
    ]
]);

/**
 * 테스트를 위한, 루트 도메인 지정.
 *
 * 테스트 할 때, 루트 도메인을 hosts 파일에 127.0.0.1 로 한다면, 배포 후, 확인을 위해서 항상 hosts 파일을 수정해야한다.
 * 그러한 번거로움 없이, 아래의 도메인의 경우, 루트 도메인으로 항상 지정한다.
 */
define('TEST_CAFE_ROOT_DOMAINS', ['banana.philov.com']);


/**
 * 여기서 메뉴 이름을 바꾸면, 실제 홈페이지에서도 바뀌어 나타난다.
 *
 * @see [게시판 성격 관리] https://docs.google.com/document/d/183T26WZtfaa0SrQRF7Ut2h_pFn3qorZk1OrdH-1VWrU/edit#heading=h.1c6q0c752xtu
 *
 *
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
    'news' => [ 'name' => '뉴스' ],
    'weather' => [ 'name' => '날씨/태풍' ],
    'travel' => ['name' => '여행' ],
    'business' => ['name' => '사업정보' ],
    'business_sale' => ['name' => '사업매매', 'parent' => 'business' ],
    'business_partner' => ['name' => '동업자구함', 'parent' => 'business' ],
    'restaurant' => ['name' => '식당' ],
    'must_eat_place' => ['name' => '맛집' ],
    'food_show' => ['name' => '먹방' ],
    'study' => ['name' => '어학연수' ],
    'immigrant' => ['name' => '이민/이주' ],
    'passport' => ['name' => '여권/비자' ],
    'helper' => ['name' => '가정부/도우미' ],
    'document' => ['name' => '각종서류' ],
    'bonding' => ['name' => '소모임' ],
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
    'boarding_house' => ['name' => '하숙집', 'parent' => 'buyandsell', ],
    'money_exchange' => ['name' => '페소/환전', 'parent' => 'buyandsell', ],
    'house_rent' => ['name' => '주택임대', 'parent' => 'buyandsell', ],
    'house_sale' => ['name' => '주택매매', 'parent' => 'buyandsell', ],
    'home_appliances' => ['name' => '가전제품', 'parent' => 'buyandsell', ],
    'baby_products' => ['name' => '유아용품', 'parent' => 'buyandsell', ],
    'daily_supplies' => ['name' => '생활용품', 'parent' => 'buyandsell', ],
    'office_supplies' => ['name' => '사무용품', 'parent' => 'buyandsell', ],
    'store_supplies' => ['name' => '업소/가게 용품', 'parent' => 'buyandsell', ],
    'clothing_goods' => ['name' => '의류/신발/잡화', 'parent' => 'buyandsell', ],
    'luxury_goods' => ['name' => '보석/명품', 'parent' => 'buyandsell', ],
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
 * 카페 메뉴에서 자식이 없는 상위 카테고리만 리턴한다.
 * @return array
 */
function cafe_categories_parent_without_child(): array {
    $rets = [];
    $parents = [];
    foreach(CAFE_CATEGORIES as $slug => $menu ) {
        if ( isset($menu['parent']) ) {
            if ( !isset($parents[$menu['parent']]) ) $parents[$menu['parent']] = [];
            $parents[$menu['parent']][$slug] = $menu;
            continue;
        }
        $rets[$slug] = $menu;
    }
    foreach($parents as $slug => $menu) {
        unset($rets[$slug]);
    }
    return $rets;
}

/**
 * 카페 메뉴에서 특정 카테고리의 자식 카테고리만 리턴한다.
 * @return array
 */
function cafe_categories_of($parent): array {
    $parents = [];
    foreach(CAFE_CATEGORIES as $slug => $menu ) {
        if ( isset($menu['parent']) ) {
            if ( !isset($parents[$menu['parent']]) ) $parents[$menu['parent']] = [];
            $parents[$menu['parent']][$slug] = $menu;
            continue;
        }
    }
    return $parents[$parent];
}




/**
 * @param $slug
 * @return string[]
 */
function get_cafe_category($slug): array {
    return CAFE_CATEGORIES[$slug];
}

/**
 * 글을 가져 올 때, 카테고리 설정이 되지 않은 경우,
 * CAFE_DOMAIN_SETTINGS 에 설정된 해당 카페의 국가의 글만 가져오도록 한정시킨다.
 * 만약, 국가 코드가 없는 경우는, 전체 글을 다 가져온다.
 */
add_hook('forum_search_option', function(&$in) {
    if ( !isset($in['category_name']) || empty($in['category_name']) ) {
        $in['category_name'] = cafe_country_code();
    }
});


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
 * CAFE_CATEGORIES 에 없는 게시판은 자동 생성하지 않는다.
 */
add_hook('category_not_exists', function( &$category) {
    $categorySlug = in('category');
    $orgCategory = original_category($categorySlug);
    if ( isset(CAFE_CATEGORIES[$orgCategory]) ) {
        $countryCode = cafe_country_code(); // 카페 국가 코드
        $countryCategory = get_category_by_slug($countryCode); // 국가 카테고리 아래에 카테고리 생성.
        if ( empty($countryCategory) ) {
            jsBack('앗! 국가 카테고리가 존재하지 않습니다. 카페 하나를 생성하면 자동으로 국가 카테고리가 생성됩니다. 먼저 카페 하나를 생성하세요.');
        }
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
{$category->cat_name} 게시판은 $country_name 전체에서 공유됩니다.
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


add_hook('favicon', function() {
    return DOMAIN_THEME_URL . '/favicon/' . get_root_domain() . '/' . 'favicon.ico';
});