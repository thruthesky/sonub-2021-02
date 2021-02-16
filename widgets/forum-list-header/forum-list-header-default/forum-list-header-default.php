<?php
$o = get_widget_options();
$post_topic = NOTIFY_POST . $o['category']->slug;
$comment_topic = NOTIFY_COMMENT . $o['category']->slug;
?>

<div class="forum-list-header">
    <div class="p-2 d-flex justify-content-between">
        <h2><?= $o['category']->cat_name ?></h2>
        <div class="d-flex">
            <div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notificationUnderMyPost" @change="onChangeSubscribeOrUnsubscribeTopic('<?= $post_topic ?>',$event)" <? echo ( isSubscribedToTopic($post_topic) ? 'checked' : '' );?>
                    >
                    <label class="form-check-label fs-sm" for="notificationUnderMyPost">새 글 알림</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notificationUnderMyComment" @change="onChangeSubscribeOrUnsubscribeTopic('<?= $comment_topic ?>', $event)" <? echo ( isSubscribedToTopic($comment_topic) ? 'checked' : '' );?>
                    >
                    <label class="form-check-label fs-sm" for="notificationUnderMyPost">새 코멘트 알림</label>
                </div>
            </div>
            <div class="ms-2">
                <a class="btn btn-secondary" href="/?page=forum.edit&category=<?= $o['category']->slug ?>"><?=ln('Create Post', '글 작성')?></a>
            </div>
        </div>
    </div>
</div>
<style>
    .forum-list-header .form-check {
        max-height: 1em;
        min-height: 1em;
    }
</style>
