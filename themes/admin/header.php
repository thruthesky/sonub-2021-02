<?php
if ( !admin() ) {
    jsBack('You are not admin!');
    exit;
}
function is_admin_home(): bool {
    return strpos( in('page'), 'admin/home' ) !== false;
}
function is_admin_user_page(): bool {
    return strpos( in('page'), 'admin/user' ) !== false;
}
function is_admin_forum_page(): bool {
    return strpos( in('page'), 'admin/forum' ) !== false;
}
?>
<style>
    .option-box {
        padding: 1em;
        border-radius: 25px;
        background-color: #f3f3f3;
    }
    .option-box h2 {
        font-size: 1.2em;
        font-weight: normal;
    }
</style>
<header class="l-center bg-grey fs-sm">
    <a class="ps-4" href="/?page=admin/home"><?=ln('Dashboard', '홈')?></a>
    <a href="/?page=admin/user/list"><?=ln('Users', '사용자')?></a>
    <a href="/?page=admin/forum/list"><?=ln('Forums', '게시판')?></a>
    <a href="/?page=admin/forum/point"><?=ln("Point", "포인트")?></a>
    <a href="/?page=admin/push-notification/send"><?=ln('Send Push Notifications', '푸시 알림 전송')?></a>
    <a href="/?page=admin/files/list"><?=ln('Files', '업로드파일')?></a>
    <a href="/?page=admin/translations/translations"><?=ln('Translations', '언어화')?></a>
    <a href="/?page=admin/shopping-mall/shopping-mall">쇼핑몰</a>
    <a href="/?page=admin.settings.settings"><?=ln('Settings', '설정')?></a>

    <? if ( defined('ADMIN_MENUS') ) { ?>
        <? foreach( ADMIN_MENUS as $menu ) { ?>
            <a href="/?page=admin.in&script=<?=$menu['script']?>"><?=$menu['name']?></a>
        <? } ?>
    <? } ?>

</header>

<section class="l-center l-content fs-sm">
    <?php
    include 'sidebar.left.php';
    ?>
    <section class="l-body-middle mt-3">
