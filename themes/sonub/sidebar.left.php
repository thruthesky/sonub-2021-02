<section class="l-sidebar d-none d-md-block mt-3">


    <?php
    if ( is_cafe_admin() ) {
        include widget('cafe/admin', ['class' => 'border-radius-md']);
    }
    if ( has_widget_of("cafe-left-sidebar-widget") || is_widget_edit_mode() ) {
        for( $i=0; $i<NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-left-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('posts/latest');
    }

    ?>


</section>
