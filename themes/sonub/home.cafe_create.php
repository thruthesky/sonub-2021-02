<?php


$setting = get_cafe_domain_settings();


?>




<div class="box border-radius-sm mb-2">
    <h3 class="fs-normal fs-md"><?=$setting['countryName']?> 교민 카페를 시작해 보세요.</h3>
    <hr>
    <form>
        <input type="hidden" name="page" value="cafe/create.submit">

        <div class="mb-3">
            <label class="form-label d-block fs-sm">카페 아이디</label>
            <input type="text" class="form-control w-200px d-inline" name="id" placeholder="카페 아이디">.<?=CAFE_ROOT_DOMAIN?>
            <div class="hint d-block mt-1">
                카페 아이디는 도메인과 같습니다. 한번 설정하면 변경이 안됩니다.
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fs-sm">카페 이름</label>
            <input class="form-control" name="name" type="text" placeholder="카페 이름을 입력해주세요.">

            <div class="hint mt-1">
                카페 이름은 설정에서 변경이 가능합니다.
            </div>
        </div>

        <?
        if ( $setting['countryCode'] ) {
            ?>
            <input type="hidden" name="countryCode" value="<?=$setting['countryCode']?>">
        <? } else { ?>

            <div class="mb-3">
                <label class="fs-sm" for="countryCode">교민 카페 운영 국가</label>
                <select class="form-select" id="countryCode" name="countryCode" aria-label="Country selection box">
                    <option selected>국가를 선택해주세요.</option>
                    <?
                    foreach( country_code() as $co ) {
                        ?>
                        <option value="<?=$co['2digitCode']?>"><?=$co['CountryNameKR']?></option>
                    <? } ?>
                </select>
            </div>

        <? } ?>

        <div>
            <button class="btn btn-primary w-100" type="submit">카페 생성</button>
        </div>
    </form>
</div>