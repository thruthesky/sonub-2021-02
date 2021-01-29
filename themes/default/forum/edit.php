<?php

$category = '';
$post = null;


if (isset($_REQUEST['category'])) {
    $category = $_REQUEST['category'];
} else {
    $post = get_post($_REQUEST['ID']);
    $category = get_the_category($post->ID)[0]->slug;
}
//echo ($post);
//echo ($post->post_content);
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
        <textarea class="form-control" id="post_content" name="post_content" rows="10"><?php echo $post != null ? $post->post_content : '' ?></textarea>
    </div>
    <file-upload-form></file-upload-form>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


<script>
    addComponent('file-upload-form', {
        template: '<form>' +
            '<input type="file">' +
            '</form>'
    });

</script>
