<?php

?>
<h1>Sonub Theme & API version 1.x</h1>
<div class="d-flex justify-content-between">
    <div>

        <a href="/">Home</a>
        <span v-if="notLoggedIn()">
            <a href="/?page=user/register">Register page</a>
            <a href="/?page=user/login">Login page</a>
            </span>
        <span v-if="loggedIn()">
            <a href="/?page=user/profile">Profile page</a>
            <a href="/?page=user/logout">Logout</a>
            </span>
        <a href="/?page=forum/list&category=reminder">Reminder</a>
        <a href="/?page=forum/list&category=qna">QnA</a>
        <a href="/?page=forum/list&category=discussion">Discussion</a>

        <a href="/?page=forum/list&category=community">Community</a>

    </div>
    <div>
        <? if ( admin() ) { ?>
            <a href="/?page=admin/home">Admin</a>
        <? } ?>
        <a class="ms-2" href="/?page=user/settings" v-if="loggedIn()"><i class="fa fa-cog"></i></a>
        <a href="/?page=user/profile"><img class="size-40 circle" :src="user.profile_photo_url" v-if="user && user.profile_photo_url !== 'undefined'"></a>
    </div>
</div>
