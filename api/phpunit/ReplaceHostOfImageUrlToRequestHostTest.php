<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;


if ( !defined('API_DIR') ) define('API_DIR', '.');
require_once(API_DIR . '/api-load.php');

define('URL_TEST_SET', [
    'a' => 'Apple',
    'b' => 'Banana',
    'files' => [
        [
            'ID' => 123, 'url' => 'abc', 'comments' => [
            'files' => [
                ['name' => 'abc', 'url' => 'https://non-url.com/wp-content/debug.jpg'],
                ['name' => 'abc', 'url' => 'https://nalia.kr/wp-content/uploads/2021/01/12345a.jpg'],
            ],
        ],
        ],
        ['ID' => 4, 'url' => 'four'],
        ['ID' => 5, 'url' => 'https://five.com/abc.jpg'],
        ['ID' => 6, 'url' => 'abc'],
        ['ID' => 7, 'url' => 'abc'],
        ['ID' => 8, 'url' => 'https://local.nalia.kr/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg'],
    ]
]);


final class ReplaceHostOfImageUrlToRequestHostTest extends TestCase {
    public function testUrl(): void {
        $re = replace_host_of_image_url_to_request_host(['code' => 0, 'data'=>URL_TEST_SET], 'https://abc.com/v3/index.php');
        self::assertTrue($re['code'] === 0, 'code to be 0');
        self::assertTrue($re['data']['a'] == 'Apple', 'a to be Apple');
        self::assertTrue($re['data']['b'] == 'Banana', 'a to be Apple');
        self::assertTrue($re['data']['files'][0]['comments']['files'][0]['url'] == 'https://non-url.com/wp-content/debug.jpg', 'https://non-url.com/wp-content/debug.jpg');
        self::assertTrue($re['data']['files'][0]['comments']['files'][1]['url'] == 'https://abc.com/wp-content/uploads/2021/01/12345a.jpg',  'https://abc.com/wp-content/uploads/2021/01/12345a.jpg');
        self::assertTrue($re['data']['files'][2]['url'] == 'https://five.com/abc.jpg', 'https://five.com/abc.jpg');
        self::assertTrue($re['data']['files'][5]['url'] == 'https://abc.com/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg', 'https://abc.com/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg');
    }
    public function testUrlWithFolder(): void {
        $re = replace_host_of_image_url_to_request_host(['code' => 0, 'data'=>URL_TEST_SET], 'http://192.168.0.5/wordpress/v506/v3/index.php');
//        print_r($re);
        self::assertTrue($re['code'] === 0, 'code to be 0');
        self::assertTrue($re['data']['a'] == 'Apple', 'a to be Apple');
        self::assertTrue($re['data']['b'] == 'Banana', 'a to be Apple');
        self::assertTrue($re['data']['files'][0]['comments']['files'][0]['url'] == 'https://non-url.com/wp-content/debug.jpg', 'https://non-url.com/wp-content/debug.jpg');
        self::assertTrue($re['data']['files'][0]['comments']['files'][1]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg',  'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg');
        self::assertTrue($re['data']['files'][2]['url'] == 'https://five.com/abc.jpg', 'https://five.com/abc.jpg');
        self::assertTrue($re['data']['files'][5]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg', 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg');
    }
    public function testUrlWithFolderAndQuery(): void {
        $re = replace_host_of_image_url_to_request_host(['code' => 0, 'data'=>URL_TEST_SET], 'http://192.168.0.5/wordpress/v506/v3/index.php?route=abc');
//        print_r($re);
        self::assertTrue($re['code'] === 0, 'code to be 0');
        self::assertTrue($re['data']['a'] == 'Apple', 'a to be Apple');
        self::assertTrue($re['data']['b'] == 'Banana', 'a to be Apple');
        self::assertTrue($re['data']['files'][0]['comments']['files'][0]['url'] == 'https://non-url.com/wp-content/debug.jpg', 'https://non-url.com/wp-content/debug.jpg');
        self::assertTrue($re['data']['files'][0]['comments']['files'][1]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg',  'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg');
        self::assertTrue($re['data']['files'][2]['url'] == 'https://five.com/abc.jpg', 'https://five.com/abc.jpg');
        self::assertTrue($re['data']['files'][5]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg', 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg');
    }
    public function testUrlWithFolderAndQueryWithoutIndex(): void {
        $re = replace_host_of_image_url_to_request_host(['code' => 0, 'data'=>URL_TEST_SET], 'http://192.168.0.5/wordpress/v506/v3?route=abc');
//        print_r($re);
        self::assertTrue($re['code'] === 0, 'code to be 0');
        self::assertTrue($re['data']['a'] == 'Apple', 'a to be Apple');
        self::assertTrue($re['data']['b'] == 'Banana', 'a to be Apple');
        self::assertTrue($re['data']['files'][0]['comments']['files'][0]['url'] == 'https://non-url.com/wp-content/debug.jpg', 'https://non-url.com/wp-content/debug.jpg');
        self::assertTrue($re['data']['files'][0]['comments']['files'][1]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg',  'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/12345a.jpg');
        self::assertTrue($re['data']['files'][2]['url'] == 'https://five.com/abc.jpg', 'https://five.com/abc.jpg');
        self::assertTrue($re['data']['files'][5]['url'] == 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg', 'http://192.168.0.5/wordpress/v506/wp-content/uploads/2021/01/534ff9b4261873f831d2a0a2190ecf86.jpg');
    }
}

