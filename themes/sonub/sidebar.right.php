
<section class="l-sidebar d-none d-lg-block mt-3">

    <?php
    if ( has_widget_of("cafe-right-sidebar-widget") || is_widget_edit_mode() ) {
        for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-right-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('login/social-login', ['class' => 'border-radius-md']);
        include widget('posts/latest', ['widget_title' => '최근 글', 'class' => 'border-radius-md']);
    }



    ?>


</section>
