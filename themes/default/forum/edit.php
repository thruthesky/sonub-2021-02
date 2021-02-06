<?php

$category = '';
$post = null;


if (isset($_REQUEST['category'])) {
    $category = $_REQUEST['category'];
} else {
    $post = get_post($_REQUEST['ID']);
    $category = get_the_category($post->ID)[0]->slug;
}

?>

    <h1> POST EDIT : <?php echo $category ?></h1>

    <form @submit.prevent="onPostEditFormSubmit($event)">
        <?php if ($post != null) { ?> <input type="hidden" id="ID" name="ID" value="<?php echo $post->ID ?>"> <?php } ?>
        <input type="hidden" id="category" name="category" value="<?php echo $category ?>">
        <div class="form-group">
            <label for="post_title">Title</label>
            <input type="text" class="form-control" id="post_title" name="post_title" value="<?php echo $post != null ? $post->post_title : '' ?>">
        </div>
        <div class="form-group">
            <label for="register_user_pass">Content</label>
            <textarea class="form-control" id="post_content" name="post_content" rows="10"></textarea>
        </div>


        <div class="d-flex justify-content-between mt-2">
            <div class="position-relative d-inline-block of-hidden">
                <i class="fa fa-camera fs-xl"></i>
                <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileUpload($event, onPostEditUploadSuccess)">
            </div>

            <div>
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

<div class="progress mt-3" style="height: 5px;" v-if="uploadPercentage > 0">
    <div class="progress-bar" role="progressbar" :style="{width: uploadPercentage + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>

<section class="files">
    <div class="d-inline-block position-relative size-100 p-1 bg-light border" v-for="file of files">
        <img class="size-100" :src="file.url">
        <i class="fa fa-trash fs-lg position-absolute top left" @click="onFileDelete(file.ID, null, files)"></i>
    </div>
</section>



<script>


    <?php if ( in('ID') ) { /** Load post data if it's editing */?>
    later(function() {
        const textarea = document.getElementById('post_content');
        request('forum.getPost', {id: <?=in('ID')?>}, function (res) {
            document.getElementById('post_content').value = res.post_content;
            app.files = res.files;
        }, app.error);
    })
    <?php } ?>


</script>
