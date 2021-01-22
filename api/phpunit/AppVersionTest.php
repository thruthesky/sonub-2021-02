<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

define('V3_DIR', '.');
require_once(V3_DIR . '/v3-load.php');


final class AppVersionTest extends TestCase
{
    public function testVersion(): void
    {
        $re = getRoute(['route' => 'app.version']);
        self::assertSame($re['code'], 0, "Code must be 0. But got $re[code]");
        require(V3_DIR . '/routes/app.route.php');
        self::assertSame($re['data']['version'], (new AppRoute())->version()['version'], 'version should be:' . (new AppRoute())->version()['version']);
    }
}


