전 세계를 잇는 교민 카페 생성!
<form>
    <input type="hidden" name="page" value="cafe/create.submit">
    <div>
        카페명 아이디: <input class="w-100px" name="id">.sonub.com 변경불가<br>
        @TODO: https://cafe-id.sonub.com 와 같이 2차 도메인 사용 가능. 하지만 실제 각 페이지나 글 읽기 페이지 주소는 https://sonub.com/cafe-id 와 같이 됨.
    </div>
    <div>
        카페 이름: <input name="name">
    </div>
    <div>
        교민 카페 운영 국가: <select name="countryCode">

            <option value="">교민 사이트 국가 선택</option>
            <?
            foreach( country_code() as $co ) {
                ?>
                <option value="<?=$co['2digitCode']?>"><?=$co['CountryNameKR']?></option>
                <?
            }
            ?>
        </select>
    </div>
    <div>
        <input type="checkbox">
        @TODO 추천: 소너브에서 제공하는 공지 및 뉴스 표시 (사이트의 정보 추가). 관리자 페이지에서 변경 가능.
    </div>
    <div>
        <button type="submit">카페 생성</button>
    </div>
</form>
<hr>

<ul>
    해야 할 일
    <li>게시판 생성시 1) 자유토론, 질문게시판, 뉴스, 여행 게시판, 업소록, 사업정보, 렌트카, 장터, 하숙집, 맛집, 게시판 등 게시판 성격을 선택해야만 한다.
        그래야, 모든 사이트에서 공유가 가능하다.
    </li>
    <li>
        @TODO: 가입 약관에 추가해서 회원에게 알려야 함. 카페 관리자의 선택에 따라 전체 공유가 될 수 있다고 한다.<br>
        @TDOO: 글 공유 게시판은 다른 카페에도 공유가 되므로, 카페 관리자가 관리를 잘 못하면 전체 사이트가 엉망이 된다.<br>
        @TODO: 카페장은 해당 카페에서 생성된 자료만 관리 할 수 있다. 카페장은 회원의 개인 정보를 볼 수 없으나, 회원 강퇴 처리를 할 수 있다.<br>
        글 공유 기능<br>
        카페A에 등록했는데, 카페B 에도 자동으로 공유되어 보인다.
        카페A는 다른 카페에서 안보이게 할 수 없다.
        다만, 카페A는 다른 카페에서 등록한 업소는 안보이게 할 수 있다.
        따라서, 예를 들어, 업소록 글은 카페A 에 등록했으면, 다른 카페에서는 등록하지 못한다.
        다만, 어느 카페에서든지 자기 글이면 수정 가능하다.<br>
        글 공유 문제점. 악의적으로 글을 올리는 경우. 전체 카페에 영향을 미친다.
        따라서, 공유 게시판에는 입력 형식이 딱 정해져 있어야 한다.
        제목 제한, 내용은 꼭 필요한 경우를 제외하고, 가급적 입력하지 않도록 한다.
        그리고 운영자가 늘 한명 있어야 하고 글을 상시 관리해야 한다.


    </li>
    <li>
        글 공유 게시판. 게시판 이름 변경 불가.
        뉴스, 업소록, 렌트카, 여행, 여행지 소개, 사업정보, 장터, 하숙집,
        맛집(카페 주인이 소개), 먹방(사용자가 자유 형식 음식 사진 게시),
        구인구직, 어학연수, 이민, 중고차,
        여권/비자, 이민/이주, 페소환전, 주택임대, 주택매매,
        도시별 게시판, 국제결혼, 주의 사항, 사람 찾기, 사업매매, 사업동업, 헬퍼/가정부,
        각종 서류, 날씨/태풍, 경험담, 소모임,

    </li>
    <li>
        게시판 카테고리별 세부 카테고리<br>
        아래와 같은 구조로 국가별, 카페 별 글 관리, 서브 카테고리 관리를 한다.

        /cafe/country/게시판-category/게시판-subcategory/글 meta: [ key=>cafe-id, vale=> ...


    </li>
    <li>
        관리자가 글 공유 선택가능 게시판. 게시판 이름 변경 불가.
        국가 선택 가능. 내 카페 국가 또는 전체 국가.
        가입인사, 자유게시판, 질문게시판, 공지사항,
    </li>
    <li>
        도시별 게시판. 명칭이 도시별 게시판이고 운영자가 선택 가능.
        예) 마닐라, 세부, 앙헬레스, 하노이, 뱅쿠버, 로스앤젤레스 등.
    </li>
    <li>
        관리자가 게시판 직접 생성 최대 2개까지 가능.
        단, 유료 버전의 경우 게시판 최대 10개 생성 가능.
    </li>
</ul>

<ul>
    <li>목적: 전 세계 교민 카페 (또는 어떤 목적이든 카페 포털 사이트)</li>
    <li>메인 사이트에는 카페 개설하는 방법 및 카페에서 추출된 정보를 제공</li>
</ul>
장점
<ol>
    <li>교민을 위한 특화된 정보 서비스</li>
    <li>교민 사이트에 맞는 카페 기능 제공. 일반 게시판, 갤러리, 업소록, 장터 등.</li>
    <li>본인 인증. 실명 인증을 통한 사기 방지.</li>
    <li>카페 글이 소너브와 연결되어 인터넷 검색에 많이 노출되어 방문자 수가 증가</li>
    <li>푸시 알림 전송</li>
    <li>핸드폰 홈 화면에 앱 아이콘 추가</li>
    <li>
        매우 저렴한 비용으로 안드로이드와 iOS 앱 개발. 기본 디자인을 이용하는 경우, 안드로이드 개발 비용 10만원. iOS 개발 비용 10만원.
        월 관리비 매월 2만2천원.
    </li>
</ol>

<?php


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



