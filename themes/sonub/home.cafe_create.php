전 세계를 잇는 교민 카페 생성!
<form>
    <input type="hidden" name="page" value="cafe/create.submit">
    <div>
        카페명 아이디: <input class="w-100px" name="id">.<?=CAFE_ROOT_DOMAIN?> 변경불가<br>
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
