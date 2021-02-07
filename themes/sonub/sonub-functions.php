<?php

define( 'CAFE_DOMAIN', 'sonub.com' );
define( 'CAFE_ID_PREFIX', 'cafe_' );

include_once('cafe.config.php');




function cafe_id($id) {
    return CAFE_ID_PREFIX . $id;
}
function cafe_home_url($id) {
    $id = str_replace(CAFE_ID_PREFIX, '', $id);
    return "https://$id.". CAFE_DOMAIN;
}
function cafe_url($category) {
    $co = cafe_option();
    return "/?page=forum.list&category={$category}_$co[countryCode]";
}


function update_cafe_option($id, $data) {
    update_option($id, $data, false);
}


/**
 * 카페 옵션을 리턴한다. 카페 페이지 아니면가 빈 배열을 리턴한다.
 * @return array|false|mixed|void
 */
function cafe_option() {
    $id = str_replace("." . CAFE_DOMAIN, "", get_domain_name());
    if ( empty($id) || $id === 'www' ) return [];
    $co = get_option(cafe_id($id));
    $co['id'] = $id;
    return $co;
}



function original_category($categorySlug) {
    return substr($categorySlug, 0, strlen($categorySlug) - 3 );
}