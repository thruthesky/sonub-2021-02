<?php
/**
 * @file view.php
 */
/**
 * Get the post
 */

$post = get_current_page_post();
if ( $post == null ) {
    $_uri = urldecode($_SERVER['REQUEST_URI']);
    include error_script("앗! 잘못된 접속 경로입니다.", "접속 경로 '$_uri' 에 해당하는 글이 없습니다.");
    return;
}
$post = post_response($post, ['with_autop' => true]);



/**
 * Get the category of the post
 */
$category = get_category_by_slug($post['category']);


run_hook('forum_category', $category);
$o = [
    'category' => $category,
];


include_once widget(category_meta($category->term_id, 'forum_view_widget', 'forum-view/forum-view-default'), $o);
