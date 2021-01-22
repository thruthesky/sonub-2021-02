<?php
require_once(V3_DIR . '/ext/credit.class.php');


class NaliaRoute {


    private $credit;

    public function __construct()
    {
        $this->credit = new Credit();
    }

    public function giveJewelry() {
        return $this->credit->giveJewelry(in());
    }

    /**
     * Get my daily jewelry bonus of today.
     * @return mixed
     */
    public function getMyBonusJewelry() {
        if ( !is_user_logged_in()) return ERROR_LOGIN_FIRST;
        $re = $this->credit->getBonusJewelry(wp_get_current_user()->ID);
        if ( $re === null ) return ERROR_DAILY_BONUS_NOT_GENERATED;
        return $re;
    }



    /**
     * @deprecated
     * Reset the date of daily bonus.
     *
     * @param $todate - is the date in 'YYYYMMDD".
     *
     * @note use this function to change(reset) the date that applies to daily bonus date.
     */
    function setTodate($todate) {
        $this->todate = $todate;
    }


    /**
     * Generate daily bonus jewelry
     *
     * @param $user_ID
     * @param array $options
     *  Use options for test.
     * @return mixed 무료 보너스로 생성한 보석 레코드를 리턴. 이미 생성되었으면 에러 코드.
     */
    public function generateTodayBonus() {
        return $this->credit->generateTodayBonus(wp_get_current_user()->ID);
    }

    public function getMyCreditJewelry() {
        return $this->credit->getMyCreditJewelry();
    }

    /**
     * @param $in
     * @return array|string
     * @throws \Google\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyPurchase($in) {

        if ( !isset($in['platform'] ) ) return ERROR_EMPTY_PLATFORM;
        if ( !isset($in['productID'] ) ) return ERROR_EMPTY_PRODUCT_ID;
        if ( !isset($in['purchaseID'] ) ) return ERROR_EMPTY_PURCHASE_ID;
        if ( !isset($in['price'] ) ) return ERROR_EMPTY_PRODUCT_PRICE;
        if ( !isset($in['title'] ) ) return ERROR_EMPTY_PRODUCT_TITLE;
        if ( !isset($in['description'] ) ) return ERROR_EMPTY_PRODUCT_DESCRIPTION;
        if ( !isset($in['transactionDate'] ) ) return ERROR_EMPTY_TRANSACTION_DATE;
        if ( !isset($in['productIdentifier'] ) ) return ERROR_EMPTY_PRODUCT_IDENTIFIER;
        if ( !isset($in['quantity'] ) ) return ERROR_EMPTY_QUANTITY;
        if ( !isset($in['transactionIdentifier'] ) ) return ERROR_EMPTY_TRANSACTION_IDENTIFIER;
        if ( !isset($in['transactionTimeStamp'] ) ) return ERROR_EMPTY_TRANSACTION_TIMESTAMP;
        if ( !isset($in['localVerificationData'] ) ) return ERROR_EMPTY_LOCAL_VERIFICATION_DATA;
        if ( !isset($in['serverVerificationData'] ) ) return ERROR_EMPTY_SERVER_VERIFICATION_DATA;


        if ( $in['platform'] == 'android' ) {
            if ( !isset($in['localVerificationData_packageName']) ) return ERROR_EMPTY_PACKAGE_NAME;
        }


        if ( $in['platform'] == 'ios' ) {
            return $this->credit->verifyIOSPurchase($in);
        } else if ( $in['platform'] == 'android' ) {
            return $this->credit->verifyAndroidPurchase($in);
        } else {
            return ERROR_WRONG_PLATFORM;
        }
    }




}