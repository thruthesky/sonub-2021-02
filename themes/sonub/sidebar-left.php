<section class="l-sidebar d-none d-md-block mt-3">
    <div class="box mb-2">
        <div class="fs-xs">
            카카오톡, 네이버 로그인 후 본인 인증을합니다.
        </div>

        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/kakao-login.png">
        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/naver-login.png?v=4">
    </div>


        <?php
        include dynamic_widget("local-left-1");
        include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항']);
        ?>

        <?php
        include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변']);
        ?>

</section>
