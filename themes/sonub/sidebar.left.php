<section class="l-sidebar d-none d-md-block mt-3">


    <?php
    if ( is_cafe_admin() ) {
        include widget('cafe/admin', ['class' => 'border-radius-md']);
    } else if ( loggedIn() ) {
        include widget('login/info', ['class' => 'border-radius-md']);
    }
    if ( has_widget_of("cafe-left-sidebar-widget") || is_widget_edit_mode() ) {
        for( $i=0; $i<NO_OF_CAFE_WIDGETS; $i++) {
            include dynamic_widget("cafe-left-sidebar-widget-$i", ['class' => 'border-radius-md']);
        }
    } else {
        include widget('posts/latest', ['class' => 'border-radius-md']);
    }

    ?>

    <div class="box border-radius-md">
        퀵 메뉴
        <hr>
        <div class="fs-sm">
            가입인사, 자유게시판, 질문게시판, 공지사항, 업소록, 장터, 경험담,
            자유게시판, 질문게시판, 뉴스, 업소록, 렌트카, 여행, 여행지 소개, 사업정보, 장터, 하숙집, 맛집(카페 주인이 소개), 먹방(사용자가 자유 형식 음식 사진 게시), 구인구직, 어학연수, 이민, 중고차, 여권/비자, 이민/이주, 페소환전, 주택임대, 주택매매, 도시별 게시판, 국제결혼, 주의 사항, 사람 찾기, 사업매매, 사업동업, 헬퍼/가정부, 각종 서류, 날씨/태풍, 경험담, 소모임,
            전체글
        </div>
    </div>


</section>

