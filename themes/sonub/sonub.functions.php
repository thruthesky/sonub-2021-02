<?php

define( 'CAFE_ID_PREFIX', 'cafe_' );

include_once('cafe.config.php');

foreach(CAFE_ROOT_DOMAINS as $_domain) {
    if ( stripos(get_domain_name(), $_domain) !== false ) {
        define( 'CAFE_ROOT_DOMAIN', get_root_domain() );
        break;
    }
}

define('ADMIN_MENUS', [
    [
        'name' => '틀린 그림 찾기',
        'script' => 'admin.game.find_wrong_picture',
    ]
]);


add_hook('html_title', function() {
    if ( is_in_cafe() ) {
        $co = cafe_option();
        return $co['name'];
    }
});





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
 * 현재 카페의 id 를 options 이나 다른 곳에 사용하기 쉽도록 cafe_[id] 와 같이 리턴한다.
 * @return string
 *  - 예) cafe_local, cafe_my_cafe_id
 */
function get_current_cafe_id_key(): string {
    return cafe_id_key( get_cafe_id() );
}

/**
 * 현재 카페의 관리자 id 를 리턴한다.
 * @return int
 */
function get_current_cafe_admin_id() {
    return get_cafe_admin_id(get_current_cafe_id_key());
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

/**
 * 워드프레스 관리자이거나 현재 접속한 카페의 관리자이면 참을 리턴한다.
 * @return bool
 */
function is_cafe_admin(): bool {
    if ( notLoggedIn() ) return false;
    if ( admin() ) return true;
    if ( get_current_cafe_admin_id() == wp_get_current_user()->ID ) return true;
    return false;
}


function cafe_root_url() {
    return "https://" . CAFE_ROOT_DOMAIN;
}

/**
 * 현재 카페 URL 을 리턴한다.
 * @param null $id - 카페아이디가 주어지면, 해당 카페 URL 을 리턴한다.
 * @return string
 */
function cafe_home_url($id=null) {
    if ( $id === null ) $id = get_current_cafe_id_key();
    $id = str_replace(CAFE_ID_PREFIX, '', $id);
    return "https://$id.". CAFE_ROOT_DOMAIN;
}

/**
 * 게시판 URL 을 리턴한다.
 * @param $category
 * @return string
 */
function cafe_url($category) : string {
    $code = cafe_country_code();
    return "/?page=forum.list&category={$category}_$code";
}


/**
 * 카페 설정 저장.
 *
 * 하나의 옵션에 여러개의 값을 저장한다.
 *
 * @param $id
 * @param $data
 */
function update_cafe_option($id, $data) {
    $id = cafe_id_key($id);
    update_option($id, $data, false);
}

/**
 * 카페 옵션을 리턴한다.
 *
 * @note 메인 페이지(카페 페이지가 아닌)이면 null 을 리턴한다.
 * @note 카페가 개설되어져 있지 않으면 null 을 리턴한다.
 * @note 카페가 개설되어져 있으면 카페 id 를 추가해서 리턴한다.
 *
 * @param string $name is the option name
 * @param mixed $default_value is the default value.
 * @return array|false|mixed|void
 *
 *  - 넓은 메뉴가 하나도 설정되지 않았다면, [`wide_menu` => false] 의 값이 리턴된다.
 */
$_cafe_option = null;
function cafe_option($name = null, $default_value = null) {

    if ( ! is_in_cafe() ) return $default_value;

    /// 메모리 캐시 값을 리턴한다.
    global $_cafe_option;
    if ( $_cafe_option ) {
        if ( $name ) return $_cafe_option[$name] ?? $default_value;
        return $_cafe_option;
    }

    /// DB 에서 읽어, 메모리에 저장하고 리턴한다.
    $id = get_cafe_id();
    $co = get_option(cafe_id_key($id));
    if ( ! $co ) return $default_value;
    $co['id'] = $id;
    if ( $name ) return $co[$name] ?? $default_value;

    $co['wide_menu'] = false;
    for($i = 0; $i < NO_OF_WIDE_CAFE_MENU; $i ++ ) {
        if ( isset($co["wide_menu_$i"]) && $co["wide_menu_$i"] ) {
            $co['wide_menu'] = true;
            break;
        }
    }
    $co['narrow_menu'] = false;
    for($i = 0; $i < NO_OF_WIDE_CAFE_MENU; $i ++ ) {
        if ( isset($co["narrow_menu_$i"]) && $co["narrow_menu_$i"] ) {
            $co['narrow_menu'] = true;
            break;
        }
    }

    $_cafe_option = $co;
    return $_cafe_option;
}

/**
 * 카페 관리자 지정.
 *
 * 한번 설정이 되면 절대 변경되지 않도록, 별도의 키/값으로 저장한다.
 *
 * @param $id
 */
function set_cafe_admin($id) {
    update_option($id . '_admin', wp_get_current_user()->ID, false);
}

/**
 * 카페 관리자 아이디(번호, 숫자)를 리턴한다.
 *
 * @param $id
 * @return int
 */
function get_cafe_admin_id($id): int {
    return get_option($id . '_admin');
}

function set_cafe_country_code($id, $code) {
    update_option($id . '_countryCode', $code, false);
}

/**
 * @return string
 */
function cafe_country_code(): string {
    return get_option(get_current_cafe_id_key() . '_countryCode');
}

function cafe_country_name(): string {
    return country_name(get_option(get_current_cafe_id_key() . '_countryCode'));
}


/**
 *
 * 카테고리 slug 'abc_kr' 에서 'abc' 만 리턴한다.
 *
 * @param $categorySlug
 * @return false|string
 */
function original_category($categorySlug) {
    return substr($categorySlug, 0, strlen($categorySlug) - 3 );
}

function update_widget_icon($widget_id) {
    return "<a href='/?page=home&update_widget=$widget_id#$widget_id'><i class='fa fa-cog'></i></a>";
}


/**
 *
 * 다이나믹 위젯의 설정을 저장/삭제하고 가져오는 함수
 * @param $id - widget id
 * @return false|mixed|void
 */
function get_dynamic_widget_options($id) {
    return get_option(get_current_cafe_id_key() . '-' .$id);
}
function set_dynamic_widget_options($id, $data) {
    return update_option(get_current_cafe_id_key() . '-' .$id, $data, false);
}

function delete_dynamic_widget_options($id) {
    return delete_option(get_current_cafe_id_key() . '-' .$id);
}


/**
 * 본문, 왼쪽/오른쪽 사이드바에 설정된 위젯이 있으면 true 를 리턴하고 없으면 false 를 리턴한다.
 * @param $prefix
 * @return bool
 */
function has_widget_of($prefix): bool {
    for ($i = 0; $i < NO_OF_CAFE_WIDGETS; $i++) {
        $dynamic_widget_id = "$prefix-$i";
        $dow = get_dynamic_widget_options($dynamic_widget_id);
        if ( $dow ) return true;
    }
    return false;
}
