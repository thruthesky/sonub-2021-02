<?php
/**
 * @file latest.ph
 */
/**
 * Options
 * - $o['category_name'] is the slug
 * - $o[' .... '] - you can use all options that can be used with get_posts()
 * - $o['class'] is the style class
 * - $o['widget_title'] is the widget title.
 */
$o = get_widget_options();
$posts = forum_search($o);




?>
<section class="posts-latest box mb-2 <?=$o['class'] ?? ''?>">
    <a class="d-flex justify-content-between" href="/?page=forum/list&category=<?=$o['category_name']?>">
        <h2 class="fs-normal"><?=$o['widget_title'] ?? ''?></h2>
        <i class="fa fa-angle-double-right"></i>
    </a>
    <hr>
    <? foreach( $posts as $post ) { ?>
        <div class="post">
            <a href="<?=$post['url']?>" class="post-title fs-normal"><?=$post['post_title']?></a>
        </div>
    <? } ?>
</section>


