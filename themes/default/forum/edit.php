<?php





$category = $_REQUEST['category'];
?>

<h1> POST EDIT : <?php echo $category ?></h1>

<form @submit.prevent="onPostEditFormSubmit('<?php echo $category?>')">
    <div class="form-group">
        <label for="post_title">Title</label>
        <input type="text" class="form-control" id="post_title" name="post_title" v-model="post.post_title">
    </div>
    <div class="form-group">
        <label for="register_user_pass">Content</label>
        <input type="text" class="form-control" id="post_content" name="post_content" v-model="post.post_content">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>