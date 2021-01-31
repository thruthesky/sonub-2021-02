<?php

$failure_count = 0;

/**
 * @param $re
 * @param string $msg
 * @todo change name to `testSuccess`
 */
function testSuccess($re, $msg = '') {
    if ( is_string($re) && api_error($re) ) isTrue(false, "$msg, Error code: $re");
    else if ( isset($re['code']) && api_error($re['code']) )  isTrue(false, "$msg, Error code: $re[code]");
    else isTrue(true, $msg);
}

/**
 * @param $re - Route response or if it's string, it's error.
 * @param $error_code
 * @param string $msg
 * @todo change name to `testError`
 */
function testError($re, $error_code, $msg = '') {
    $code = is_string($re) ? $re : $re['code'];
    if ( $code === $error_code )
     isTrue($code === $error_code, "Expected error code: $error_code, $msg");
    else isTrue($code === $error_code, "Expected error code: $error_code, but got: $code");
}

function isTrue($re, $msg='') {
    global $failure_count;
    if ( $re && !api_error($re) ) {
        echo "SUCCESS: $msg\n";
    } else {
        $failure_count ++;
        if ( api_error($re) ) echo "FAILURE:  $msg, $re <<<<<<<<<<<<<<<<\n";
else        echo "FAILURE: $msg <<<<<<<<<<<<<<<<<<<<<<<\n";
    }
}

function displayTestSummary() {
    global $failure_count;

    echo "\n\n";
    echo "Date " . date('r') . "\n";
    echo "==================================================================\n";
    echo "Failure: $failure_count\n";
}
/// Clear test data
function clearDatabase() {
    global $wpdb;
    $wpdb->query("TRUNCATE " . JEWELRY_LOG_TABLE);
    $wpdb->query("TRUNCATE " . JEWELRY_DAILY_BONUS_TABLE);
    $wpdb->query("TRUNCATE " . JEWELRY_CREDIT_TABLE);
}