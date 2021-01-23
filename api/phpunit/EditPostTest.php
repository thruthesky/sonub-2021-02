<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;


if ( !defined('API_DIR') ) define('API_DIR', '.');
require_once(API_DIR . '/api-load.php');


final class EditPostTest extends TestCase
{
    public function testInput(): void
    {
        $re = api_edit_post([]);
        self::assertTrue($re === ERROR_EMPTY_CATEGORY_OR_ID, 'Expect Error: ERROR_EMPTY_CATEGORY_OR_ID');


        $re = api_edit_post(['category' => 'uncategorized'] );







        print_r($re);
    }
}



