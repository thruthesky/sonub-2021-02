<section class="l-sidebar d-none d-md-block mt-3">


    <?php
    if ( is_cafe_admin() ) {
        include widget('cafe/admin', ['class' => 'border-radius-md']);
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
    for( $i=0; $i<10; $i++)
    include dynamic_widget("cafe-left-sidebar-widget-$i", ['class' => 'border-radius-md']);

    ?>


</section>
