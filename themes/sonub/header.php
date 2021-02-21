<?php

$co = cafe_option();

?>


<header class="d-md-none">
    <? include 'header.mainmenu.mobile.php' ?>
</header>





<header class="d-none d-md-block">
    <nav class="d-flex justify-content-between l-center  mt-2 border-radius-xs greys bg-lighter fs-xs p-a-xs">
        <ul class="list-menu p-menu">
            <li><a href="/">&nbsp; 홈</a></li>
            <li><a href="/?page=cafe.all-posts">전체 글</a></li>
            <? if ( loggedIn() ) { ?>
                <li><a href="/?page=user/profile">회원정보</a></li>
                <a href="/?page=user/logout.submit">로그아웃</a>
            <? } else { ?>
            <? } ?>
        </ul>


        <ul class="list-menu">
            <li><a class="" href="/?page=forum.list&category=admin_advertisement">광고문의</a></li>
            <li><a href="/?page=forum.list&category=admin_inquiry">운영자문의</a></li>
            <li><a href="/?page=cafe/sitemap">전체메뉴</a></li>
            <? if ( admin() ) { ?>
                <li>
                    <a href="/?page=admin/home">Admin(<?= is_localhost() ? 'local' : 'prod' ?>)</a>
                </li>
            <? } ?>
            <li>
                <a class="ms-2" href="/?page=user/settings" v-if="loggedIn()"><i class="fa fa-cog"></i></a>
            </li>
        </ul>
    </nav>


    <section class="logo-search mt-3">
        <div class="l-center d-flex justify-content-center align-items-center">
            <? if ( is_in_cafe() ) { ?>
            <a href="/" class="fs-xxl" style="font-family: Georgia"><?=cafe_option('name', '카페이름')?></a>
            <? } else { ?>
                <a class="d-block" href="/" style="width: 230px; height: 58px; overflow: hidden;">
                    <img class="w-100" src="<?=DOMAIN_THEME_URL?>/img/logo/<?=get_root_domain()?>.jpg">
                </a>
            <? } ?>
            <form class="search ms-3 position-relative">
                <input class="ps-3 pe-5 w-300px h-48px border-radius-md" style="border: 1px solid #7a878a">
                <i class="position-absolute right p-3 fa fa-search"></i>
            </form>
        </div>
    </section>



    <? include 'header.mainmenu.desktop.php' ?>

</header>

<section class="l-center l-content bg-white">
<?php
  include 'sidebar.left.php';
?>
    <section class="l-body-middle mt-3">
