<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

define('V3_DIR', '.');
require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/ext/purchase.route.php');


define('PENDING_ID', '15');
define('FAILURE_ID', '10');
define('SUCCESS_ID', '5');


final class failure extends TestCase
{

    public function testMethods(): void
    {
        wp_set_current_user(2);
        $profile = profile();
        $testData = [
            'route' => 'purchase.createHistory',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($testData);
        self::assertTrue($re['code'] === ERROR_METHOD_NOT_EXIST, "$re[code]");



    }
    public function testSetFailurePurchaseRequestData(): void
    {
        wp_set_current_user(FAILURE_ID);
        $profile = profile();

        $request = [
            'route' => 'purchase.recordFailure',
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], ERROR_EMPTY_SESSION_ID);

        $request = [
            'route' => 'purchase.recordFailure',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], 0);
        $this->assertTrue($re['data']['user_ID'] == FAILURE_ID);
        $this->assertTrue($re['data']['ID'] > 0);
        $this->assertTrue($re['data']['status'] == FAILURE);
    }

    public function testSetPendingPurchaseRequestData(): void
    {
        wp_set_current_user(PENDING_ID);
        $profile = profile();

        $request = [
            'route' => 'purchase.recordPending',
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], ERROR_EMPTY_SESSION_ID, 'session id missing');

        $request = [
            'route' => 'purchase.recordPending',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'],  ERROR_MISSING_PRODUCT_ID, 'product id missing');

        $request['productDetails_id'] = 'box101';
        $re = getRoute($request);
        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_PRICE, 'price missing');


        $request['productDetails_price'] = 'P100';
        $re = getRoute($request);
        $this->assertSame($re['code'], 0);
        $this->assertNotEmpty($re['data']['ID']);

        $this->assertTrue($re['data']['user_ID'] == PENDING_ID);
        $this->assertNotEmpty($re['data']['ID']);
        $this->assertTrue($re['data']['status'] == PENDING);
        $this->assertTrue($re['data']['productDetails_id'] === 'box101');
        $this->assertTrue($re['data']['productDetails_price'] === 'P100');
        $this->assertTrue($re['data']['purchaseDetails_pendingCompletePurchase'] == false);


        $purchase = new PurchaseRoute();
        $record = $purchase->getPurchaseRecord($re['data']['ID']);
        $this->assertTrue($re['data']['status'] === $record['status']);
        $this->assertTrue($re['data']['productDetails_id'] === $record['productDetails_id']);
        $this->assertTrue($re['data']['productDetails_price'] === $record['productDetails_price']);
        $this->assertTrue($re['data']['purchaseDetails_pendingCompletePurchase'] == $record['purchaseDetails_pendingCompletePurchase']);

    }

    public function testSetSuccessPurchaseRequestData(): void
    {

        wp_set_current_user(SUCCESS_ID);
        $profile = profile();

        $request = [
            'route' => 'purchase.recordSuccess',
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], ERROR_EMPTY_SESSION_ID);

        $request = [
            'route' => 'purchase.recordSuccess',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($request);

        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_ID);

        $request['productDetails_id'] = 'item555';
        $re = getRoute($request);
        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_PRICE);


//        $request['productDetails_price'] = 'P90';
//        $re = getRoute($request);
//
//        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_SKPRICE);
//
//        $request['productDetails_skProduct_price'] = '90';
//        $re = getRoute($request);
//        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYCODE);
//
//        $request['productDetails_skProduct_priceLocale_currencyCode'] = 'PHP';
//        $re = getRoute($request);
//        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_SKPRICELOCALE_CURRENCYSYMBOL);
//
//        $request['productDetails_skProduct_priceLocale_currencySymbol'] = 'P';
//        $re = getRoute($request);
//        $this->assertSame($re['code'], ERROR_MISSING_PRODUCT_SKIDENTIFIER);

//        $request['productDetails_skProduct_productIdentifier'] = '12345a';

        $request['productDetails_price'] = 'P90';
        $re = getRoute($request);
        $this->assertTrue($re['data']['user_ID'] == SUCCESS_ID);
        $this->assertNotEmpty($re['data']['ID']);
        $this->assertTrue($re['data']['status'] == SUCCESS);

        $purchase = new PurchaseRoute();
        $record = $purchase->getPurchaseRecord($re['data']['ID']);

        $this->assertTrue($re['data']['status'] === $record['status']);
        $this->assertTrue($re['data']['productDetails_id'] === $record['productDetails_id']);
        $this->assertTrue($re['data']['productDetails_price'] === $record['productDetails_price']);
        $this->assertTrue($re['data']['purchaseDetails_pendingCompletePurchase'] == $record['purchaseDetails_pendingCompletePurchase']);

    }

    public function testMyPurchaseRecord(): void
    {
        wp_set_current_user(1);
        $profile = profile();
        $request = [
            'route' => 'purchase.myPurchase',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], 0);
        $this->assertTrue(count($re['data']) === 0);


        wp_set_current_user(SUCCESS_ID);
        $profile = profile();
        $request = [
            'route' => 'purchase.myPurchase',
            'session_id' => $profile['session_id'],
        ];
        $re = getRoute($request);
        $this->assertSame($re['code'], 0);
        $this->assertTrue(count($re['data']) > 0);
    }





}