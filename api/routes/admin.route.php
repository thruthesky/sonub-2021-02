<?php

define('ERROR_NOT_AN_ADMIN', 'ERROR_NOT_AN_ADMIN');
define('ERROR_MALFORMED_DATE', 'ERROR_MALFORMED_DATE');

class AdminRoute
{

    private function admin_sql_query($in) {

        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
        if ( !admin()) return ERROR_NOT_AN_ADMIN;
        if ( !isset($in['table']) || empty($in['table'])) return ERROR_EMPTY_TABLE;

        $orderby = isset($in['orderby']) ? "ORDER BY $in[orderby]" : "";
        $limit = "LIMIT " . ($in['limit'] ?? 100);


        global $wpdb;
        $table = $in['table'];
        if( isset($in['cond'])) {
            $cond = stripslashes($in['cond']);
        } else {
            $cond = "";
        }


        $re = sql_injection_test($table);
        if ( $re ) return $re;
        $re = sql_injection_test($cond);
        if ( $re ) return $re;

        $where = '';
        if($cond) $where = "WHERE " . $cond;

        $q =  "SELECT * FROM $table $where $orderby $limit";
//        debug_log("sql_query: $q");
        return $wpdb->get_results($q, ARRAY_A);
    }

    public function purchaseSearch()
    {


        $q_and = [];
        if (in('user_id') && !empty(in('user_id'))) {
            $q_and[] = "user_id=" .  in('user_id');
        }
        if (in('status') && !empty(in('status'))) {
            $q_and[] = "status='" .  in('status') . "'";
        }

        if (in('product_id') && !empty(in('product_id'))) {
            $q_and[] = "productDetails_id='" .  in('product_id') . "'";
        }

        $start_date = in('start_date');
        if ($start_date && !empty($start_date)) {
            if (strlen($start_date) !== 8) return ERROR_MALFORMED_DATE;
            $Y = substr( $start_date, 0, 4);
            $m = substr( $start_date, 4, 2);
            $d = substr( $start_date, 6, 2);
            $stamp = mktime(0, 0, 0, $m, $d, $Y);
            $q_and[] = "stamp>=$stamp";
        }

        $end_date = in('end_date');
        if ($end_date && !empty($end_date)) {
            if (strlen($end_date) !== 8) return ERROR_MALFORMED_DATE;
            $Y = substr( $end_date, 0, 4);
            $m = substr( $end_date, 4, 2);
            $d = substr( $end_date, 6, 2);
            $stamp = mktime(0, 0, 0, $m, $d+1, $Y);
            $q_and[] = "stamp<$stamp";
        }



        if ( $q_and ) $cond = implode(' AND ', $q_and );
        else $cond = '';

        $req = [
            'table' => 'purchase_history',
            'cond' => $cond,
            'limit' => in('limit'),
            'orderby' => in('orderby')
        ];

        $res = $this->admin_sql_query($req);
        if ($res) return $res;
        return [];
    }


    public function userProfileUpdate($in) {
        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
        if ( !admin()) return ERROR_NOT_AN_ADMIN;
        return admin_user_profile_update($in);
    }


}