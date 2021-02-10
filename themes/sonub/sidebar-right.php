<section class="l-sidebar d-none d-lg-block mt-3">
    <div class="box">
        <div class="fs-xs">
            카카오톡, 네이버, 패스 로그인을 해주세요.
        </div>

        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/kakao-login.png">
        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/naver-login.png?v=4">
    </div>

    <?php
    for( $i=0; $i<10; $i++)
    include dynamic_widget("cafe-right-sidebar-widget-$i");
    ?>

        <?php
        include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항']);
        ?>

        <?php
        include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변']);
        ?>

</section>
