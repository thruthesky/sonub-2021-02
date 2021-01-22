<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

define('V3_DIR', '.');
require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/ext/purchase.route.php');
require_once(V3_DIR . '/ext/admin.route.php');

define('ADMIN_ID', 1);
define('USER_ID', 2);


class failure extends TestCase {

    public function testAdminPurchaseSearch() {

        wp_set_current_user(USER_ID);
        $profile = profile();


        $request = [
            'route' => 'purchase.recordSuccess',
            'session_id' => $profile['session_id'],
            'productDetails_id' => 'item555',
            'productDetails_price' => 'P90'
        ];
        $re = getRoute($request);
        $this->assertTrue($re['data']['user_ID'] == USER_ID);
        $this->assertNotEmpty($re['data']['ID']);
        $this->assertTrue($re['data']['status'] == 'success');

        $request = [
            'route' => 'admin.purchaseSearch',
        ];
        $re = getRoute($request);
        self::assertSame($re['code'], ERROR_EMPTY_SESSION_ID);

        $request['session_id'] = '12345a';
        $re = getRoute($request);
        self::assertSame($re['code'], ERROR_MALFORMED_SESSION_ID);

        $request['session_id'] = $profile['session_id']  . 1 ;
        $re = getRoute($request);
        self::assertSame($re['code'], ERROR_WRONG_SESSION_ID);

        $request['session_id'] = $profile['session_id'];
        $re = getRoute($request);
        self::assertSame($re['code'], ERROR_NOT_AN_ADMIN);


        wp_set_current_user(ADMIN_ID);
        $profile = profile();
        $request['session_id'] = $profile['session_id'];
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) > 0);

        $request['limit'] = 1;
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) == 1);

        $request['user_id'] = ADMIN_ID;
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) == 0);

        $request['user_id'] = USER_ID;
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) > 0);

        $request['start_date'] = '2020117';
        $re = getRoute($request);
        self::assertSame($re['code'], ERROR_MALFORMED_DATE);

        $start_date = date("Ymd");
        $request['start_date'] = $start_date;
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) > 0);

        $m = (int)date("m");
        $d = (int)date("d")+1;
        $stamp = mktime(0, 0, 0, $m, $d);
        $end_date = date("Ymd", $stamp);
        $request['start_date'] = $end_date;
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) === 0);


        $request = [
            "route" => "admin.purchaseSearch",
            "session_id" => $profile['session_id'],
            "limit" => 1,
            "end_date" => date("Ymd"),
        ];
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) > 0);

        $request = [
            "route" => "admin.purchaseSearch",
            "session_id" => $profile['session_id'],
            "limit" => 1,
            "start_date" => $end_date,
            "end_date" => $start_date,
        ];
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) === 0);

        $request = [
            "route" => "admin.purchaseSearch",
            "session_id" => $profile['session_id'],
            "limit" => 1,
            "start_date" => $start_date,
            "end_date" => $end_date,
        ];
        $re = getRoute($request);
        self::assertSame($re['code'], 0);
        self::assertTrue(count($re['data']) > 0);

    }

}
