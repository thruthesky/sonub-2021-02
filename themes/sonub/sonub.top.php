<nav class="d-flex d-sm-none justify-content-between bg-light">
    <div class="d-flex">
        <a class="p-2" href="/">필럽</a>
        <a class="p-2" href="/?page=forum/intro">게시판</a>
        <a class="p-2" href="/?page=cafe/intro">카페</a>
        <a class="p-2" href="/?page=buyandsell/intro">회원장터</a>
    </div>
    <div>
        <? if ( App::page('menu/all') ) { ?>
            <a href="/"><i class="fa fa-times-circle p-2 fs-lg black"></i></a>
        <? } else { ?>
            <a href="/?page=menu/all"><i class="fa fa-bars p-2 fs-lg"></i></a>
        <? } ?>
    </div>
</nav>

<nav class="d-none d-sm-flex justify-content-between bg-light">
    <ul class="list-menu fs-sm">
        <li><a href="/">홈</a></li>
        <li><a href="/?page=user/login">로그인</a></li>
        <li><a href="/?page=user/logout">로그아웃</a></li>
        <li><a href="/?page=user/register">회원가입</a></li>
        <li><a href="/?page=user/profile">회원정보</a></li>
    </ul>


    <ul class="list-menu fs-sm">
        <li><a href="#">광고문의</a></li>
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
