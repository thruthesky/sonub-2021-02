<section class="l-sidebar mt-3">

    <div class="box">
        <div class="fs-xs">
            카카오톡, 네이버, 패스 로그인을 해주세요.
        </div>

        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/kakao-login.png">
        <img class="mt-1 w-100" src="/wp-content/themes/sonub/themes/sonub/img/naver-login.png?v=4">
    </div>

    <div class="mt-2">
        <?php
        include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항']);
        ?>
    </div>
    <div class="mt-2">
        <?php
        include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변']);
        ?>
    </div>
</section>
