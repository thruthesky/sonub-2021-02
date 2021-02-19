<?php
/**
 * @file app.route.php
 */
/**
 * Class AppRoute
 */
class MallRoute
{
    public function order($in) {

        $data = [
            'user_ID' => wp_get_current_user()->ID,
            'stamp' => time(),
            'info' => fixJson($in['info']),
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
}

