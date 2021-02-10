<?php
$category = get_category_by_slug(in('category'));
/// 카테고리가 존재하지 않으면, 카테고리가 존재하지 않는다는 hook 을 실행
/// 카테고리를 생성 할 수도 있음.
if ( ! $category ) {
    run_hook('category_not_exists', $category);
}

if ( empty($category) ) {
    include get_theme_page_path( DOMAIN_THEME, 'error/forum-list-wrong-category');
    return;
}

$post_topic = NOTIFY_POST . $category->slug;
$comment_topic = NOTIFY_COMMENT . $category->slug;
$page_no = in('page_no', 1);
$posts_per_page = category_meta($category->ID, 'posts_per_page', POSTS_PER_PAGE);



include_once widget(category_meta($category->term_id, 'forum_list_header_widget', 'forum-list-header/forum-list-header-default'));

include_once widget(category_meta($category->term_id, 'forum_list_widget', 'forum-list/forum-list-default'));

include_once widget(category_meta($category->term_id, 'pagination_widget', 'pagination/pagination-default'), [
    'page_no' => $page_no,
    'blocks' => 3,
    'arrow' => true,
    'total_no_of_posts' => $category->category_count,
    'no_of_posts_per_page' => $posts_per_page,
    'url' => '/?page=forum/list&category=' . $category->slug . '&page_no={page_no}'
]);



//include_once THEME_DIR . '/widgets/pagination/pagination.php';
