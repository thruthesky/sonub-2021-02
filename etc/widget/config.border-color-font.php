<?php
global $dwo;

$colors = [
    'Black' => '검정',
    'White' => '흰색',
    'Red' => '빨강',
    'Green' => '녹색',
    'Blue' => '파랑',
    'DarkBlue' => '검은 파랑',
    'LightBlue' => '밝은 파랑',
    'Purple' => '자주색',
    'Yellow'=> '노랑',
    'Lime' => '라임',
    'Cyan' => '밝은 푸른색',
    'Orange' => '오랜지색',
    'Brown' => '갈색',
    'Olive' => '올리브색',
    'Silver' => '은색',
    'Grey' => '회색',
    '#cdcdcd' => '밝은 회색',
    '#dedede' => '밝은색',
    '#efefef' => '더 밝은 색',
    '#fafafa' => '매우 밝은 색',
    ];
?>
<div class="mb-1">


    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">테두리 두께</label>
            </div>
            <select class="form-select mb-1" name="borderWidth">
                <option value="">테두리 두께</option>
                <? for($px = 0; $px < 6; $px++) { ?>
                    <option value="<?=$px?>" <? if ( ($dwo['borderWidth']??'1') == $px ) echo "selected"?>><?=$px?>px</option>
                <? } ?>
            </select>
        </div>

        <div class="col">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">테두리 색깔</label>
            </div>
            <select class="form-select mb-1" name="borderColor">
                <option value="">선택</option>
                <? foreach($colors as $value => $name) { ?>
                    <option value="<?=$value?>" <? if ( ($dwo['borderColor']??'#efefef') == $value ) echo "selected"?>><?=$name?></option>
                <? } ?>
            </select>
        </div>

    </div>



    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">배경 색깔</label>
            </div>
            <select class="form-select mb-1" name="backgroundColor">
                <option value="">선택</option>
                <? foreach($colors as $value => $name) { ?>
                    <option value="<?=$value?>" <? if ( ($dwo['backgroundColor']??'#fafafa') == $value ) echo "selected"?>><?=$name?></option>
                <? } ?>
            </select>
        </div>

        <div class="col-12 col-sm-6">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">꼭지점 둥글게 표시</label>
            </div>

            <select class="form-select mb-1" name="borderRadius">
                <option value="">꼭지점 둥글게(px)</option>
                <? for( $px = 0; $px <= 25; $px ++) { ?>
                    <option value="<?=$px?>" <? if ( ($dwo['borderRadius']??'15') == $px ) echo "selected"?>><?=$px?>px</option>
                <? } ?>
            </select>
        </div>

    </div>


    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">글자 색깔</label>
            </div>

            <select class="form-select mb-1" name="fontColor">
                <option value="">선택</option>
                <? foreach($colors as $value => $name) { ?>
                    <option value="<?=$value?>" <? if ( ($dwo['fontColor']??'') == $value ) echo "selected"?>><?=$name?></option>
                <? } ?>
            </select>
        </div>
        <div class="col">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">글자 크기</label>
            </div>

            <select class="form-select mb-1" name="fontSize">
                <option value="">선택</option>
                <? for( $px = 12; $px <= 24; $px ++) { ?>
                    <option value="<?=$px?>" <? if ( ($dwo['fontSize']??'') == $px ) echo "selected"?>><?=$px?>px</option>
                <? } ?>
            </select>
        </div>
    </div>

</div>
