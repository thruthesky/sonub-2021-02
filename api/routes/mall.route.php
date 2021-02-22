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
     * @param $in
     * @return array|object|void|null
     */
    public function order($in) {
        $data = [
            'user_ID' => wp_get_current_user()->ID,
            'stamp' => time(),
            'info' => $in['info'],
        ];

        debug_log('data: ', $data);
        global $wpdb;
        $wpdb->insert('api_order_history', $data);
        $ID = $wpdb->insert_id;
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

