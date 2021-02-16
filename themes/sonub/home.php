<?php
if ( is_in_cafe() == false ) {
    include 'home.cafe_create.php';

    include widget('posts/two-columns', [
        'left' => [
            'category_name' => 'qna',
        ],
        'middle' => [
            'category_name' => 'reminder',
        ],
        'class' => 'border-radius-md',
    ]);

    include widget('posts/latest-photos', ['category_name' => 'qna', 'posts_per_page' => 4, 'widget_title' => '질문과 답변 사진', 'class' => "mt-2 border-radius-md"]);
    include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항', 'class' => "mt-2 border-radius-md"]);
    include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변', 'class' => "mt-2 border-radius-md"]);
    include widget('posts/latest', ['category_name' => 'discussion', 'widget_title' => '자유게시판', 'class' => "mt-2 border-radius-md"]);
} else {

    if ( has_widget_of("cafe-home-widget") || is_widget_edit_mode() ) {
        for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-home-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('posts/latest', ['widget_title' => '최근 글', 'class' => 'border-radius-md']);
        include widget('posts/latest', ['widget_title' => '최근 글', 'class' => 'border-radius-md']);
        include widget('posts/latest', ['widget_title' => '최근 글', 'class' => 'border-radius-md']);
    }

}
