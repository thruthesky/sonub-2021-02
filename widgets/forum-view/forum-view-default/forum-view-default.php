<?php
/**
 * @file view.php
 * @desc view page
 */

$o = get_widget_options();
$category = $o['category'];
/**
 * Get the post
 */
$post = get_current_page_post();
$post = post_response($post, ['with_autop' => true]);

/**
 * Comments of the post
 */
$comments = $post['comments'];
?>
<article class="card border border-dark p-2 m-3">
    <h1><?php echo $post['post_title'] ?></h1>
    <p>
        ID: <?php echo $post['ID'] ?>,
        Date: <?=$post['short_date_time']?>
        <br>
        Content: <?php echo $post['post_content_autop'] ?>
    </p>


    <?php if (count($post['files'])) {
        foreach ($post['files'] as $file) {
            ?>
            <div><img class="w-100" src="<?= $file['url'] ?>"></div>
        <?php }
    } ?>

    <!-- TODO: MINE BUTTONS -->
    <div class="d-flex justify-content-between">
        <div>
            <? if ( is_my_post($post['ID']) ) { ?>
                <a class="btn btn-success" href="/?page=forum/edit&ID=<?php echo $post['ID'] ?>">Edit</a>
                <button class="btn btn-danger ms-2" @click="onPostDelete('<?= $post['ID'] ?>', '<?=$post['category']?>')">
                    Delete
                </button>
            <? } ?>
        </div>
        <div>
            <a class="btn btn-secondary" href="/?page=forum.list&category=<?=$post['category']?>">List</a>
            <? if ( admin() ) { ?>
                <a class="btn btn-secondary ms-2" href="/?page=admin/push-notification/send&ID=<?php echo $post['ID'] ?>" target="_blank">
                    Send Push
                </a>
            <? } ?>
        </div>


    </div>

    <hr>

    <comment-form :comment_post_id="<?=$post['ID']?>"></comment-form>

    <!-- TODO: Comment order -->
    <?php if (count($comments)) { ?>
        <section class="mt-2">
            <span>Comments</span>
            <?php
            $comments = $post['comments'];
            foreach ($comments as $comment) {
                // print_r($comment)
                $comment_ID = $comment['comment_ID'];
                $comment_content = $comment['comment_content'];
                $comment_content_autop = $comment['comment_content'];
                $comment_author = $comment['comment_author'];
                $comment_parent = $comment['comment_parent'];
                $comment_date = $comment['comment_date'];
                $short_date_time = $comment['short_date_time'];
                $depth = $comment['depth'];
                $files = $comment['files'];
                $user_id = $comment['user_id'];
                ?>
                <!-- TODO: comment sorting between brothers. -->
                <div class="card p-2 mt-2 border border-dark" id="comment_<?php echo $comment_ID ?>" depth="<?=$depth?>">
                    <article :class="{ 'd-none': editNo == <?=$comment_ID?>, }">
                        ID: <?php echo $comment_ID ?> |
                        Author: <?php echo $comment_author ?>
                        <div style="display: block;" id="comment_content_<?php echo $comment_ID ?>">
                            Content: <?php echo $comment_content ?>
                            <? if ( count($files) ) { ?>
                                <div class="bg-light">
                                    <? foreach($files as $file) { ?>
                                        <div><img class="w-100" src="<?=$file['url']?>"></div>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <!-- TODO: MINE BUTTON -->
                            <div class="mt-2">
                                <button class="btn btn-secondary mr-2" @click="replyNo=<?=$comment_ID?>">Reply</button>

                                <? if ( is_my_comment($comment_ID) ) { ?>
                                    <button class="btn btn-success mr-2" @click="editNo=<?=$comment_ID?>">Edit</button>

                                    <button class="btn btn-danger"
                                            @click="onCommentDelete('<?php echo $comment_ID ?>')"
                                    >
                                        DELETE
                                    </button>
                                <? } ?>

                            </div>
                            <hr>
                            <comment-form :comment_post_id="<?=$post['ID']?>" :comment_parent="<?=$comment_ID?>" v-if="replyNo == <?=$comment_ID?>"></comment-form>
                        </div>
                    </article>
                    <comment-form
                        :comment_post_id="<?=$post['ID']?>"
                        :comment_id="<?=$comment_ID?>"
                        v-if="editNo == <?=$comment_ID?>"></comment-form>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</article>
