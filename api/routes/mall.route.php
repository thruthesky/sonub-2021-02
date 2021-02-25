<?php
/**
 * @file app.route.php
 */
/**
 * Class AppRoute
 */
class MallRoute
{

    /**
     * @return array
     */
    public function options(): array {
        return [
            'delivery_fee_free_limit' => get_option('delivery_fee_free_limit', DEFAULT_DELIVERY_FEE_FREE_LIMIT),
            'delivery_fee_price' => get_option('delivery_fee_price', DEFAULT_DELIVERY_FEE_PRICE),
        ];
    }

    /**
     * @param $in
     * @return mixed
     */
    public function order($in) {
        $data = [
            'user_ID' => wp_get_current_user()->ID,
            'stamp' => time(),
            'info' => $in['info'],
        ];

        debug_log('data: ', $data);
        global $wpdb;

        $info = json_decode($data['info'], ARRAY_A);
        $point = $info['pointToUse'];

        debug_log("point: $point");

        /// 상품 주문을 할 때, 회원 포인트를 사용한다면,
        if ( $point ) {
            if ( my('point') < $point ) { // 포인트가 모자라면, 주문을 하지 못하도록 한다.
                return ERROR_LACK_OF_POINT;
            }
        }


        $wpdb->insert('api_order_history', $data);
        $ID = $wpdb->insert_id;


        // 포인트를 차감하고 기록을 남긴다.
        $applied = add_user_point(my('ID'), -$point );
        debug_log("applied: $applied");
        add_point_history(
            POINT_ITEM_ORDER,
            $applied,
            $applied,
            $ID,
            0
        );

        debug_log("ID: $ID");
        $res = $wpdb->get_row("SELECT * FROM api_order_history WHERE ID=$ID", ARRAY_A);
        $res['info'] = json_decode($res['info']);
        return $res;
    }

    public function myOrders($in) {
        global $wpdb;
        $user_ID = wp_get_current_user()->ID;
        $rows = $wpdb->get_results("SELECT * FROM api_order_history WHERE user_ID=$user_ID ORDER BY stamp DESC", ARRAY_A);

        $rets = [];
        foreach($rows as $row) {
            $row['info'] = json_decode($row['info']);
            $rets[] = $row;
        }
        return $rets;
    }
}

