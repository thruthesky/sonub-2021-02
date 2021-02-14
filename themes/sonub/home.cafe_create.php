<?php

$root = get_root_domain();
$setting = CAFE_DOMAIN_SETTING[$root]

?>

<?=$setting['countryName']?> 교민 카페 포털

<form>
    <input type="hidden" name="page" value="cafe/create.submit">
    <div>
        카페 URL: <input class="w-100px" name="id">.<?=CAFE_ROOT_DOMAIN?><br>
    </div>
    <div>
        카페 이름: <input name="name">
    </div>

    <?
    if ( isset(CAFE_DOMAIN_SETTING[$root]) && isset(CAFE_DOMAIN_SETTING[$root]['countryCode']) ) {
    ?>
        <input type="hidden" name="countryCode" value="<?=CAFE_DOMAIN_SETTING[$root]['countryCode']?>">
    <? } else { ?>
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
    <? } ?>
    <div>
        <button type="submit">카페 생성</button>
    </div>
</form>
<hr>
