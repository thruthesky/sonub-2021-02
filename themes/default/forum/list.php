<?php
$category = get_category_by_slug(in('category'));
$post_topic = NOTIFY_POST . $category->slug;
$comment_topic = NOTIFY_COMMENT . $category->slug;



?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List</h2>
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

<section class="post-list p-2">
    <?php
    $page_no = in('page_no', 1);
    $posts_per_page = category_meta($category->ID, 'posts_per_page', POSTS_PER_PAGE);
    $offset = ($page_no - 1) * $posts_per_page;
    $q = ['category_name' => $category->slug, 'posts_per_page' => $posts_per_page, 'offset' => $offset];
    $posts = forum_search($q);

    foreach ($posts as $post) {
        // print_r($post);
    ?>
        <a class="d-flex justify-content-between mb-2" href="<?php echo $post['url'] ?>">

            <div class="d-flex">
                <? if ( $post['profile_photo_url'] ) { ?>
                <img class="me-3 size-40 circle" src="<?= $post['profile_photo_url'] ?>">
                <? } ?>
                <h1><?php echo $post['post_title'] ?></h1>
            </div>

            <div class="meta">
                By <?php echo $post['author_name'] ?>
            </div>
        </a>
    <?php } ?>
</section>

<?php

$options = [
        'page_no' => $page_no,
    'blocks' => 3,
    'arrow' => true,
    'total_no_of_posts' => $category->category_count,
    'no_of_posts_per_page' => $posts_per_page,
    'url' => '/?page=forum/list&category=reminder&page_no={page_no}'
];

include_once THEME_DIR . '/widgets/pagination/pagination.php';


