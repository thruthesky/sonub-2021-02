<?php
$category = get_category_by_slug(in('category'));


if (!$category) { ?>

    <h1>WRONG CATEGORY</h1>

<?php } else {
    $post_topic = NOTIFY_POST . $category->slug;
    $comment_topic = NOTIFY_COMMENT . $category->slug;


    $page_no = in('page_no', 1);
    $posts_per_page = category_meta($category->ID, 'posts_per_page', POSTS_PER_PAGE);

?>
    <hr>
    <div class="p-2 d-flex justify-content-between">
        <h2>Forum List - <?= $category->slug ?></h2>
        <a class="btn btn-success" href="/?page=forum/edit&category=<?= $category->slug ?>">Create</a>
    </div>
    <div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notificationUnderMyPost" @change="onChangeSubscribeOrUnsubscribeTopic('<?= $post_topic ?>',$event)" <? echo ( isSubscribedToTopic($post_topic) ? 'checked' : '' );?>
            >
            <label class="form-check-label" for="notificationUnderMyPost">Notification on New Post</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notificationUnderMyComment" @change="onChangeSubscribeOrUnsubscribeTopic('<?= $comment_topic ?>', $event)" <? echo ( isSubscribedToTopic($comment_topic) ? 'checked' : '' );?>
            >
            <label class="form-check-label" for="notificationUnderMyPost">Notification on New Comment</label>
        </div>
    </div>
    <hr>

<?php
    include_once widget(category_meta($category->term_id, 'forum_list_widget', 'forum-list-default'));


    include_once widget('forum-list-pagination-default', [
        'page_no' => $page_no,
        'blocks' => 3,
        'arrow' => true,
        'total_no_of_posts' => $category->category_count,
        'no_of_posts_per_page' => $posts_per_page,
        'url' => '/?page=forum/list&category=' . $category->slug . '&page_no={page_no}'
    ]);
}


//include_once THEME_DIR . '/widgets/pagination/pagination.php';
