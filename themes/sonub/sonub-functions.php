<?php
include_once('cafe.config.php');

foreach(CAFE_ROOT_DOMAINS as $_domain) {
    if ( stripos(get_domain_name(), $_domain) !== false ) {
        define( 'CAFE_ROOT_DOMAIN', 'sonub.com' );
        break;
    }
}
define( 'CAFE_ID_PREFIX', 'cafe_' );

/// 카페 페이지로 접속했는데, 해당 카페가 존재하지 않는 경우, 루트 에러 페이지로 이동.
if ( is_in_cafe() && cafe_exists() === false ) {
    jsGo(cafe_root_url() . "?page=cafe.not_found");
}




function cafe_exists(): bool {
    $co = cafe_option();
    if ( empty($co) ) return false;
    else return true;
}

/**
 * cafe id 를 options 나 다른 곳에 활용하기 위해서 key 값으로 변환한다.
 *
 * @note 카페 아이디가 apple 이면 cafe_apple 로 리턴한다.
 *
 * @param $id
 * @return string
 */
function cafe_id_key($id) {
    return CAFE_ID_PREFIX . $id;
}

/**
 * 접속 URL 로 부터 카페 아이디를 리턴한다.
 *
 * @note 접속이 apple.sonub.com 이면 apple 을 리턴한다.
 *
 * @return mixed|string|string[]|null
 */
function get_cafe_id() {
    $domain = get_domain_name();
    if ( $domain === CAFE_ROOT_DOMAIN ) return null;
    $id = str_replace("." . CAFE_ROOT_DOMAIN, "", get_domain_name());
    if ( empty($id) || $id === 'www' ) return null;
    else return $id;
}

/**
 * 현재 페이지가 카페 페이지라면 true 를 리턴한다.
 *
 * @usage 현재 사용자가 카페에 있는지 루트 사이트에 있는지 판단 할 때 사용.
 *
 * @return bool
 */
function is_in_cafe() {
    if ( get_cafe_id() ) return true;
    else return false;
}

function cafe_root_url() {
    return "https://" . CAFE_ROOT_DOMAIN;
}
function cafe_home_url($id) {
    $id = str_replace(CAFE_ID_PREFIX, '', $id);
    return "https://$id.". CAFE_ROOT_DOMAIN;
}

/**
 * 게시판 URL 을 리턴한다.
 * @param $category
 * @return string
 */
function cafe_url($category) : string {
    $co = cafe_option();
    if ( !isset($co['countryCode']) ) {
        return "/?page=cafe.wrong_setting";
    }
    return "/?page=forum.list&category={$category}_$co[countryCode]";
}


function update_cafe_option($id, $data) {
    update_option($id, $data, false);
}


/**
 * 카페 옵션을 리턴한다.
 *
 * @note 메인 페이지(카페 페이지가 아닌)이면 빈 배열을 리턴한다.
 * @note 카페가 개설되어져 있지 않으면 빈 배열을 리턴한다.
 * @note 카페가 개설되어져 있으면 카페 id 를 추가해서 리턴한다.
 *
 * @return array|false|mixed|void
 */
function cafe_option() {
    $id = get_cafe_id();
    $co = get_option(cafe_id_key($id));
    if ( ! $co ) return [];
    $co['id'] = $id;
    return $co;
}



function original_category($categorySlug) {
    return substr($categorySlug, 0, strlen($categorySlug) - 3 );
}