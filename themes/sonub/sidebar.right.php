
<section class="l-sidebar d-none d-lg-block mt-3">

    환률 위젯<br>
    유료 서비스 API 로 전 세계 환율을 10분마다 저장해서 보여준다.<br>
    위젯으로 만들어, 옵션으로 카페 국가를 지정하면,
    카페 국가, 미국, 한국으로 환율 정보를 보여준다.<br>
    환전/송금 업체 보기
    <br>
    RSS 위젯. 제목을 클릭하면, 전체 글이 보이고, 위젯 페이지네이션을 할 수 있도록 한다.

    <?php
    if ( has_widget_of("cafe-right-sidebar-widget") || is_widget_edit_mode() ) {
        for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-right-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('cafe/intro');
        include widget('posts/latest', ['widget_title' => '최근 글', 'class' => 'border-radius-md']);
    }



    ?>


</section>
