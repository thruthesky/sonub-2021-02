<?php
/**
 * @file latest.ph
 */
/**
 * Required options
 * - $o['category_name'] 이 slug 이다. 변수명이 이렇게 밖에 안되는 이유 => README # widget 참고
 * - $o[' .... '] - you can use all options that can be used with get_posts()
 * - $o['class'] is the style class
 * - $o['widget_title'] is the widget title.
 */
$o = get_widget_options();
run_hook('widgets/posts/latest option', $o);
include THEME_DIR . '/etc/widget/config.styles.php';
$posts = forum_search($o);


?>
<section class="<?=$o['widget_id']?> posts-latest mb-2 p-3 <?=$o['class'] ?? ''?>">
    <a class="d-flex justify-content-between" href="/?page=forum/list&category=<?=$o['category_name'] ?? ''?>">
        <h2 class="fs-normal"><?=$o['widget_title'] ?? ''?></h2>
        <i class="fa fa-angle-double-right"></i>
    </a>
    <hr style="margin: .7em 0; color: #a0a0a0;">
    <div class="posts fs-sm">
        <? foreach( $posts as $post ) { ?>
            <a class="post-title d-block pt-1" href="<?=$post['url']?>"><?=$post['post_title']?></a>
        <? } ?>
    </div>
</section>


