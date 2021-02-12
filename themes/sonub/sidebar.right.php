<section class="l-sidebar d-none d-lg-block mt-3">

    <?php
    if ( has_widget_of("cafe-right-sidebar-widget") || is_widget_edit_mode() ) {
        for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-right-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {

        echo "위젯을 선택해주세요.";
        include widget('posts/latest', ['widget_title' => '최근 글']);
    }



    ?>


</section>
