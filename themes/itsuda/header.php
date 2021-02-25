

    <nav class="d-flex justify-content-between l-center bg-light fs-xs greys">
        <ul class="list-menu p-menu">
            <li><a href="/">홈</a></li>
            <? if ( loggedIn() ) { ?>
                <li><a href="/?page=user/logout.submit">로그아웃</a></li>
            <? } else { ?>
                <li><a href="/?page=user/login">로그인</a></li>
            <? } ?>

        </ul>



        <ul class="list-menu">
            <? if ( admin() ) { ?>
                <li>
                    <a href="/?page=admin/home">관리자페이지</a>
                </li>
            <? } ?>
        </ul>
    </nav>



    <section class="l-center p-3 p-lg-0 bg-white">
