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
$posts = latest_photos([
    'category_name' => $o['left']['category_name'] ?? '',
    'posts_per_page' => 1,
]);
if ( empty($posts) ) {

        echo "글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.<hr>@TODO: 기본 사진이나 글을 보여 줄 것.";
        return;

}
$big = $posts[0];

$left_posts = latest_search([
    'category_name' => $o['left']['category_name'] ?? '',
    'posts_per_page' => 7,
    'post__not_in' => [$big['ID']],
]);




$right_photos = latest_photos([
    'category_name' => $o['right']['category_name'] ?? '',
    'posts_per_page' => 4,
]);





$right_posts = latest_search([
    'category_name' => $o['right']['category_name'] ?? '',
    'posts_per_page' => 4,
    'post__not_in' => App::getIDs($right_photos),
]);

if ( count($right_photos) != 4 ) {
    echo "글 수가 충분하지 않아 위젯을 표시 할 수 없습니다.";
    return;
}

?>
<div class="container">
    <section class="posts-multi-column box mb-2 row <?=$o['class'] ?? ''?>">
        <div class="col-12 col-sm-6">
            <a class="d-block" href="<?=$big['url']?>">
                <div class="h-xxxl of-hidden">
                    <img class="w-100" src="<?=$big['files'][0]['thumbnail_url']?>">
                </div>
                <div class="mt-1 fs-md"><?=$big['post_title']?></div>
            </a>
            <hr>
            <div class="posts">
                <? foreach ($left_posts as $post) { ?>
                    <a class="d-block h-1em" href="<?=$post['url']?>"><?=$post['post_title']?></a>
                <? } ?>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="posts">
                <? foreach ($right_posts as $post) { ?>
                    <a class="d-block h-1em" href="<?=$post['url']?>"><?=$post['post_title']?></a>
                <? } ?>
            </div>
            <div class="small-photos">
                <? for($i=0; $i<2; $i++) { $post = $right_photos[$i]; ?>
                    <a class="d-flex mt-1" href="<?=$post['url']?>">
                        <img class="size-64" src="<?=$post['files'][0]['thumbnail_url']?>">
                        <div class="fs-md h-64px of-hidden"><?=$post['post_title']?></div>
                    </a>
                <? } ?>
            </div>
            <div class="medium-photos d-flex justify-content-between mt-1">
                <? for($i=2; $i<4; $i++) { $post = $right_photos[$i]; ?>
                    <a class="s-49" href="<?=$post['url']?>">
                        <img class="w-100" src="<?=$post['files'][0]['thumbnail_url']?>">
                        <div class="h-2em fs-xs"><?=$post['post_title']?></div>
                    </a>
                <? } ?>
            </div>
        </div>
    </section>
</div>





