<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;



require_once("../../../wp-load.php");
require(API_DIR . '/routes/app.route.php');


final class AppUpdatesTest extends TestCase
{
    private $session_id;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        wp_set_current_user(2);
        $_profile = profile();
        $this->session_id = $_profile['session_id'];
    }

    public function testInput(): void
    {
        $re = getRoute(['route' => 'app.updates']);
        self::assertTrue($re['code'] == ERROR_EMPTY_SESSION_ID, 'input test');
        $re = getRoute(['route' => 'app.updates', 'session_id' => '111']);
        self::assertTrue($re['code'] == ERROR_MALFORMED_SESSION_ID, $re['code']);
        $re = getRoute(['route' => 'app.updates', 'session_id' => $this->session_id]);
        self::assertTrue($re['code'] == ERROR_EMPTY_TABLE, $re['code']);
        $re = getRoute(['route' => 'app.updates', 'session_id' => $this->session_id, 'table' => 'api_bio']);
        self::assertTrue($re['code'] == ERROR_NO_FIELDS, $re['code']);
        $re = getRoute(['route' => 'app.updates', 'session_id' => $this->session_id, 'table' => 'api_bio', 'a' => 'b']);
        self::assertTrue($re['code'] == ERROR_UNKNOWN_COLUMN, $re['code']);
    }

    public function testUpdateOneField(): void
    {
        $re = getRoute(['route' => 'app.updates', 'session_id' => $this->session_id, 'table' => 'api_bio', 'name' => 'Apple']);
        self::assertTrue($re['code'] === 0, "Error code: $re[code]");
        $bio = table_get(['table' => 'api_bio']);
        self::assertTrue($re['data']['name'] === 'Apple');
        self::assertTrue($re['data']['name'] === $bio['name']);
    }

    public function testUpdateTwoFields(): void {

        $re = getRoute(['route' => 'app.updates', 'session_id' => $this->session_id, 'table' => 'api_bio', 'name' => 'Banana', 'city'=>'GimHae']);
        self::assertTrue($re['code'] === 0, "$re[code]");
        $bio = table_get(['table' => 'api_bio']);
        self::assertTrue($re['data']['name'] === 'Banana');
        self::assertTrue($re['data']['city'] === 'GimHae');
        self::assertTrue($re['data']['name'] === $bio['name']);
        self::assertTrue($re['data']['city'] === $bio['city']);
    }
}








