<?php
if ( is_cafe_admin() === false ) {
?>
    <div class="alert alert-danger border-0">
        <h2>앗! 카페 관리자가 아닙니다.</h2>
        <hr>
        카페 설정은 카테 관리자만 할 수 있습니다.
        <br>
        관리자로 로그인을 해 주세요.
    </div>
<?php return; } ?>

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
        교민 카페 운영 국가: <?=cafe_country_name()?>
    </div>
    <div>
        <button type="submit">카페 수정</button>
    </div>

    <hr>
    <h2>메뉴 설정</h2>

    <div class="container">
        <div class="row">
            <div class="col">

                <h3>넓은 화면 메뉴</h3>
                <div class="hint">
                    컴퓨터나 노트북 등의 넓은 화면에서 사용되는 메뉴입니다.
                </div>
                <ol>
                    <? for( $i = 0; $i < NO_OF_WIDE_CAFE_MENU; $i ++ ) { ?>
                        <li>
                            <select name="wide_menu_<?=$i?>">
                                <option value="">게시판 선택</option>
                                <? foreach( CAFE_CATEGORIES as $id => $cat ) { ?>
                                    <option value="<?=$id?>" <? if ( ($co["wide_menu_$i"] ?? '') == "$id" ) echo "selected" ?>><?=$cat['name']?></option>
                                <? } ?>
                            </select>
                        </li>
                    <? } ?>
                </ol>


            </div>
            <div class="col">

                <h3>좁은 화면 메뉴</h3>
                <div class="hint">
                    핸드폰이나 테블릿 등의 좁은 화면에서 사용되는 메뉴입니다.
                </div>
                <ol>
                    <? for( $i = 0; $i < NO_OF_NARROW_CAFE_MENU; $i ++ ) { ?>
                        <li>
                            <select name="narrow_menu_<?=$i?>">
                                <option value="">게시판 선택</option>
                                <? foreach( CAFE_CATEGORIES as $id => $cat ) { ?>
                                    <option value="<?=$id?>" <? if ( ($co["narrow_menu_$i"] ?? '') == "$id" ) echo "selected" ?>><?=$cat['name']?></option>
                                <? } ?>
                            </select>
                        </li>
                    <? } ?>
                </ol>
            </div>
        </div>
    </div>

    <div>
        <button type="submit">카페 메뉴 저장</button>
    </div>

</form>
<hr>
