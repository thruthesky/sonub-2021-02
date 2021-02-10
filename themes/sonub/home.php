<?php
if ( is_in_cafe() == false ) {
    include 'home.cafe_create.php';
}
include widget('posts/two-columns', [
    'left' => [
        'category_name' => 'qna',
    ],
    'middle' => [
        'category_name' => 'reminder',
    ],
]);

include widget('posts/latest-photos', ['category_name' => 'qna', 'posts_per_page' => 4, 'widget_title' => '질문과 답변 사진', 'class' => "mt-2"]);

include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항', 'class' => "mt-2"]);
include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변', 'class' => "mt-2"]);
include widget('posts/latest', ['category_name' => 'discussion', 'widget_title' => '자유게시판', 'class' => "mt-2"]);



