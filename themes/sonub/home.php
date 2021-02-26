<?php


// 로그인을 했고, 이름이 없으면 이름을 등록한다.
if ( loggedIn() && empty(my('name')) ) {
    include script('user/profile');
    return;
}

if ( is_in_cafe_main() ) {
    include 'home.cafe_create.php';
    include 'home.default_widgets.php';
} else {

    if ( has_widget_of("cafe-home-widget") || is_widget_edit_mode() ) {
        for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-home-widget-$i", ['class' => '']);
        }
    } else {
        include 'home.default_widgets.php';
    }

}
