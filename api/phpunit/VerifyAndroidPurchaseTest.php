<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/ext/credit.class.php');
require_once(V3_DIR . '/ext/nalia.route.php');



class VerifyAndroidPurchaseTest extends TestCase
{

    private $nalia;

    public function __construct()
    {
        parent::__construct();
        $this->nalia = new NaliaRoute();
    }

    public function testAndroidPurchaseInputTest() {
        self::assertTrue( file_exists(SERVICE_ACCOUNT_FIREBASE_JSON_FILE_PATH) === true, 'service account json file exists' );


        $serverVerificationData = "...";
        $inputData = [];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_LOGIN_FIRST,ERROR_LOGIN_FIRST);

        wp_set_current_user(5);
        $profile = profile();
        $inputData = [
            'session_id' => $profile['session_id'],
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PLATFORM, ERROR_EMPTY_PLATFORM);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android'
        ];

        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_ID,ERROR_EMPTY_PRODUCT_ID);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PURCHASE_ID,ERROR_EMPTY_PURCHASE_ID);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_PRICE,ERROR_EMPTY_PRODUCT_PRICE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_TITLE,ERROR_EMPTY_PRODUCT_TITLE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => 'One Thousand Pesos',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_DESCRIPTION,ERROR_EMPTY_PRODUCT_DESCRIPTION);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_DATE,ERROR_EMPTY_TRANSACTION_DATE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_IDENTIFIER,ERROR_EMPTY_PRODUCT_IDENTIFIER);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_QUANTITY,ERROR_EMPTY_QUANTITY);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_IDENTIFIER,ERROR_EMPTY_TRANSACTION_IDENTIFIER);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
            'transactionIdentifier' => '987654321',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_TIMESTAMP,ERROR_EMPTY_TRANSACTION_TIMESTAMP);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
            'transactionIdentifier' => '987654321',
            'transactionTimeStamp' => '99999.88',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_LOCAL_VERIFICATION_DATA,ERROR_EMPTY_LOCAL_VERIFICATION_DATA);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
            'transactionIdentifier' => '987654321',
            'transactionTimeStamp' => '99999.88',
            'localVerificationData' => 'localVerificationData_this_qwertyuio',
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_SERVER_VERIFICATION_DATA,ERROR_EMPTY_SERVER_VERIFICATION_DATA);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
            'transactionIdentifier' => '987654321',
            'transactionTimeStamp' => '99999.88',
            'localVerificationData' => 'localVerificationData_this_qwertyuio',
            'serverVerificationData' => $serverVerificationData,
        ];
        $re = $this->nalia->verifyAndroidPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_APPLICATION_USERNAME,ERROR_EMPTY_APPLICATION_USERNAME);


        /// TODO UPDATE WHEN THE VERIFICATION FUNCTION IS WORKING

//        $inputData = [
//            'platform' => 'android',
//            'productID' => 'product_ID_101',
//            'purchaseID' => 'purchase_ID_abcd',
//            'price' => 'P1000',
//            'title' => '1k Item',
//            'description' => 'One Thousand Pesos Item',
//            'transactionDate' => '1234567890',
//            'productIdentifier' => 'same_as_product_id',
//            'quantity' => '777',
//            'transactionIdentifier' => '987654321',
//            'transactionTimeStamp' => '99999.88',
//            'localVerificationData' => 'localVerificationData_this_qwertyuio',
//            'serverVerificationData' => $serverVerificationData,
//            'applicationUsername' => '',
//        ];
//        $re = $this->nalia->verifyAndroidPurchase($inputData);
//        self::assertTrue(is_string($re) && strpos($re, ERROR_RECEIPT_INVALID) === 0, ERROR_RECEIPT_INVALID);



    }
    public function testAndroidPurchaseRealData() {
//        $nalia = new NaliaRoute();
//        $re = $nalia->verifyAndroidPurchase([
//            'productID' => 'lucky_box',
//            'serverVerificationData' =>
//                'neampogglalfkdokomcmdnhf.AO-J1OxhA2PECxpIIWIGoAf6oPbe4-QPoqUDrDKV9V9pPubtfPaH_NYw-HD6nz-JX8KnzUYAfw5aDvzqxZrivOA5udjvLV2lww']
//        );
//        print_r("$re\n");
//        self::assertTrue( is_string($re) && strpos($re, ERROR_RECEIPT_INVALID) === 0, 'receipt invalid');

        wp_set_current_user(2);
        $profile = profile();

        $serverVerificationData="neampogglalfkdokomcmdnhf.AO-J1OxhA2PECxpIIWIGoAf6oPbe4-QPoqUDrDKV9V9pPubtfPaH_NYw-HD6nz-JX8KnzUYAfw5aDvzqxZrivOA5udjvLV2lww";
        $data = [
            'route' => 'nalia.verifyAndroidPurchase',
            'session_id' => $profile['session_id'],
            'platform' => 'android',
            'productID' => 'lucky_box',
            'purchaseID' => '1000000766127645',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
            'transactionIdentifier' => '987654321',
            'transactionTimeStamp' => '99999.88',
            'localVerificationData' => 'localVerificationData_this_qwertyuio',
            'serverVerificationData' => $serverVerificationData,
            'localVerificationData_packageName' => 'kr.nalia.app',
            'applicationUsername' => '',
        ];

        $re = $this->nalia->verifyIOSPurchase($data);
        self::assertTrue(isset($re['history']), "history");
        $history = $re['history'];
        self::assertTrue($re['transactionId'] === $history["purchaseID"], "purchaseID");
        self::assertTrue($history['platform'] === $data["platform"], "platform");
        self::assertTrue($history['productID'] === $data["productID"], "productID");
        self::assertTrue($history['purchaseID'] === $data["purchaseID"], "purchaseID");
        self::assertTrue($history['price'] === $data["price"], "price");
        self::assertTrue($history['title'] === $data["title"], "title");
        self::assertTrue($history['description'] === $data["description"], "description");
        self::assertTrue($history['transactionDate'] === $data["transactionDate"], "transactionDate");
        self::assertTrue($history['productIdentifier'] === $data["productIdentifier"], "productIdentifier");
        self::assertTrue($history['quantity'] == $data["quantity"], "quantity");
        self::assertTrue($history['transactionIdentifier'] === $data["transactionIdentifier"], "transactionIdentifier");
        self::assertTrue($history['transactionTimeStamp'] == $data["transactionTimeStamp"], "transactionTimeStamp");
        self::assertTrue($history['localVerificationData'] === $data["localVerificationData"], "localVerificationData");
        self::assertTrue($history['serverVerificationData'] === $data["serverVerificationData"], "serverVerificationData");
        self::assertTrue($history['applicationUsername'] === "", 'applicationUsername');

//        $re = $this->nalia->verifyAndroidPurchase($inputData);
//        print_r($re);

//        $re = getRoute($inputData);
//        self::assertSame($re['code'], 0);
//        $data = $re['data'];
//        self::assertSame(count($re['data']), 4);
//        $history = $data['history'];
//        print_r($history);
//        self::assertTrue(isset($history), "history");
//        self::assertTrue($data['transactionId'] === $history["purchaseID"], "purchaseID");
//        self::assertTrue($history['platform'] === $inputData["platform"], "platform");
//        self::assertTrue($history['productID'] === $inputData["productID"], "productID");
//        self::assertTrue($history['purchaseID'] === $inputData["purchaseID"], "purchaseID");
//        self::assertTrue($history['price'] === $inputData["price"], "price");
//        self::assertTrue($history['title'] === $inputData["title"], "title");
//        self::assertTrue($history['description'] === $inputData["description"], "description");
//        self::assertTrue($history['transactionDate'] === $inputData["transactionDate"], "transactionDate");
//        self::assertTrue($history['productIdentifier'] === $inputData["productIdentifier"], "productIdentifier");
//        self::assertTrue($history['quantity'] == $inputData["quantity"], "quantity");
//        self::assertTrue($history['transactionIdentifier'] === $inputData["transactionIdentifier"], "transactionIdentifier");
//        self::assertTrue($history['transactionTimeStamp'] == $inputData["transactionTimeStamp"], "transactionTimeStamp");
//        self::assertTrue($history['localVerificationData'] === $inputData["localVerificationData"], "localVerificationData");
//        self::assertTrue($history['serverVerificationData'] === $inputData["serverVerificationData"], "serverVerificationData");
//        self::assertTrue($history['applicationUsername'] === $inputData["applicationUsername"], 'applicationUsername');

    }

//    public function testAndroidPurchaseDiamond() {
////        $nalia = new NaliaRoute();
////        $re = $nalia->verifyAndroidPurchase(['productID' => 'lucky_box', 'serverVerificationData' => 'neampogglalfkdokomcmdnhf.AO-J1OxhA2PECxpIIWIGoAf6oPbe4-QPoqUDrDKV9V9pPubtfPaH_NYw-HD6nz-JX8KnzUYAfw5aDvzqxZrivOA5udjvLV2lww']);
////        print_r("$re\n");
////        self::assertTrue( is_string($re) && strpos($re, ERROR_RECEIPT_INVALID) === 0, 'receipt invalid');
//    }

}








