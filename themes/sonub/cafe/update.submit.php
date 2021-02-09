<?php
if ( is_cafe_admin() === false ) jsBack("카페 관리자가 아닙니다.");
if ( in('name') == null ) jsBack('카페 이름을 입력하세요.');
$co = cafe_option();
$in = in();
unset($in['page']);
$co = array_merge($co, $in);
update_cafe_option(get_cafe_id(), $co);
jsGo('/?page=cafe.admin');



