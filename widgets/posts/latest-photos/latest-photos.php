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
$posts = latest_photos($o);





?>
<section class="posts-latest box mb-2 <?=$o['class'] ?? ''?>">
    <a class="d-flex justify-content-between" href="/?page=forum/list&category=<?=$o['category_name'] ?? ''?>">
        <h2 class="fs-normal"><?=$o['widget_title'] ?? ''?></h2>
        <i class="fa fa-angle-double-right"></i>
    </a>
    <hr>

    <div class="posts d-flex">
        <? foreach( $posts as $post ) {
if (           empty($post['files']) ) continue;
            ?>
            <div class="post w-100px of-hidden">
                <a href="<?=$post['url']?>" class="post-title fs-normal">
                    <img class="size-100" src="<?=$post['files'][0]['thumbnail_url']?>">
                    <div class="fs-xs"><?=$post['post_title']?></div>
                </a>
            </div>
        <? } ?>
    </div>
</section>



