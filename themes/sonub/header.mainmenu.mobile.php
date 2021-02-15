
<nav class="d-flex justify-content-between bg-light">
    <div class="d-flex">
        <a class="p-2" href="/">홈</a>
        <a class="p-2" href="/?page=forum.list&cafe">게시판</a>
        <a class="p-2" href="/?page=cafe/intro">카페</a>
        <a class="p-2" href="/?page=buyandsell/intro">회원장터</a>
    </div>
    <div class="d-flex align-items-center">
        <? if ( App::page('menu/all') ) { ?>
            <a href="/"><i class="fa fa-times-circle p-2 fs-lg black"></i></a>
        <? } else { ?>
            <a :href="loggedIn() ? '/?page=user/profile' : '/?page=user/login'"><img class="size-32 circle" :src="profile_photo_url()"></a>
            <a href="/?page=menu/all"><i class="fa fa-bars p-2 fs-lg"></i></a>
        <? } ?>
    </div>
</nav>
