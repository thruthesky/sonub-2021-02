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
run_hook('widgets/posts/latest-photos option', $o);
include THEME_DIR . '/etc/widget/config.styles.php';
$posts = latest_photos($o);


?>
<section class="<?=$o['widget_id']?> latest-photos box mb-2 <?=$o['class'] ?? ''?>">
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
                <a class="p-1 fs-normal" href="<?=$post['url']?>">
                    <img class="size-100" src="<?=$post['files'][0]['thumbnail_url']?>">
                    <div class="p-1 fs-xs h-2em"><?=$post['post_title']?></div>
                </a>
            </div>
        <? } ?>
    </div>
</section>

<style>
    .latest-photos .posts .post:first-child a {
        padding-left: 0!important;
    }
    .latest-photos .posts .post:last-child a {
        padding-right: 0!important;
    }
</style>

