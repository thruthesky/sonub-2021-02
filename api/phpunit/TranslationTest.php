<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;


if ( !defined('API_DIR') ) define('API_DIR', '.');
require_once(API_DIR . '/api-load.php');
require_once(API_DIR . '/routes/translation.route.php');

define('USER_ID', 3);

define('LANGUAGE_TEST_SET', [
    'en' => ['name' => 'en name', 'address' => 'en address', 'input email' => 'Please, input your email'],
    'ch' => ['name' => 'ch name', 'address' => 'ch address'],
    'ja' => ['name' => 'ja name', 'address' => 'ja address', 'update phone' => 'Please, update your phone number'],
    'ko' => ['name' => 'ko name', 'address' => 'ko address', 'input email' => '이름을 입력하세요.', 'gender' => '성별', 'more' => '더보기...'],
]);



class TranslationTest extends TestCase {
    private $tr;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->tr = new TranslationRoute();
    }

    public function testAddLanguage() {

        delete_option(LANGUAGES);

        //
        $re = $this->tr->addLanguage([]);
        self::assertTrue($re === ERROR_PERMISSION_DENIED, ERROR_PERMISSION_DENIED);

        // login as admin
        wp_set_current_user(1);

        //
        $re = $this->tr->addLanguage([]);
        self::assertTrue($re === ERROR_EMPTY_LANGUAGE, 'Expected: ERROR_EMPTY_LANGUAGE');


        // success. add. en.
        $re = $this->tr->addLanguage(['language' => 'en']);
        self::assertTrue(count($re) === 1, "Expected: SUCCESS. ['en']. count === 1");

        // failure. add. en. again. duplicated error.
        $re = $this->tr->addLanguage(['language' => 'en']);
        self::assertTrue($re === ERROR_LANGUAGE_EXISTS, "Error expected. 'en' is already added.");

        // success. add. ko.
        $re = $this->tr->addLanguage(['language' => 'ko']);
        self::assertTrue(count($re) === 2, "Expected: SUCCESS adding 'ko'. count === 2");


        // success. add. ch.
        $re = $this->tr->addLanguage(['language' => 'ch']);
        self::assertTrue(count($re) === 3, "Expected: SUCCESS adding 'ko'. count === 3");


        $re = get_option(LANGUAGES);
        self::assertTrue( in_array('en', $re) && in_array('ko', $re) && in_array('ch', $re) && !in_array('ja', $re), 'Expected success. en,ko,ch');
    }

    public function testCodeAndList() {


        // reset data
        wp_set_current_user(1);
        delete_option(LANGUAGES);
        $this->tr->addLanguage(['language' => 'en']);
        $this->tr->addLanguage(['language' => 'ch']);
        $this->tr->addLanguage(['language' => 'ja']);
        $this->tr->addLanguage(['language' => 'ko']);
        global $wpdb;
        $wpdb->query("TRUNCATE " . TRANSLATIONS_TABLE);

        // logout
        wp_set_current_user(0);

        //
        $re = $this->tr->edit([]);
        self::assertTrue($re === ERROR_PERMISSION_DENIED, ERROR_PERMISSION_DENIED);


        // login as admin
        wp_set_current_user(1);


        $re = $this->tr->edit(['language' => 'fr', 'code' => 'name', 'value' => '...']);
        self::assertTrue($re === ERROR_LANGUAGE_NOT_EXISTS, ERROR_LANGUAGE_NOT_EXISTS);


        foreach (LANGUAGE_TEST_SET as $language => $row ){
            foreach ($row as $code => $value ) {
                $this->tr->edit(['language'=>$language, 'code' => $code, 'value' => $value ]);
            }
        }



        wp_set_current_user(0);
        $res = $this->tr->list(['format'=>'language-first']);
        $trs = $res['translations'];
        self::assertTrue(count($trs) === count(LANGUAGE_TEST_SET), 'count');
        self::assertTrue(count($trs['en']) === count(LANGUAGE_TEST_SET['en']), 'count');
        self::assertTrue(count($trs['ko']) === count(LANGUAGE_TEST_SET['ko']), 'count');
        self::assertTrue($trs['en']['input email'] === LANGUAGE_TEST_SET['en']['input email'], 'input email');
        self::assertTrue($trs['ko']['more'] === LANGUAGE_TEST_SET['ko']['more'], 'more');

        self::assertTrue($trs['en']['name'] === LANGUAGE_TEST_SET['en']['name'], 'name');
        self::assertTrue($trs['en']['name'] !== LANGUAGE_TEST_SET['ch']['name'], 'name');
//
//
        wp_set_current_user(1);
        $re = $this->tr->changeCode(['oldCode' => 'name', 'newCode' => 'nickname']);

        $res = $this->tr->list(['format'=>'language-first']);
        $trs = $res['translations'];
        self::assertTrue(isset($trs['en']['name']) === false, 'name changed: ');
        self::assertTrue(isset($trs['en']['nickname']), 'name changed: ');
        self::assertTrue($trs['en']['nickname'] === LANGUAGE_TEST_SET['en']['name'], "name changed: en");
        self::assertTrue($trs['ja']['nickname'] === LANGUAGE_TEST_SET['ja']['name'], 'name changed: ja');
    }
}




