<?php

function set_like_point($no) {
    update_option(POINT_LIKE, $no, true);
}

function get_like_point() {
    return get_option(POINT_LIKE, 0);
}

function set_dislike_point($no) {
    update_option(POINT_DISLIKE, $no, true);
}
function get_dislike_point() {
    return get_option(POINT_DISLIKE, 0);
}

function set_like_deduction_point($no) {
    update_option(POINT_LIKE_DEDUCTION, $no, true);
}

function get_like_deduction_point() {
    return get_option(POINT_LIKE_DEDUCTION, 0);
}

function set_dislike_deduction_point($no) {
    update_option(POINT_DISLIKE_DEDUCTION, $no, true);
}
function get_dislike_deduction_point() {
    return get_option(POINT_DISLIKE_DEDUCTION, 0);
}

function set_register_point($no) {
    update_option(POINT_REGISTER, $no, false);
}
function get_register_point() {
    return get_option(POINT_REGISTER, 0);
}


function set_login_point($no) {
    update_option(POINT_LOGIN, $no, false);
}
function get_login_point() {
    return get_option(POINT_LOGIN, 0);
}


function set_like_hour_limit($hour) {
    update_option(POINT_LIKE_HOUR_LIMIT, $hour);
}

function get_like_hour_limit() {
    return get_option(POINT_LIKE_HOUR_LIMIT, 0);
}

function set_like_hour_limit_count($count) {
    update_option(POINT_LIKE_HOUR_LIMIT_COUNT, $count);
}

function get_like_hour_limit_count() {
    return get_option(POINT_LIKE_HOUR_LIMIT_COUNT, 0);
}

function set_like_daily_limit_count($count) {
    update_option(POINT_LIKE_DAILY_LIMIT_COUNT, $count);
}
function get_like_daily_limit_count() {
    return get_option(POINT_LIKE_DAILY_LIMIT_COUNT, 0);
}


function set_post_create_point($category, $point) {
    update_category(['category' => $category, POINT_POST_CREATE => $point]);
}

function get_post_create_point($category) {
    return category_meta($category, POINT_POST_CREATE, 0);
}

function set_comment_create_point($category, $point) {
    update_category(['category' => $category, POINT_COMMENT_CREATE => $point]);
}
function get_comment_create_point($category) {
    return category_meta($category, POINT_COMMENT_CREATE, 0);
}


function set_post_delete_point($category, $point) {
    update_category(['category' => $category, POINT_POST_DELETE => $point]);
}
function get_post_delete_point($category) {
    return category_meta($category, POINT_POST_DELETE, 0);
}

function set_comment_delete_point($category, $point) {
    update_category(['category' => $category, POINT_COMMENT_DELETE => $point]);
}
function get_comment_delete_point($category) {
    return category_meta( $category, POINT_COMMENT_DELETE, 0);
}


function set_category_hour_limit($category, $hour) {
    update_category(['category' => $category, POINT_HOUR_LIMIT => $hour]);
}
function get_category_hour_limit($category) {
    return category_meta($category, POINT_HOUR_LIMIT, 0);
}

function set_category_hour_limit_count($category, $count) {
    update_category(['category' => $category, POINT_HOUR_LIMIT_COUNT => $count]);
}
function get_category_hour_limit_count($category) {
    return category_meta($category, POINT_HOUR_LIMIT_COUNT, 0);
}

function set_category_daily_limit_count($category, $count) {
    update_category(['category' => $category, POINT_DAILY_LIMIT_COUNT => $count]);
}
function get_category_daily_limit_count($category) {
    return category_meta($category, POINT_DAILY_LIMIT_COUNT, 0);
}



/**
 * 포인트 기록 테이블에서 $stamp 시간 내에 $reason 들을 찾아 그 수가 $count 보다 많으면 true 를 리턴한다.
 *
 * 예를 들어,
 *   - 추천/비추천을 1시간 이내에 5번으로 제한을 하려고 할 때,
 *   - 글 쓰기를 하루에 몇번으로 제한 할 때 등에 사용 할 수 있다.
 *
 * @param array $reasons
 * @param int $stamp
 * @param int $count
 * @return bool
 *
 * @example
 *
    // 추천/비추천 시간/수 제한
    if ( $re = count_over(
        [ POINT_LIKE, POINT_DISLIKE ], // 추천/비추천을
        get_like_hour_limit() * 60 * 60, // 특정 시간에, 시간 단위 이므로 * 60 * 60 을 하여 초로 변경.
        get_like_hour_limit_count() // count 회 수 이상 했으면,
    ) ) return ERROR_HOURLY_LIMIT; // 에러 리턴
 *
 */
function count_over(array $reasons, int $stamp, int $count): bool {
    if ( $count ) {
        $total = count_my_point_actions( $stamp, $reasons );
        if ( $total >= $count ) {
            return true;
        }
    }
    return false;
}

/**
 * @param $options
 *   - $options['limit'] 가져 올 레코드 개 수. 기본 200 개.
 * @return null
 *
 * @example 내 받은 또는 감소된 모든 포인트 기록 가져오기
 *   get_point_history([ 'from_user_ID' => $user['ID'] ]);
 */
function get_point_history($options) {
    global $wpdb;
    $conds = [];
    if ( isset($options[FROM_USER_ID]) ) $conds[] = FROM_USER_ID . "=" . $options[FROM_USER_ID];
    if ( isset($options[TO_USER_ID]) ) $conds[] = TO_USER_ID . "=" . $options[TO_USER_ID];
    if ( isset($options[REASON]) ) $conds[] = REASON . "='" . $options[REASON] . "'";
    if ( isset($options['between']) ) $conds[] = "stamp BETWEEN {$options['between'][0]} AND {$options['between'][1]}";

    if ( empty($conds) ) return null;

    $q_where = "WHERE " . implode(' AND ', $conds);
    $q = "SELECT * FROM api_point_history $q_where";
    if ( isset($options['order']) && isset($options['orderby'])) {
        $q .= " ORDER BY ".$options['orderby']." " . $options['order'];
    } else {
        $q .= " ORDER BY ID DESC";
    }
    if ( isset($options['limit']) ) {
        $q .= " LIMIT " . $options['limit'];
    } else {
        $q .= " LIMIT 200";
    }

    return $wpdb->get_results($q, ARRAY_A);
}

/**
 * 사용자 포인트를 리턴한다. 값이 없으면 0 을 리턴한다.
 *
 * 주의: get_user_meta 는 캐시를 하기 때문에 직접 DB 쿼리를 한다.
 *
 * @param $user_ID
 * @return mixed
 *
 * @example
    $from_applied = add_user_point($user_ID, get_like_deduction_point() );
    $to_applied = add_user_point($to_user_ID, get_like_point() );
 */
function get_user_point($user_ID): int
{
    if ( !$user_ID) return 0;
    global $wpdb;
    $q = "SELECT meta_value FROM wp_usermeta WHERE user_id=$user_ID AND meta_key='".POINT."'";
    $re = $wpdb->get_var($q);
    if ( empty($re) ) return 0;
    else return $re;
}

/**
 * 회원의 포인트 지정 함수는 이 함수를 통해서 해야만 한다.
 * 이 함수에서, 포인트 음수의 값이 들어오면 0 으로 저장한다.
 * @param $user_ID
 * @param $point
 */
function set_user_point($user_ID, $point) {
    if ( $point < 0 ) $point = 0;
    update_user_meta($user_ID, POINT, $point);
}


/**
 * 포인트 증/감 후, 증가된 값은 양의 정수, 차감 된 값은 음의 수로 리턴.
 *
 * 입력된 $point 만큼 증가한다. 만약, $point 가 음수 이면 차감한다.
 * 단, 음수로 저장되는 경우, 최소 0으로 저장한다.
 *
 * @param $user_ID
 * @param $point
 *
 * @return int|mixed
 *
 * - 적용된 포인트를 음/양의 값으로 리턴한다. 이 리턴되는 값을 from_user_point_apply 또는 to_user_point_apply 에 넣으면 된다.
 */
function add_user_point($user_ID, $point): int
{
    $user_point = get_user_point($user_ID);
    $saving_point = $user_point + $point;

    // 저장되려는 포인트가 0 보다 작으면,
    if ( $saving_point < 0 ) {
        // 0 을 저장하고,
        set_user_point($user_ID, 0);
        // 실제 차감된 포인트를 리턴
        return -$user_point;
    } else {
        // 저장되려는 포인트가 양수이면, 저장하
        set_user_point($user_ID, $saving_point);
        // 전체 차감 포인트를 리턴
        return $point;
    }

}



/**
 * 회원의 포인트가 해당 REASON 의 포인트 보다 모자라면 true 를 리턴한다.
 *
 * 예를 들어, 추천을 하려는 회원의 포인트가 모자라는 경우, 이 함수를 통해서 검사를 할 수 있다.
 * 추천하는데 차감(소모)되는 포인트가 100 인데, 회원 포인트가 50 밖에 없다면, 50이 모자란다. 이 때, true 를 리턴한다.
 *
 *
 * @param $user_ID
 * @param $reason
 * @return bool
 *
 * @example
 *              if ( lack_of_point( $from_user->ID, POINT_DISLIKE ) ) return ERROR_LACK_OF_POINT;
 */
function lack_of_point($user_ID, $reason): bool
{
    $reason_point = get_option($reason, 0);
    if ( $reason_point == 0 ) return false;
    return ( get_user_point($user_ID) + $reason_point ) < 0;
}



/**
 * 포인트 기록 테이블에서, $stamp 시간 내의 입력된 $actions 의 레코드를 수를 찾아 리턴한다.
 * @param $stamp
 * @param $actions
 * @return int|string|null
 */
function count_my_point_actions($stamp, $actions) {
    if ( ! $stamp ) return 0;
    $reasons = [];
    foreach( $actions as $r ) {
        $reasons[] = REASON . "='$r'";
    }
    $reason_ors = "(" . implode(" OR ", $reasons) . ")";
    $my_user_ID = wp_get_current_user()->ID;
    $q = "SELECT COUNT(*) FROM api_point_history WHERE from_user_ID=$my_user_ID AND $reason_ors";
    global $wpdb;
    return $wpdb->get_var($q);
}


/**
 * 포인트 기록을 한다.
 *
 * - to_user_ID 는 target_ID 에 의해서 자동으로 결정된다. 특히, target_ID 가 post_123, comment_456 과 같이 입력되면, 해당 글/코멘트에서 사용자 번호를 뽑아낸다.
 * - 그 외에, 대부분의 경우, 예를 들어,
 *   - 사용자 A/B 가 아닌 경우, 즉, 회원 가입, 로그인 등에서 자동으로 포인트가 생겨나는 경우 또는
 *   - 상품 구매시 적립금 등,
 *   한 사용자가 다른 사용자에게 포인트 영향을 주지 않는다면, from_user_ID 와 to_user_ID 의 값은 동일하다.
 *   단, 관리자가 상품 주문을 confirm 하는 경우, 로그인 사용자가 관리자, 대상은 회원이기 때문에 from_user_ID 와 to_user_ID 가 달라야 한다.
 *
 * @param string $reason 포인트 액션(REASON)
 * @param int $from_user_point_apply 현재 사용자의 포인트 증/감. 추천의 경우, 자신의 증/감 포인트. 글 쓰기의 경우 자신의 증/감 포인트.
 * @param int $to_user_point_apply 상대 사용자의 포인트 증/감. 추천의 경우, 상대방 증/감 포인트.
 * @param string|null $target_ID 글/코멘트 번호. 예) post_123, comment_456
 * @param int $cat_ID 카테고리 번호. 숫자.
 * @return int
 *
 * @example
    add_point_history(
        $in['choice'] == 'Y' ? POINT_LIKE : $data[REASON] = POINT_DISLIKE,
        $from_applied,
        $to_appiled,
        isset($in['post_ID']) ? 'post_' . $in['post_ID'] : 'comment_' . $in['comment_ID'],
        $cat_ID
    );
 */
function add_point_history(string $reason, int $from_user_point_apply, int $to_user_point_apply=0, string $target_ID=null, int $cat_ID=0): int {



    // 받는 사람 아이디
    $to_user_ID = 0;

    // 회원 가입, 로그인에서 자기 자신을 가르키는 숫자, target_ID 는 숫자.
    // 게시판/코멘트에서는 글 번호. 추천/비추천을 할 때, target_ID 는 글/코멘트 번호.
    if ( $target_ID && is_string($target_ID) ) {
        $arr = explode('_', $target_ID);
        if ( $arr[0] == 'post' ) {
            $post = get_post($arr[1]);
            $to_user_ID = $post->post_author;
        } else if ( $arr[0] == 'comment' ) {
            $comment = get_comment($arr[1]);
            $to_user_ID = $comment->user_id;
        }
    }
    if ( in_array($reason, [POINT_ORDER_CONFIRM, POINT_ITEM_ORDER, POINT_ITEM_RESTORE]) ) {
        // 주문 또는 구매확정을 할 때에는 from_user_ID 와 to_user_ID 가 동일하다.
        $order = get_order($target_ID);
        $to_user_ID = $order['user_ID'];
    }
    if ( in_array($reason, [POINT_REGISTER, POINT_LOGIN]) ) {
        // 로그인, 가입에서는 from_user_ID 와 to_user_ID 가 동일하다.
        $to_user_ID = wp_get_current_user()->ID;
    }


    // 포인트 기록
    $history = [
        FROM_USER_ID => wp_get_current_user()->ID,
        TO_USER_ID => $to_user_ID,
        REASON => $reason,

        // 아래의 값은 상황에 따라 변경이 되어야 한다.
        'from_user_point_apply' => $from_user_point_apply,
        'from_user_point_after' => get_user_point(wp_get_current_user()->ID),
        'to_user_point_apply' => $to_user_point_apply,
        'to_user_point_after' => get_user_point($to_user_ID),

        // 이 값도 상황에 따라 변경되어야 한다.
        'target_ID' => $target_ID,
        // 글/코멘트에서는 카테고리 값이 기록된다.
        'cat_ID' => $cat_ID,
        'stamp' => time(),
    ];


    global $wpdb;
    $wpdb->insert('api_point_history', $history);

    return $wpdb->insert_id;
}

/// 상품 주문 했을 때, 추가한 포인트를 뺀다.
function api_item_order_point_restore($ID) {
    $order = get_order($ID);
    $info = decode_order_info($order);
    $point = $info['pointToUse'];
    add_user_point($order['user_ID'], $point);
    add_point_history(POINT_ITEM_RESTORE, 0, $point, $ID, 0 );
}


/**
 * 내가 쓴 $ID (글 또는 코멘트) 에 대해서, $point 를 증/감하고 기록을 남기고 기록 ID 리턴한다.
 *
 * 참고: 관리자는 타인의 글을 삭제 할 수 있다. 따라서 자기 글/코멘트가 아니면, 그냥 리턴한다.
 *
 * @param string $reason
 * @param int $ID - 글 또는 코멘트 번호
 * @return int
 */
function api_forum_point_change(string $reason, int $ID): int {

    if ( in_array($reason, [ POINT_POST_CREATE, POINT_POST_DELETE ]) ) {
        if ( is_my_post($ID) == false ) return 0;
        $cat_ID = get_first_category_ID($ID);
        $category = get_first_category($ID);
        $point = category_meta($category, $reason, 0);
        $target_ID = "post_$ID";
    } else if ( in_array($reason, [ POINT_COMMENT_CREATE, POINT_COMMENT_DELETE ] ) ) {
        if ( is_my_comment($ID) == false ) return 0;
        $comment = get_comment( $ID );
        $cat_ID = get_first_category_ID($comment->comment_post_ID);
        $category = get_first_category($comment->comment_post_ID);
        $point = category_meta($category, $reason, 0);
        $target_ID = "comment_$ID";
    } else {
        die("api_forum_point_change() : Wrong reason.");
    }

    // 제한에 걸리면, 포인트를 추가하지 않는다.
    if ( category_hourly_limit($category) || category_daily_limit($category) ) return 0;


    // 포인트 추가하기
    $applied = add_user_point(my('ID'), $point );

    // 포인트 기록 남기기
    return add_point_history(
        $reason,
        $applied,
        $applied,
        $target_ID,
        $cat_ID
    );
}

/**
 * 카테고리 별 글/코멘트 쓰기 제한에 걸렸으면 true 를 리턴한다.
 * @param string $category
 * @return bool
 */
function category_hourly_limit(string $category): bool {
    return count_over(
        [ POINT_POST_CREATE, POINT_COMMENT_CREATE ], // 글/코멘트 작성을
        get_category_hour_limit($category) * 60 * 60, // 특정 시간에, 시간 단위 이므로 * 60 * 60 을 하여 초로 변경.
        get_category_hour_limit_count($category) // count 회 수 이상 했으면,
    );
}

/**
 * 카테고리 별 일/수, 글/코멘트 쓰기 제한에 걸렸으면 true 를 리턴한다.
 * @param string $category
 * @return bool
 */
function category_daily_limit(string $category): bool {
    // 추천/비추천 일/수 제한
    return count_over(
        [ POINT_POST_CREATE, POINT_COMMENT_CREATE ], // 글/코멘트 작성을
        24 * 60 * 60, // 하루에
        get_category_daily_limit_count($category) // count 회 수 이상 했으면,
    );
}


/**
 * @param $in
 * @return mixed
 */
function api_admin_point_update($in) {
    if ( admin() == false ) return ERROR_PERMISSION_DENIED;
    $user = get_user_by('id', $in['user_ID']);
    if ( ! $user ) return ERROR_USER_NOT_FOUND;
    if ( isset($in['point']) == false ) return ERROR_POINT_IS_NOT_SET;
    if ( $in['point'] < 0 ) return ERROR_POINT_CANNOT_BE_SET_LESS_THAN_ZERO;
    $applied = add_user_point($in['user_ID'], $in['point']);
    return ['ID' => add_point_history(
        POINT_UPDATE,
        $applied,
        $applied,
        $in['user_ID'],
        0
    )];
}