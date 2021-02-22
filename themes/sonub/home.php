<?php
$str=<<<EOJ
{
address: '배송지 주소',
name: '받는 사람 이름',
phoneNo: '받는 사람 전화번호',
memo: '포장지에 적을 메모',
price: '18,100',
noOfItems: 2,
order: {
    '111': {
        postTitle: '',
price: 1000,
discountRate: 0,
orderPrice: 4500,
selectedOptions: {
            'Default Option': {
                count: 3,
price: 0,
discountRate: 0,
},
pepper: {
                count: 1,
price: 500,
discountRate: 0,
},
},
},
'222': {
        postTitle: '두번째 테스트 상품',
price: 2000,
discountRate: 50,
orderPrice: 13600,
selectedOptions: {
            potato: {
                count: 1,
price: 5000,
discountRate: 20,
},
            tomato: {
                count: 2,
price: 6000,
discountRate: 20,
},
        },
},
},
}
EOJ;
d((fixJson($str)));

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
