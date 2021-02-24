<?php
global $dwo;

$colors = [
    'Black',
    'White',
    'Red',
    'Green',
    'Blue',
    'DarkBlue',
    'LightBlue',
    'Purple',
    'Yellow',
    'Lime',
    'Cyan',
    'Silver',
    'Grey',
    'Orange',
    'Brown',
    'Olive',
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
                    <option value="<?=$px?>" <? if ( ($dwo['borderWidth']??'') == $px ) echo "selected"?>><?=$px?>px</option>
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
                    <option value="<?=$px?>" <? if ( ($dwo['borderRadius']??'') == $px ) echo "selected"?>><?=$px?>px</option>
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
                <? foreach($colors as $color) { ?>
                    <option value="<?=$color?>" <? if ( ($dwo['backgroundColor']??'') == $color ) echo "selected"?>><?=$color?></option>
                <? } ?>
            </select>
        </div>

        <div class="col">

        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between">
                <label class="form-label fs-xs mb-0">글자 색깔</label>
            </div>

            <select class="form-select mb-1" name="fontColor">
                <option value="">선택</option>
                <? foreach($colors as $color) { ?>
                    <option value="<?=$color?>" <? if ( ($dwo['fontColor']??'') == $color ) echo "selected"?>><?=$color?></option>
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
