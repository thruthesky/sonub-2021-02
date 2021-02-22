<section class="left-sidebar l-sidebar d-none d-md-block mt-3">


    <?php
    // 로그인을 한 경우,
    if ( loggedIn() ) {
        if ( is_cafe_admin() ) {
            include widget('cafe/admin', ['class' => 'border-radius-md']);
        } else {
            include widget('login/info', ['class' => 'border-radius-md']);
        }
    } else {
        include widget('login/social-login', ['class' => 'login border-radius-md']);
    }

    //
    if ( has_widget_of("cafe-left-sidebar-widget") || is_widget_edit_mode() ) {
        for( $i=0; $i<NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-left-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('posts/latest', ['widget_title' => '최근 글 모음', 'class' => 'border-radius-md']);
        include widget('posts/latest', ['widget_title' => '질문 게시판 최근 글', 'class' => 'border-radius-md']);
        include widget('posts/latest', ['widget_title' => '자유 게시판 최근 글', 'class' => 'border-radius-md']);
    }

    ?>

    <div class="box border-radius-md" style="padding: .25em;">
        <div class="p-2">
            <div class="p-1 pb-0">
                퀵메뉴
            </div>
        </div>
        <hr style="margin: 0 .5em">
        <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
            <a class="p-1" href="/?page=cafe.all-posts">전체글</a>
            <? foreach( cafe_categories_parent_without_child() as $slug => $menu ) { ?>
                <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
            <? } ?>
        </div>

        <hr style="margin: 0 .5em">

        <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
            <a class="p-1 bold" href="<?=cafe_url('buyandsell')?>">회원장터</a>
            <? foreach( cafe_categories_of('buyandsell') as $slug => $menu ) { ?>
                <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
            <? } ?>
        </div>

        <hr style="margin: 0 .5em">

        <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
            <a class="p-1 bold" href="<?=cafe_url('business')?>">사업정보</a>
            <? foreach( cafe_categories_of('business') as $slug => $menu ) { ?>
                <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
            <? } ?>
        </div>

    </div>

</section>

