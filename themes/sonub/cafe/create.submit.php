<?php
if ( in('id') == null ) jsBack('카페 아이디를 입력하세요.');
if ( in('name') == null ) jsBack('카페 이름을 입력하세요.');
if ( in('countryCode') == null ) jsBack('교민 사이트 국가를 선택하세요.');




$cafe = get_category_by_slug('cafe');
if ( empty($cafe) ) {
    wp_insert_category(['cat_name' => 'cafe']);
    $cafe = get_category_by_slug('cafe');
}


//get_child_categories($cafe->term_id);
$code = in('countryCode');
$country = get_category_by_slug($code);
if ( empty($country) ) {
    wp_insert_category(['cat_name' => $code, 'category_parent' => $cafe->term_id]);
    $country = get_category_by_slug($code);
}

$id = cafe_id_key(in('id'));
$co = get_option( $id );
if ( $co ) {
    jsBack("카페 아이디가 존재합니다. 다른 카페 아이디를 입력하세요.");
}
update_cafe_option(in('id'), ['name' => in('name') ]);
set_cafe_admin($id);
set_cafe_country_code($id, in('countryCode'));
jsGo(cafe_home_url($id));



