<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once("../../../wp-load.php");



final class FirebaseDatabaseTest extends TestCase
{

    public function testConnection() {

        $db = getDatabase();
        $reference = $db->getReference('notifications/translation');

        $stamp = time();
        $reference->set(['updatedAt' => $stamp]);
        $snapshot = $reference->getSnapshot();
        $value = $snapshot->getValue();

        self::assertTrue($value['updatedAt'] === $stamp);
    }

}


