<?php

$o = get_widget_options();

?>
<div class="box mb-2 <?=$o['class'] ?? ''?>">
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
        <li><a href="/?page=cafe.admin">카페 설정</a></li>
        <li>

            <a href="<?=set_cookie_url('widget_edit', 'on')?>&value=<? echo is_widget_edit_mode() == 'on' ? 'off' : 'on' ?>">
                위젯 설정:
                <? if ( is_widget_edit_mode() ) { ?>
                    ON
                <? } else { ?>
                    OFF
                <? } ?>
            </a>
        </li>
        <li>카페 운영 문의하기</li>
    </ul>

</div>

