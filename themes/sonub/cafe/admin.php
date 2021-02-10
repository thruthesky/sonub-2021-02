<h1>카페 설정</h1>
<?php
$co = cafe_option();
?>
<form action="/" method="post">
    <input type="hidden" name="page" value="cafe/update.submit">
    <div>
        카페 홈 주소: <?=cafe_home_url()?>변경불가<br>
    </div>
    <div>
        카페 이름: <input name="name" value="<?=$co['name']?>">
    </div>
    <div>
        교민 카페 운영 국가: <?=country_name($co['countryCode'])?>
    </div>
    <div>
        <button type="submit">카페 수정</button>
    </div>

    <hr>
    <h2>메뉴 설정</h2>
    <ol>
        <? for( $i = 0; $i < 10; $i ++ ) { ?>
        <li>
            <select name="menu<?=$i?>">
                <option value="">게시판 선택</option>
                <? foreach( CAFE_CATEGORIES as $id => $cat ) { ?>
                    <option value="<?=$id?>" <? if ( ($co["menu$i"] ?? '') == "$id" ) echo "selected" ?>><?=$cat['name']?></option>
                <? } ?>
            </select>
        </li>
        <? } ?>
    </ol>

    <div>
        <button type="submit">카페 메뉴 저장</button>
    </div>

</form>
<hr>
