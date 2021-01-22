<?php

$arr = explode('/', $_SERVER['REQUEST_URI']);
$post = get_post($arr[1]);





?>
<hr>
<article class="card border border-dark p-2 m-3">
    <h1><?php echo $post->post_title ?></h1>
    <p>
        ID: <?php echo $post->ID ?> <br>
        Content: <?php echo $post->post_content ?>
    </p>

    <!-- TODO: MINE BUTTONS -->
    <div>
        <a class="btn btn-success mr-3" href="/?page=forum/edit&ID=<?php echo $post->ID ?>">Edit</a>
        <button class="btn btn-danger" @click="onPostDelete('<?php echo $post->ID ?>', '<?php echo get_the_category($post->ID)[0]->slug ?>')">
            Delete
        </button>
    </div>

    <hr>
    <form class="d-flex" @submit.prevent="onCommentEditFormSubmit($event)">
        <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID ?>" />
        <input class="form-control" type="text" name="comment_content" placeholder="Input Comment" />
        <button class="btn btn-success ml-2" type="submit">Submit</button>
    </form>

    <!-- TODO: Comment order -->
    <?php if ($post->comment_count) { ?>
        <section class="mt-2">
            <span>Comments</span>
            <?php
            $comments = get_comments(['post_id' => $post->ID]);
            foreach ($comments as $comment) {
                // print_r($comment)
            ?>
                <!-- TODO: comment depth -->
                <div class="card p-2 mt-2 border border-dark" id="comment_<?php echo $comment->comment_ID ?>">
                    ID: <?php echo $comment->comment_ID ?> |
                    Author: <?php echo $comment->comment_author ?>

                    <div style="display: block;" id="comment_content_<?php echo $comment->comment_ID ?>">
                        Content: <?php echo $comment->comment_content ?>

                        <!-- TODO: MINE BUTTON -->
                        <div class="mt-2">
                            <button class="btn btn-secondary mr-2" onclick="toggleCommentReplyDisplay('<?php echo $comment->comment_ID ?>', 'block')">Reply</button>
                            <button class="btn btn-success mr-2" onclick="toggleCommentEditDisplay('<?php echo $comment->comment_ID ?>', 'none')">Edit</button>
                            <button class="btn btn-danger" @click="onCommentDelete('<?php echo $comment->comment_ID ?>')">DELETE</button>
                        </div>
                        <hr>

                        <!-- Comment Reply form -->
                        <form style="display: none;" @submit.prevent="onCommentEditFormSubmit($event)" id="comment_reply_<?php echo $comment->comment_ID ?>">
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID ?>" />
                            <input type="hidden" name="comment_parent" value="<?php echo $comment->comment_ID ?>" />
                            <input class="form-control" type="text" name="comment_content" id="comment_content" placeholder="Input Reply" />
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger" onclick="toggleCommentReplyDisplay('<?php echo $comment->comment_ID ?>', 'none')">Cancel</button>
                                <button class="btn btn-success ml-2" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- Comment edit form -->
                    <div style="display: none;" id="comment_content_edit_<?php echo $comment->comment_ID ?>">
                        <form @submit.prevent="onCommentEditFormSubmit($event)">
                            <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID ?>" />
                            <input type="hidden" name="comment_parent" value="<?php echo $comment->comment_ID ?>" />
                            <input class="form-control" type="text" name="comment_content" id="comment_content" value="<?php echo $comment->comment_content ?>" />
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger" onclick="toggleCommentEditDisplay('<?php echo $comment->comment_ID ?>', 'block')">Cancel</button>
                                <button class="btn btn-success ml-2" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</article>

<script>
    function toggleCommentEditDisplay(elementID, display) {
        var el = document.getElementById('comment_content_' + elementID);
        var el2 = document.getElementById('comment_content_edit_' + elementID)
        el.style.display = display;
        el2.style.display = display == 'block' ? 'none' : 'block';
    }

    function toggleCommentReplyDisplay(elementID, display) {
        var el = document.getElementById('comment_reply_' + elementID);
        el.style.display = display;
    }
</script>