<form @submit.prevent="onCommentEditFormSubmit($event)">
    Comment Parent : <?= $comment_parent ?> <br>
    <input type="hidden" name="comment_post_ID" value="<?= $post_ID ?>" />

    <!-- only when editting -->
    <?php if ($comment_ID) { ?>
        <input type="hidden" name="comment_ID" value="<?= $comment_ID ?>" />
    <?php } ?>
    <input type="hidden" name="comment_parent" value="<?= $comment_parent ?? 0 ?>" />
    <!-- NOTE: Setting initial value for textarea using `value` attribute is temporary -->
    <textarea class="form-control" name="comment_content" value="<?= $comment_content ?? '' ?>"></textarea>
    <div class="mt-2">
        <button type="button" class="btn btn-danger" @click="editNo=0;replyNo=0" v-if="editNo || replyNo">Cancel</button>
        <button class="btn btn-success ml-2" type="submit">Submit</button>
    </div>
</form>