<?php

?>

<div class="box border-radius-md" style="padding: .25em;">
    <div class="p-2">
        <div class="p-1 pb-0">
            퀵메뉴
        </div>
    </div>
    <hr style="margin: 0 .5em">
    <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
        <a class="p-1" href="/?page=cafe.all-posts">전체글</a>
        <? foreach( cafe_categories_parent_without_child() as $slug => $menu ) { ?>
            <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
        <? } ?>
    </div>

    <hr style="margin: 0 .5em">

    <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
        <a class="p-1 bold" href="<?=cafe_url('buyandsell')?>">회원장터</a>
        <? foreach( cafe_categories_of('buyandsell') as $slug => $menu ) { ?>
            <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
        <? } ?>
    </div>

    <hr style="margin: 0 .5em">

    <div class="d-flex flex-wrap justify-content-between p-2 fs-sm">
        <a class="p-1 bold" href="<?=cafe_url('business')?>">사업정보</a>
        <? foreach( cafe_categories_of('business') as $slug => $menu ) { ?>
            <a class="p-1" href="<?=cafe_url($slug)?>"><?=$menu['name']?></a>
        <? } ?>
    </div>

</div>
