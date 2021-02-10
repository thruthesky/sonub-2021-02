
<header class="l-center bg-grey">

    <a href="/?page=admin/home"><?=ln('Dashboard', '관리자 화면')?></a> |
    <a href="/?page=admin/user/list"><?=ln('Users', '사용자')?></a> |
    <a href="/?page=admin/forum/list"><?=ln('Forums', '게시판')?></a> |
    <a href="/?page=admin/push-notification/send"><?=ln('Send Push Notifications', '푸시 알림 전송')?></a> |
    <a href="/?page=admin/files/list">Files</a> |
    <a href="/?page=admin/translations/translations">Translations</a> |
    <a href="/?page=admin/shopping-mall/shopping-mall">쇼핑몰</a> |
    <a href="/?page=admin.settings.settings">Settings</a> |

    <? if ( defined('ADMIN_MENUS') ) { ?>
        <? foreach( ADMIN_MENUS as $menu ) { ?>
            <a href="/?page=admin.in&script=<?=$menu['script']?>"><?=$menu['name']?></a> |
        <? } ?>
    <? } ?>

</header>

<section class="l-center l-content bg-white">
    <?php
    include 'sidebar-left.php';
    ?>
    <section class="l-body-middle mt-3">
