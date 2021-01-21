<?php




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
//         print_r($post);
    ?>

            <a class="d-block" href="<?php echo $post['guid'] ?>">
                <?php echo $post['post_title'] ?>
                By <?php echo $post['author_name'] ?>
            </a>

    <?php } ?>
</section>