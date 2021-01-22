<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

define('API_DIR', '.');
require_once(API_DIR . '/api-load.php');
require_once(API_DIR . '/ext/purchase.route.php');

define('USER_ID', 3);


class failure extends TestCase {
    public function testPurchase() {

        wp_set_current_user(USER_ID);
        $profile = profile();

        $request = [
            'route' => 'purchase.recordSuccess',
            'session_id' => $profile['session_id'],
            'productDetails_id' => 'item555',
            'productDetails_price' => 'P90',
        ];

        $re = getRoute($request);

        self::assertSame($re['code'], 0);



    }

}
