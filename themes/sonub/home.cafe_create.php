전 세계를 잇는 교민 카페 생성!
<form>
    <input type="hidden" name="page" value="cafe/create.submit">
    <div>
        카페 URL: <input class="w-100px" name="id">.<?=CAFE_ROOT_DOMAIN?> 변경불가<br>
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
        <button type="submit">카페 생성</button>
    </div>
</form>
<hr>
