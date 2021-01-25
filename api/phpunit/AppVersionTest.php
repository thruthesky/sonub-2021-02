<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;



require_once("../../../wp-load.php");


final class AppVersionTest extends TestCase
{
    public function testVersion(): void
    {
        $re = getRoute(['route' => 'app.version']);
        self::assertSame($re['code'], 0, "Code must be 0. But got $re[code]");
        require(API_DIR . '/routes/app.route.php');
        self::assertSame($re['data']['version'], (new AppRoute())->version()['version'], 'version should be:' . (new AppRoute())->version()['version']);
    }
}


