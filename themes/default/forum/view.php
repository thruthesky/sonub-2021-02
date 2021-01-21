<?php
$post = get_post_from_guid(home_url() . $_SERVER['REQUEST_URI']);
?>
Post view:
<hr>
<article>
    <h1><?php echo $post->post_title ?></h1>
    <p>
        ID: <?php echo $post->ID ?> <br>
        Content: <?php echo $post->post_content ?>
    </p>

    <a class="btn btn-success mr-3" href="/?page=forum/edit&ID=<?php echo $post->ID ?>">Edit</a>
    <button 
        class="btn btn-danger"
        @click="onPostDelete('<?php echo $post->ID ?>', '<?php echo get_the_category($post->ID)[0]->slug ?>')">
        Delete
    </button>
</article>