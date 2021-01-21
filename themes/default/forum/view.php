<?php
    $post = get_post_from_guid( home_url() . $_SERVER['REQUEST_URI'] );
?>
<article>

    <h1>Post view: <?php echo $post->post_title ?></h1>
    <p>
        <?php echo $post->post_content ?>
    </p>
</article>

