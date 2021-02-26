<?php
global $dwo;
$cats = get_categories();
run_hook('widget/config.category_name categories', $cats);
$options = [];
foreach( $cats as $cat) {
    $options[$cat->slug] = $cat->cat_name;
}


$default_option = [
    'value' => '',
    'label' => '게시판 카테고리 선택'
];
run_hook('widget/config.category_name default_option', $default_option);


?>
<div class="mb-1">

        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between">
                    <label class="form-label fs-xs mb-0">왼쪽 카테고리</label>
                    <div class="fs-xs pointer" onclick="alert('전체 또는 특정 게시판의 글만 보이게 할 수 있습니다.')">도움말(?)</div>
                </div>

                <select class="form-select mb-1" name="left">
                    <option value="<?=$default_option['value']?>"><?=$default_option['label']?></option>
                    <? foreach($options as $slug => $name) { ?>
                        <option value="<?=$slug?>" <? if ( ($dwo['left']??'') == $slug ) echo "selected"?>><?=$name?></option>
                    <? } ?>
                </select>
            </div>
            <div class="col">
                <div class="d-flex justify-content-between">
                    <label class="form-label fs-xs mb-0">오른쪽 카테고리</label>
                    <div class="fs-xs pointer" onclick="alert('전체 또는 특정 게시판의 글만 보이게 할 수 있습니다.')">도움말(?)</div>
                </div>

                <select class="form-select mb-1" name="right">
                    <option value="<?=$default_option['value']?>"><?=$default_option['label']?></option>
                    <? foreach($options as $slug => $name) { ?>
                        <option value="<?=$slug?>" <? if ( ($dwo['right']??'') == $slug ) echo "selected"?>><?=$name?></option>
                    <? } ?>
                </select>
            </div>
        </div>

</div>
