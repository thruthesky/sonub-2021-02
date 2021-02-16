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
$posts = forum_search($o);


?>
<section class="posts-latest box mb-2 <?=$o['class'] ?? ''?>">
    <a class="d-flex justify-content-between" href="/?page=forum/list&category=<?=$o['category'] ?? ''?>">
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


