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
include THEME_DIR . '/etc/widget/config.styles.php';

$posts = latest_photos([
    'category_name' => $o['left'] ?? '',
    'posts_per_page' => 1,
]);
if ( empty($posts) ) {
    $big = [
            'ID'=> 0,
        'post_title' => "컬럼 위젯에 글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.",
        'files' => [
                ['thumbnail_url' => THEME_URL . '/img/xbox.jpg', ],
        ]
    ];
} else {
    $big = $posts[0];
}


$left_posts = latest_search([
    'category_name' => $o['left'] ?? '',
    'posts_per_page' => 6,
    'post__not_in' => [$big['ID']],
]);
if ( count($left_posts) == 0 ) {
    for($i = 0; $i < 6; $i ++ ) {
        $left_posts[] = [
            'ID'=> 0,
            'post_title' => "컬럼 위젯에 글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.",
        ];
    }
}

$right_photos = latest_photos([
    'category_name' => $o['right'] ?? '',
    'posts_per_page' => 4,
]);




if ( count($right_photos) == 0 ) {
    for($i = 0; $i < 4; $i ++ ) {
        $right_photos[] = [
            'ID'=> 0,
            'post_title' => "컬럼 위젯에 글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.",
            'files' => [
                ['thumbnail_url' => THEME_URL . '/img/xbox.jpg', ],
            ]
        ];
    }
}




$right_posts = latest_search([
    'category_name' => $o['right'] ?? '',
    'posts_per_page' => 3,
    'post__not_in' => App::getIDs($right_photos),
]);




if ( count($right_posts) == 0 ) {
    for($i = 0; $i < 3; $i ++ ) {
        $right_posts[] = [
            'ID'=> 0,
            'post_title' => "컬럼 위젯에 글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.",
        ];
    }
}





?>

<div class="container">
    <section class="<?=$o['widget_id']?> posts-multi-column mb-2 row px-0 py-2 <?=$o['class'] ?? ''?>">
        <div class="col-12 col-sm-6">
            <a class="d-block" href="<?=$big['url']?>">
                <div class="of-hidden">
                    <img class="w-100 border-radius-md" style="height: 207px;" src="<?=image_url($big)?>">
                </div>
                <div class="mt-2 fs-sm"><?=$big['post_title']?></div>
            </a>
            <hr style="margin: .4em 0;">
            <div class="posts">
                <? foreach ($left_posts as $post) { ?>
                    <a class="d-block fs-sm h-1em" href="<?=$post['url']?>"><?=$post['post_title']?></a>
                <? } ?>
            </div>
        </div>


        <div class="col-12 col-sm-6">
            <div class="posts">
                <? foreach ($right_posts as $post) { ?>
                    <a class="d-block fs-sm h-1em" href="<?=$post['url']?>"><?=$post['post_title']?></a>
                <? } ?>
            </div>

            <div class="small-photos mt-2">
                <div class="small-photos mt-2">
                    <?
                    if ( isset($right_photos[0]) ) {
                        $post = $right_photos[0]; ?>
                        <a class="d-flex mt-2" href="<?=$post['url']?>">
                            <img class="size-64 border-radius-md" src="<?=image_url($post)?>">
                            <div class="ms-2 fs-sm h-64px of-hidden"><?=$post['post_title']?></div>
                        </a>
                    <? } ?>
                    <?
                    if ( isset($right_photos[1]) ) {
                        $post = $right_photos[1]; ?>
                        <a class="d-flex mt-2" href="<?=$post['url']?>">
                            <img class="size-64 border-radius-md" src="<?=image_url($post)?>">
                            <div class="ms-2 fs-sm h-64px of-hidden"><?=$post['post_title']?></div>
                        </a>
                    <? } ?>
                </div>
            </div>

            <div class="medium-photos d-flex justify-content-between mt-2">
                <?  if ( isset($right_photos[2]) ) {
                    $post = $right_photos[2]; ?>
                    <a class="s-48" href="<?=$post['url']?>">
                        <img class="w-100 border-radius-md" src="<?=image_url($post)?>">
                        <div class="mt-2 h-2em fs-xs"><?=$post['post_title']?></div>
                    </a>
                <? } ?>
                <?  if ( isset($right_photos[3]) ) {
                    $post = $right_photos[3]; ?>
                    <a class="s-48" href="<?=$post['url']?>">
                        <img class="w-100 border-radius-md" src="<?=image_url($post)?>">
                        <div class="mt-2 h-2em fs-xs"><?=$post['post_title']?></div>
                    </a>
                <? } ?>
            </div>

        </div>



    </section>
</div>




