<?php
/**
 * Get the post ID
 */
$arr = explode('/', $_SERVER['REQUEST_URI']);
$post_ID = $arr[1];

/**
 * Get the post
 */
$post = post_response($post_ID, ['with_autop' => true]);


/**
 * Get the category of the post
 */
$category = get_category_by_slug($post['category']);


include_once widget(category_meta($category->term_id, 'forum_view_widget', 'forum-view/forum-view-default'));
