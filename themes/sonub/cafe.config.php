<?php
define('CAFE_DEFAULT_FORUM_LIST_WIDGET', 'forum-list/forum-list-default');
define('CAFE_COMPANY_BOOK_WIDGET', 'forum-list/forum-list-company-book');
define('CAFE_CATEGORIES', [
    'discussion' => [
        'forum_list_widget' => CAFE_DEFAULT_FORUM_LIST_WIDGET,
    ],
    'qna' => [
        'forum_list_widget' => CAFE_DEFAULT_FORUM_LIST_WIDGET,
    ],
    'company_book' => [
        'forum_list_widget' => CAFE_COMPANY_BOOK_WIDGET,
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

