<?php
//
//define('PURCHASE_HISTORY_TABLE', 'purchase_history');
//
//define('ERROR_FAILED_RECORD_PURCHASE_FAILURE', 'ERROR_FAILED_RECORD_PURCHASE_FAILURE');
//define('ERROR_FAILED_RECORD_PURCHASE_PENDING', 'ERROR_FAILED_RECORD_PURCHASE_PENDING');
//define('ERROR_FAILED_RECORD_PURCHASE_SUCCESS', 'ERROR_FAILED_RECORD_PURCHASE_SUCCESS');
//
//define('FAILURE', 'failure');
//define('PENDING', 'pending');
//define('SUCCESS', 'success');
//
//
//define('ERROR_MISSING_PRODUCT_ID', 'ERROR_MISSING_PRODUCT_ID');
//define('ERROR_MISSING_PRODUCT_TITLE', 'ERROR_MISSING_PRODUCT_TITLE');
//define('ERROR_MISSING_PRODUCT_DESCRIPTION', 'ERROR_MISSING_PRODUCT_DESCRIPTION');
//define('ERROR_MISSING_PRODUCT_PRICE', 'ERROR_MISSING_PRODUCT_PRICE');
//
//define('ERROR_MISSING_PRODUCT_SKPRICE', 'ERROR_MISSING_PRODUCT_SKPRICE');
//define('ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYCODE', 'ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYCODE');
//define('ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYSYMBOL', 'ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYSYMBOL');
//define('ERROR_MISSING_PRODUCT_SKIDENTIFIER', 'ERROR_MISSING_PRODUCT_SKIDENTIFIER');
//
//
//define('ERROR_MISSING_PURCHASE_ID', 'ERROR_MISSING_PURCHASE_ID');
//
//
//class PurchaseRoute {
//
////    private function privateFunc() {
////        // ..
////    }
//
//
//
//    /**
//     * @param null $id - is the id of record. Or it can be directly called by client with the input of in('id').
//     * @return array
//     */
//    public function getPurchaseRecord($id = null): array {
//        if ( empty($id) ) $id = in('id');
//        global $wpdb;
//        return $wpdb->get_row("SELECT * FROM " . PURCHASE_HISTORY_TABLE . " WHERE ID=$id", ARRAY_A);
//    }
//
//    public function myPurchase()
//    {
//        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
//        global $wpdb;
//        $user_ID = wp_get_current_user()->ID;
//        $sql = "SELECT * FROM " . PURCHASE_HISTORY_TABLE . " WHERE user_ID='$user_ID' AND status='success' ORDER BY ID DESC";
//
//        $results = $wpdb->get_results($sql, ARRAY_A);
//
//        // TODO add more information or summary
////        $rets = [];
////        foreach ($results as $p) {
////            $rets['total'] =
////        }
//
//        if ( $results ) return $results;
//        else return [];
//    }
//
//
//    public function recordFailure()
//    {
//        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
//        global $wpdb;
//        $data = [
//            'stamp' => time(),
//            'user_ID' => wp_get_current_user()->ID,
//            'status' => FAILURE,
//            'purchaseDetails_productID' => in('purchaseDetails_productID', ''),
//            'purchaseDetails_skPaymentTransaction_transactionIdentifier' => in('purchaseDetails_skPaymentTransaction_transactionIdentifier', '')
//        ];
//
//        $ret = $wpdb->insert(PURCHASE_HISTORY_TABLE, $data);
//        $ID = $wpdb->insert_id;
//        if($ret === false)  return ERROR_FAILED_RECORD_PURCHASE_FAILURE;
//        return $this->getPurchaseRecord($ID);
//    }
//
//    public function recordPending()
//    {
//        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
//        if ( !in('productDetails_id')) return ERROR_MISSING_PRODUCT_ID;
//        if ( !in('productDetails_price')) return ERROR_MISSING_PRODUCT_PRICE;
//
//
//        global $wpdb;
//        $data = [
//            'stamp' => time(),
//            'user_ID' => wp_get_current_user()->ID,
//            'status' => PENDING,
//            'productDetails_id' => in('productDetails_id'),
//            'productDetails_title' => in('productDetails_title', ''),
//            'productDetails_description' => in('productDetails_description', ''),
//            'productDetails_price' => in('productDetails_price', ''),
//            'purchaseDetails_productID' => in('purchaseDetails_productID', ''),
//            'purchaseDetails_pendingCompletePurchase' => in('purchaseDetails_pendingCompletePurchase', false),
//            'purchaseDetails_verificationData_localVerificationData' => in('purchaseDetails_verificationData_localVerificationData', ''),
//            'purchaseDetails_verificationData_serverVerificationData' => in('purchaseDetails_verificationData_serverVerificationData', ''),
//        ];
//        $ret = $wpdb->insert(PURCHASE_HISTORY_TABLE, $data);
//        $ID = $wpdb->insert_id;
//        if($ret === false)  return ERROR_FAILED_RECORD_PURCHASE_PENDING;
//        return $this->getPurchaseRecord($ID);
//    }
//
//    public function recordSuccess()
//    {
//        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
//        if ( !in('productDetails_id')) return ERROR_MISSING_PRODUCT_ID;
//        if ( !in('productDetails_price')) return ERROR_MISSING_PRODUCT_PRICE;
//
//        global $wpdb;
//        $data = [
//            'stamp' => time(),
//            'user_ID' => wp_get_current_user()->ID,
//            'status' => SUCCESS,
//            'productDetails_id' => in('productDetails_id'),
//            'productDetails_title' => in('productDetails_title', ''),
//            'productDetails_description' => in('productDetails_description', ''),
//            'productDetails_price' => in('productDetails_price', ''),
//            'purchaseDetails_transactionDate' => in('purchaseDetails_transactionDate', ''),
//            'purchaseDetails_purchaseID' => in('purchaseDetails_purchaseID', ''),
//            'purchaseDetails_skPaymentTransaction_payment_productIdentifier' => in('purchaseDetails_skPaymentTransaction_payment_productIdentifier', ''),
//            'purchaseDetails_skPaymentTransaction_payment_quantity' => in('purchaseDetails_skPaymentTransaction_payment_quantity', ''),
//            'purchaseDetails_skPaymentTransaction_transactionIdentifier' => in('purchaseDetails_skPaymentTransaction_transactionIdentifier', ''),
//            'purchaseDetails_skPaymentTransaction_transactionTimeStamp' => in('purchaseDetails_skPaymentTransaction_transactionTimeStamp', ''),
//            'purchaseDetails_verificationData_localVerificationData' => in('purchaseDetails_verificationData_localVerificationData', ''),
//            'purchaseDetails_verificationData_serverVerificationData' => in('purchaseDetails_verificationData_serverVerificationData', ''),
//            'purchaseDetails_pendingCompletePurchase' => in('purchaseDetails_pendingCompletePurchase', True),
//            'productDetails_skProduct_price' => in('productDetails_skProduct_price', ''),
//            'productDetails_skProduct_priceLocale_currencyCode' => in('productDetails_skProduct_priceLocale_currencyCode', ''),
//            'productDetails_skProduct_priceLocale_currencySymbol' => in('productDetails_skProduct_priceLocale_currencySymbol', ''),
//            'productDetails_skProduct_productIdentifier' => in('productDetails_skProduct_productIdentifier', '')
//        ];
//        $ret = $wpdb->insert(PURCHASE_HISTORY_TABLE, $data);
//        $ID = $wpdb->insert_id;
//        if($ret === false) return ERROR_FAILED_RECORD_PURCHASE_SUCCESS;
//        return $this->getPurchaseRecord($ID);
//    }
//
//}
