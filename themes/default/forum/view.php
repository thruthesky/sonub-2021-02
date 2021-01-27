<?php

$arr = explode('/', $_SERVER['REQUEST_URI']);
$post_ID = $arr[1];

//$post = get_post($arr[1]);

$post = post_response($post_ID);
$comments = $post['comments'];

?>
<hr>
<article class="card border border-dark p-2 m-3">
    <h1><?php echo $post['post_title'] ?></h1>
    <p>
        ID: <?php echo $post_ID ?> <br>
        Content: <?php echo $post['post_content'] ?>
    </p>

    <!-- TODO: MINE BUTTONS -->
    <div>
        <a class="btn btn-success mr-3" href="/?page=forum/edit&ID=<?php echo $post_ID ?>">Edit</a>
        <button class="btn btn-danger" @click="onPostDelete('<?php echo $post_ID ?>', '<?php echo get_the_category($post_ID)[0]->slug ?>')">
            Delete
        </button>
    </div>

    <hr>

    <form class="d-flex" @submit.prevent="onCommentEditFormSubmit($event)">
        <input type="hidden" name="comment_post_ID" value="<?php echo $post_ID ?>" />
        <div style="overflow: hidden;" class="position-relative mr-2">
            <input style="opacity: 0" class="position-absolute" type="file" name="file" @change="onFileChange($event)" />
            <i class="fa fa-camera fs-xl"></i>
        </div>
        <input class="form-control" type="text" name="comment_content" placeholder="Input Comment" v-model="commentEditForm.comment_content" />
        <button class="btn btn-success ml-2" type="submit" v-if="commentEditFormCanSubmit()">Submit</button>
    </form>

    Upload Progress : {{ uploadProgress }}

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
                $comment_content_autop = $comment['comment_content_autop'];
                $comment_author = $comment['comment_author'];
                $comment_parent = $comment['comment_parent'];
                $comment_date = $comment['comment_date'];
                $short_date_time = $comment['short_date_time'];
                $depth = $comment['depth'];
                $files = $comment['files'];
            ?>
                <!-- TODO: comment depth -->
                <div class="card p-2 mt-2 border border-dark" id="comment_<?php echo $comment_ID ?>" depth="<?= $depth ?>">
                    ID: <?php echo $comment_ID ?> |
                    Author: <?php echo $comment_author ?>

                    <div style="display: block;" id="comment_content_<?php echo $comment_ID ?>">
                        Content: <?php echo $comment_content ?>

                        <!-- TODO: MINE BUTTON -->
                        <div class="mt-2">
                            <button class="btn btn-secondary mr-2" @click="toggleCommentReplyDisplay('<?php echo $comment_ID ?>', 'block')">Reply</button>
                            <button class="btn btn-success mr-2" @click="toggleCommentEditDisplay('<?php echo $comment_ID ?>', 'none')">Edit</button>
                            <button class="btn btn-danger" @click="onCommentDelete('<?php echo $comment_ID ?>')">DELETE</button>
                        </div>
                        <hr>

                        <!-- Comment Reply form -->
                        <form @submit.prevent="onCommentEditFormSubmit($event)">
                            <!-- always present -->
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post_ID ?>" />
                            <!-- only when reply -->
                            <input type="hidden" name="comment_parent" value="<?php echo $comment_ID ?>" />
                            <input class="form-control" type="text" placeholder="Input Comment" v-model="commentEditForm.comment_content" />
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger" @click="toggleCommentReplyDisplay('<?php echo $comment_ID ?>', 'none')">Cancel</button>
                                <button class="btn btn-success ml-2" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- Comment edit form -->
                    <div style="display: none;" id="comment_content_edit_<?php echo $comment_ID ?>">
                        <form @submit.prevent="onCommentEditFormSubmit($event)">
                            <input type="hidden" name="comment_post_ID" value="<?php echo $post_ID ?>" />
                            <input type="hidden" name="comment_ID" value="<?php echo $comment_ID ?>" />
                            <input class="form-control" type="text" name="comment_content" id="comment_content" value="<?php echo $comment_content ?>" />
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger" @click="toggleCommentEditDisplay('<?php echo $comment_ID ?>', 'block')">Cancel</button>
                                <button class="btn btn-success ml-2" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</article>