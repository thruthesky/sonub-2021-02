<?php

$arr = explode('/', $_SERVER['REQUEST_URI']);
$post_ID = $arr[1];

//$post = get_post($arr[1]);

$post = post_response($post_ID, ['with_autop' => true]);
$comments = $post['comments'];

?>
<hr>
<article class="card border border-dark p-2 m-3">
    <h1><?php echo $post['post_title'] ?></h1>
    <p>
        ID: <?php echo $post_ID ?> <br>
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
            <a class="btn btn-success mr-3" href="/?page=forum/edit&ID=<?php echo $post_ID ?>">Edit</a>
            <button class="btn btn-danger" @click="onPostDelete('<?php echo $post_ID ?>', '<?php echo get_the_category($post_ID)[0]->slug ?>')">
                Delete
            </button>
        </div>
        <div v-if="isAdmin()">
            <a class="btn btn-secondary" href="/?page=admin/send-push-notification&ID=<?php echo $post_ID ?>" target="_blank">
                Send Push
            </a>
        </div>


    </div>

    <hr>


    <? include 'comment-form.php' ?>

    <!-- TODO: Comment order -->
    <?php if (count($comments)) { ?>
        <section class="mt-2">
            <span>Comments</span>
            <?php
            $comments = $post['comments'];
            foreach ($comments as $comment) {
                // print_r($comment)
                $comment_content_autop = $comment['comment_content_autop'];
                $comment_author = $comment['comment_author'];
                $comment_date = $comment['comment_date'];
                $short_date_time = $comment['short_date_time'];
                $depth = $comment['depth'];
                $files = $comment['files'];
            ?>
                <!-- TODO: comment sorting between brothers. -->
                <div class="card p-2 mt-2 border border-dark" depth="<?= $depth ?>">
                    <article :class="{ 'd-none': editNo == <?= $comment['comment_ID'] ?>, }">
                        ID: <?= $comment['comment_ID'] ?> |
                        Author: <?php echo $comment_author ?>
                        <div>
                            Content: <?= $comment['comment_content'] ?> <br>
                            Comment Parent: <?= $comment_parent ?? 0 ?>
                            <? if ( count($files) ) { ?>
                            <div class="bg-light">
                                <? foreach($files as $file) {?>
                                <div><img class="w-100" src="<?= $file['url'] ?>"></div>
                                <? } ?>
                            </div>
                            <? } ?>
                            <!-- TODO: MINE BUTTON -->
                            <div class="mt-2">
                                <button class="btn btn-secondary mr-2" @click="replyNo=<?= $comment['comment_ID'] ?>">Reply</button>
                                <button class="btn btn-success mr-2" @click="editNo=<?= $comment['comment_ID'] ?>">Edit</button>
                                <button class="btn btn-danger" @click="onCommentDelete('<?= $comment['comment_ID'] ?>')">DELETE</button>
                            </div>
                            <hr>

                            <!-- Comment Reply Form -->
                            <div v-if="replyNo ==<?= $comment['comment_ID'] ?>">
                                <?
                                $comment_parent = $comment['comment_ID'];
                                $comment_content = '';
                                include 'comment-form.php' 
                                ?>
                            </div>
                        </div>
                    </article>

                    <!-- Comment Edit Form -->
                    <div v-if="editNo ==<?= $comment['comment_ID'] ?>">
                        <? 
                        $comment_ID = $comment['comment_ID'];
                        $comment_parent = $comment['comment_parent'];
                        $comment_content = $comment['comment_content'];
                        include 'comment-form.php'
                        ?>
                    </div>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</article>

<!-- <script>
    later(function() {
        app.editNo = 45;
    })
</script> -->