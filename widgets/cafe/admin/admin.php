<?php

$o = get_widget_options();

?>
<div class="box mb-2">
    <div class="d-flex justify-content-between">
        <div>카페 관리자</div>
        <div>
            <i class="fa fa-cog"></i>
        </div>
    </div>
    <ul>
        <li>회원 관리</li>
        <li>게시글 관리</li>
        <li>게시판 관리</li>
        <li>전체 사진 목록</li>
        <li>카페 설정</li>
        <li>
            <a href="/?<?=md5('set')?>=<?=md5('cookie')?>&key=<?=md5('widget')?>&value=<? echo is_widget_edit_mode() ? 'off' : 'on' ?>">
                위젯 설정:
                <? if ( is_widget_edit_mode() ) { ?>
                    ON
                <? } else { ?>
                    OFF
                <? } ?>
            </a>
        </li>
    </ul>

</div>

