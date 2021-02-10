<?php

$co = cafe_option();

?>

<style>
    /*.l-center {*/
    /*    max-width: 800px;*/
    /*}*/
    .desktop-mainmenu {
        border-top: 1px solid #efefef; border-bottom: 1px solid #d0d0d0; box-shadow: 1px 1px 1px 1px #f8f8f8;
    }
</style>
<header class="d-md-none">
    <nav class="d-flex justify-content-between bg-light">
        <div class="d-flex">
            <a class="p-2" href="/">필럽</a>
            <a class="p-2" href="/?page=forum.list&cafe">게시판</a>
            <a class="p-2" href="/?page=cafe/intro">카페</a>
            <a class="p-2" href="/?page=buyandsell/intro">회원장터</a>
        </div>
        <div class="d-flex align-items-center">
            <? if ( App::page('menu/all') ) { ?>
                <a href="/"><i class="fa fa-times-circle p-2 fs-lg black"></i></a>
            <? } else { ?>
                <? if (loggedIn()) { ?>
                    <a href="/?page=user/logout.submit">로그아웃</a>
                <? } else { ?>
                    <a href="/?page=user/login">로그인</a>
                <? } ?>
                <a href="/?page=menu/all"><i class="fa fa-bars p-2 fs-lg"></i></a>
            <? } ?>
        </div>
    </nav>
</header>




<header class="d-none d-md-block">
    <nav class="d-flex justify-content-between l-center bg-light fs-xs greys">
        <ul class="list-menu p-menu">
            <li><a href="/">홈</a></li>
            <? if ( loggedIn() ) { ?>
                <li><a href="/?page=user/profile">회원정보</a></li>
                <li><a href="/?page=user/logout.submit">로그아웃</a></li>
            <? } else { ?>
                <li><a href="/?page=user/login">로그인</a></li>
                <li><a href="/?page=user/register">회원가입</a></li>
            <? } ?>

        </ul>



        <ul class="list-menu">
            <li><a class="" href="#">광고문의</a></li>
            <li><a href="#">운영자문의</a></li>
            <li><a href="/?page=menu/all">전체메뉴</a></li>
            <? if ( admin() ) { ?>
                <li>
                    <a href="/?page=admin/home">Admin</a>
                </li>
            <? } ?>
            <li>
                <a class="ms-2" href="/?page=user/settings" v-if="loggedIn()"><i class="fa fa-cog"></i></a>
            </li>
            <li>
                <a href="/?page=user/profile"><img class="size-40 circle" :src="user.profile_photo_url" v-if="user && user.profile_photo_url !== 'undefined'"></a>
            </li>
        </ul>
    </nav>



    <section class="logo-search">
        <div class="l-center d-flex justify-content-center align-items-center">
            <a href="/" class="fs-xxl" style="font-family: Georgia"><?=cafe_option('name', 'SONUB')?></a>
            <form class="ms-3">
                <input class="w-300px h-48px">
            </form>
        </div>
    </section>

    <? include 'header.desktop.mainmenu.php' ?>

</header>

<section class="l-center l-content bg-white">
<?php
  include 'sidebar.left.php';
?>
    <section class="l-body-middle mt-3">
