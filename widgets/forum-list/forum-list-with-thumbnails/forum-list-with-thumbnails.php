<?php
$category = get_category_by_slug(in('category'));
$page_no = in('page_no', 1);
$posts_per_page = category_meta($category->ID, 'posts_per_page', POSTS_PER_PAGE);

$offset = ($page_no - 1) * $posts_per_page;
$q = ['category_name' => $category->slug, 'posts_per_page' => $posts_per_page, 'offset' => $offset];
$posts = forum_search($q);

?>
<section class="post-list p-2">
    <?php
    foreach ($posts as $post) {
        ?>
        <a class="d-flex justify-content-between mb-2" href="<?php echo $post['url'] ?>">

            <div class="d-flex">
                <? if ( $post['profile_photo_url'] ) { ?>
                    <img class="me-3 size-40 circle" src="<?= $post['profile_photo_url'] ?>">
                <? } ?>
                <? if ( $post['files'] ) { ?>
                    <img class="size-40" src="<?=$post['files'][0]['thumbnail_url']?>">
                <? } ?>
                <h1>
                    <?php echo $post['post_title'] ?>
                </h1>
            </div>

            <div class="meta">
                By <?php echo $post['author_name'] ?>
            </div>
        </a>
    <?php } ?>
</section>
