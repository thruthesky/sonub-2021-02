<?php
$category = get_category_by_slug(in('category'));
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
