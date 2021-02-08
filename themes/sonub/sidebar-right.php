<section class="l-sidebar d-none d-lg-block mt-3">
    <div class="box">
        <div class="fs-xs">
            카카오톡, 네이버, 패스 로그인을 해주세요.
        </div>

        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/kakao-login.png">
        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/naver-login.png?v=4">
    </div>

    <?php
    include dynamic_widget("cafe-right-sidebar-widget-1");
    include dynamic_widget("cafe-right-sidebar-widget-2");
    include dynamic_widget("cafe-right-sidebar-widget-3");
    include dynamic_widget("cafe-right-sidebar-widget-4");
    include dynamic_widget("cafe-right-sidebar-widget-5");
    include dynamic_widget("cafe-right-sidebar-widget-6");
    include dynamic_widget("cafe-right-sidebar-widget-7");
    include dynamic_widget("cafe-right-sidebar-widget-8");
    include dynamic_widget("cafe-right-sidebar-widget-9");
    include dynamic_widget("cafe-right-sidebar-widget-10");
    ?>

        <?php
        include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항']);
        ?>

        <?php
        include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변']);
        ?>

</section>
