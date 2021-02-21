<?php
?>

<ol>
    <li>
        https://free.currconv.com/api/v7/countries?apiKey=do-not-use-this-key 에서
        국가별 환율 코드를 저장한다.
    </li>
    <li>
        현재 카페의 국가 코드에 맞는 캐시 데이터가 있는지 본다.
    </li>
    <li>
        없으면
        https://free.currencyconverterapi.com/ 에서 가져와서,

        set_cache(""); 로 저장한다.

    </li>
</ol>

<div class="box border-radius-md mb-2 p-3">
    환률 위젯<br>
    유료 서비스 API 로 전 세계 환율을 10분마다 저장해서 보여준다.<br>
    위젯으로 만들어, 옵션으로 카페 국가를 지정하면,
    카페 국가, 미국, 한국으로 환율 정보를 보여준다.<br>
    환전/송금 업체 보기
    <br>
    RSS 위젯. 제목을 클릭하면, 전체 글이 보이고, 위젯 페이지네이션을 할 수 있도록 한다.
</div>
