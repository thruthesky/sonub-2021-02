<?php
global $post_ID, $comment_ID, $comment_parent;
?>
<form @submit.prevent="onCommentEditFormSubmit($event)">
    <input type="hidden" name="comment_post_ID" value="<?= $post_ID ?>" />

    <!-- only when editting -->
    <?php if ($comment_ID) { ?>
        <input type="hidden" name="comment_ID" value="<?= $comment_ID ?>" />
    <?php } ?>

    <?php if ($comment_parent) { ?>
        <input type="hidden" name="comment_parent" value="<?= $comment_parent ?>" />
    <?php } ?>


    <div class="d-flex">

        <div class="position-relative d-inline-block of-hidden">
            <i class="fa fa-camera fs-xl"></i>
            <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileUpload($event, onCommentEditUploadSuccess)">
        </div>

        <!-- NOTE: Setting initial value for textarea using `value` attribute is temporary. This may not work on IE and Safari -->
        <textarea class="form-control" name="comment_content" value="<?= $comment_content ?? '' ?>"></textarea>

    </div>

    <div class="mt-2">
        <button type="button" class="btn btn-secondary" @click="onCancelCommentEditForm" v-if="canCancel">Cancel</button>
        <button class="btn btn-success ml-2" type="submit"  v-if="canSubmit">Submit</button>
    </div>
</form>