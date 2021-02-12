<?php

if ( in('category') ) {
    $category = get_category_by_slug(in('category'));
} else if ( in('ID') ) {
    $post = get_post(in('ID'));
    $category = get_the_category($post->ID)[0];
}


run_hook('forum_category', $category);
$o = [
    'category' => $category,
];



include_once widget(category_meta($category->term_id, 'forum_edit_widget', 'forum-edit/forum-edit-default'), $o);
