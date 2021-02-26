<?php


include widget('posts/two-columns', [
    'left' => cafe_category('reminder'),
    'right' => cafe_category('qna'),
    'class' => 'border-radius-md',
]);

include widget('posts/latest-photos', [
    'category_name' => 'qna',
    'posts_per_page' => 4, 'widget_title' => '질문과 답변 사진', 'class' => "mt-2 border-radius-md"]);
include widget('posts/latest', ['category_name' => 'reminder', 'widget_title' => '공지사항', 'class' => "mt-2 border-radius-md"]);
include widget('posts/latest', ['category_name' => 'qna', 'widget_title' => '질문과 답변', 'class' => "mt-2 border-radius-md"]);
include widget('posts/latest', ['category_name' => 'discussion', 'widget_title' => '자유게시판', 'class' => "mt-2 border-radius-md"]);
