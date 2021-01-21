<?php

require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/routes/forum.route.php');


$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'qna';
?>

<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List</h2>
    <a class="btn btn-success" href="/?page=forum/edit&category=<?php echo $category ?>">Create</a>
</div>
<hr>

<section class="post-list p-2">
    <?php
    $posts = forum_search(['category_name' => $category, 'posts_per_page' => 20]);

    foreach ($posts as $post) { 
        // print_r($post);
    ?>
        <div class="card mb-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $post['post_title'] ?></h5>
                <p class="card-text">By <?php echo $post['author_name'] ?></p>
                <p class="card-text"><?php echo $post['post_content'] ?></p>

                <div class="d-flex justify-content-between">
                
                <a href="#" class="btn btn-primary">Edit</a>
                <button class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    <?php } ?>
</section>