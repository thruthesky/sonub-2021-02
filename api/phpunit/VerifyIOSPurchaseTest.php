<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

define('V3_DIR', '.');
require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/ext/credit.class.php');
require_once(V3_DIR . '/ext/nalia.route.php');





class VerifyIOSPurchaseTest extends TestCase
{
    private $nalia;
    private $credit;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->nalia = new NaliaRoute();
        $this->credit = new Credit();
    }

    public function testPurchaseInputTest() {
        $this->nalia = new NaliaRoute();
        $serverVerificationData = "...";
        $inputData = [];

//        $re = $this->nalia->verifyPurchase($inputData);
//        self::assertTrue($re === ERROR_LOGIN_FIRST, $re);

        wp_set_current_user(5);
        $profile = profile();
        $inputData = [
            'session_id' => $profile['session_id'],
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PLATFORM, ERROR_EMPTY_PLATFORM);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS'
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_ID,ERROR_EMPTY_PRODUCT_ID);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PURCHASE_ID,ERROR_EMPTY_PURCHASE_ID);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_PRICE,ERROR_EMPTY_PRODUCT_PRICE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_TITLE,ERROR_EMPTY_PRODUCT_TITLE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => 'One Thousand Pesos',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_DESCRIPTION,ERROR_EMPTY_PRODUCT_DESCRIPTION);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_DATE,ERROR_EMPTY_TRANSACTION_DATE);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_PRODUCT_IDENTIFIER,ERROR_EMPTY_PRODUCT_IDENTIFIER);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_QUANTITY,ERROR_EMPTY_QUANTITY);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
            'productID' => 'product_ID_101',
            'purchaseID' => 'purchase_ID_abcd',
            'price' => 'P1000',
            'title' => '1k Item',
            'description' => 'One Thousand Pesos Item',
            'transactionDate' => '1234567890',
            'productIdentifier' => 'same_as_product_id',
            'quantity' => '777',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_IDENTIFIER,ERROR_EMPTY_TRANSACTION_IDENTIFIER);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
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
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_TRANSACTION_TIMESTAMP,ERROR_EMPTY_TRANSACTION_TIMESTAMP);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
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
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_LOCAL_VERIFICATION_DATA,ERROR_EMPTY_LOCAL_VERIFICATION_DATA);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'IOS',
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
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue($re === ERROR_EMPTY_SERVER_VERIFICATION_DATA,ERROR_EMPTY_SERVER_VERIFICATION_DATA);

//        $inputData = [
//            'session_id' => $profile['session_id'],
//            'platform' => 'ios',
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
//        ];
//        $re = $this->nalia->verifyPurchase($inputData);
//        self::assertTrue($re === ERROR_EMPTY_APPLICATION_USERNAME, "Expected: ERROR_EMPTY_APPLICATION_USERNAME! But got:" . $re);

        $inputData = [
            'session_id' => $profile['session_id'],
            'platform' => 'ios',
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
            'applicationUsername' => '',
        ];
        $re = $this->nalia->verifyPurchase($inputData);
        self::assertTrue(is_string($re) && strpos($re, ERROR_RECEIPT_INVALID) === 0, ERROR_RECEIPT_INVALID);

    }




    public function testPurchaseFakeVerificationData() {

        wp_set_current_user(5);
        $profile = profile();
        $serverVerificationData=".........A6O9zed/iKHqjTNVbcObTmipGmfFDkxbJrjzJZNVzcwhJPF/cZ//iIaawmggL40xa+MF3EGwVrsD02hY=";
        $inputData = [
            'route' => 'nalia.verifyPurchase',
            'session_id' => $profile['session_id'],
            'platform' => 'ios',
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
            'applicationUsername' => '',
        ];

        $re = getRoute($inputData);
        self::assertTrue(is_string($re['code']) && strpos($re['code'], ERROR_RECEIPT_INVALID) === 0, ERROR_RECEIPT_INVALID);
    }


    // @run chokidar '**/*.php' -c "phpunit phpunit/verifyIOSPurchaseTest.php --filter testGeneratePurchasedJewelry"
    public function testGeneratePurchasedJewelry() {

        wp_set_current_user(3);
        $before = $this->credit->getMyCreditJewelry();
        $after = $this->credit->generatePurchasedJewelry('goldbox', 1);
        $log = $this->credit->getJewelryLogs(wp_get_current_user()->ID, REASON_PAYMENT, ['limit' => 1, 'orderBy'=>'ID', 'sort'=>'DESC']);

        self::assertTrue($before[DIAMOND] === $after[DIAMOND], 'diamond should not be changed by goldbox');
        self::assertTrue((intval($before[GOLD]) + intval($log[0]['apply_gold'])) === intval($after[GOLD]), "{$before[GOLD]} + {$log[0]['apply_gold']} === {$after[GOLD]}");
        self::assertTrue((intval($before[SILVER]) + intval($log[0]['apply_silver'])) === intval($after[SILVER]), "silver apply");

        wp_set_current_user(3);
        $before = $this->credit->getMyCreditJewelry();
        $after = $this->credit->generatePurchasedJewelry('diamondbox', 2);
        $log = $this->credit->getJewelryLogs(wp_get_current_user()->ID, REASON_PAYMENT, ['limit' => 1, 'orderBy'=>'ID', 'sort'=>'DESC']);

        self::assertTrue((intval($before[DIAMOND]) + intval($log[0]['apply_diamond'])) === intval($after[DIAMOND]), "diamond apply");
        self::assertTrue((intval($before[GOLD]) + intval($log[0]['apply_gold'])) === intval($after[GOLD]), "{$before[GOLD]} + {$log[0]['apply_gold']} === {$after[GOLD]}");
        self::assertTrue((intval($before[SILVER]) + intval($log[0]['apply_silver'])) === intval($after[SILVER]), "silver apply");

        self::assertTrue($log[0]['item'] == 2, 'item 2');

    }


    public function testPurchaseGoldbox() {
        $serverVerificationData="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";
        wp_set_current_user(5);
        $profile = profile();

        $before = $this->credit->getMyCreditJewelry();

        $data = [
            'session_id' => $profile['session_id'],
            'platform' => 'ios',
            'productID' => 'goldbox',
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
            'applicationUsername' => '',
        ];
        $re = $this->nalia->verifyPurchase($data);

        self::assertTrue(isset($re['productId']), "history productId");
        self::assertTrue(isset($re['history']), "history set");
        self::assertTrue(isset($re['jewelry']), "jewelry set");

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
        self::assertTrue($history['applicationUsername'] === $data["applicationUsername"], 'applicationUsername');

        $logs = $this->credit->getJewelryLogs(wp_get_current_user()->ID, REASON_PAYMENT, ['limit' => 1, 'orderBy'=>'ID', 'sort'=>'DESC']);
        $log = $logs[0];
        self::assertTrue($history['ID'] == $log['item'], 'history.ID == item');

        $rate = GOLDBOX_RATE;

        self::assertTrue( between($log['apply_diamond'], $rate['min_diamond'], $rate['max_diamond']), 'diamond_rate' );
        self::assertTrue( between($log['apply_gold'], $rate['min_gold'], $rate['max_gold']), 'gold_rate' );
        self::assertTrue( between($log['apply_silver'], $rate['min_silver'], $rate['max_silver']), 'silver_rate' );

        $after = $this->credit->getMyCreditJewelry();

        self::assertTrue($before[DIAMOND] === $after[DIAMOND], 'diamond should not be changed by goldbox');
        self::assertTrue((intval($before[GOLD]) + intval($log['apply_gold'])) === intval($after[GOLD]), "{$before[GOLD]} + {$log['apply_gold']} === {$after[GOLD]}");
        self::assertTrue((intval($before[SILVER]) + intval($log['apply_silver'])) === intval($after[SILVER]), "silver apply");
    }





    public function testPurchaseDiamondbox() {

        wp_set_current_user(6);
        $profile = profile();

        $before = $this->credit->getMyCreditJewelry();

        $data  = [
            "user_ID" => "516",
      "productID"=> "diamondbox",
      "purchaseID"=> "1000000767150991",
      "price"=> "₩2500",
      "title"=> "다이아몬드 박스",
      "description"=> "더 많은 골드와 실버, 그리고 다이아몬드를 얻기 위한 보석 상자.",
      "transactionDate"=> "1611107554000",
      "localVerificationData"=>
          "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",
      "serverVerificationData"=>
          "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",
      "platform"=> "ios",
      "applicationUsername"=> null,
      "productIdentifier"=> "diamondbox",
      "quantity"=> 1,
      "transactionIdentifier"=> "1000000767150991",
      "transactionTimeStamp"=> 1611107554.0,
    ];
        $re = $this->nalia->verifyPurchase($data);

        $logs = $this->credit->getJewelryLogs(wp_get_current_user()->ID, REASON_PAYMENT, ['limit' => 1, 'orderBy'=>'ID', 'sort'=>'DESC']);
        $log = $logs[0];

        $rate = DIAMONDBOX_RATE;

        self::assertTrue( between($log['apply_diamond'], $rate['min_diamond'], $rate['max_diamond']), 'diamond_rate' );
        self::assertTrue( between($log['apply_gold'], $rate['min_gold'], $rate['max_gold']), 'gold_rate' );
        self::assertTrue( between($log['apply_silver'], $rate['min_silver'], $rate['max_silver']), 'silver_rate' );

        $after = $this->credit->getMyCreditJewelry();


        self::assertTrue((intval($before[DIAMOND]) + intval($log['apply_diamond'])) === intval($after[DIAMOND]), "diamond apply");
        self::assertTrue((intval($before[GOLD]) + intval($log['apply_gold'])) === intval($after[GOLD]), "{$before[GOLD]} + {$log['apply_gold']} === {$after[GOLD]}");
        self::assertTrue((intval($before[SILVER]) + intval($log['apply_silver'])) === intval($after[SILVER]), "silver apply");
    }




//    public function testCredit() {
        // get user's jewelry credit before pay
        // pay & leave logs
        // check log if it has status=success
        // get log of credit pay
        // get credit of user and compare user's credit before and after.
//    }
}











