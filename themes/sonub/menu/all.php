
    <ul>
        <li><a href="/">홈</a></li>
        <li><a href="/?page=user/login">로그인</a></li>
        <li><a href="/?page=user/register">회원가입</a></li>
        <li><a href="/?page=user/profile">회원정보</a></li>

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
