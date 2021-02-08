<section class="l-sidebar d-none d-md-block mt-3">


    <?php
    if ( is_cafe_admin() ) {
        include widget('cafe/admin');
    } else { ?>
        <div class="box mb-2">
            <div class="fs-xs">
                카카오톡, 네이버 로그인 후 본인 인증을합니다.
            </div>

            <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/kakao-login.png">
            <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/naver-login.png?v=4">
        </div>
    <? } ?>


    <?php

    include dynamic_widget("cafe-left-sidebar-widget-1");
    include dynamic_widget("cafe-left-sidebar-widget-2");
    include dynamic_widget("cafe-left-sidebar-widget-3");
    include dynamic_widget("cafe-left-sidebar-widget-4");
    include dynamic_widget("cafe-left-sidebar-widget-5");
    include dynamic_widget("cafe-left-sidebar-widget-6");
    include dynamic_widget("cafe-left-sidebar-widget-7");
    include dynamic_widget("cafe-left-sidebar-widget-8");
    include dynamic_widget("cafe-left-sidebar-widget-9");
    include dynamic_widget("cafe-left-sidebar-widget-10");

    include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항']);
    ?>

    <?php
    include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변']);
    ?>

</section>
