<?php
/**
 * @file app.class.php
 */

/**
 * Class App
 */
class App {
    static function page(string $folder_and_name): bool {
        return strpos(in('page'), $folder_and_name) !== false;
    }
    static function getIDs($obj) {
        if ( ! $obj ) return [];
        $rets = [];
        foreach($obj as $o) {
            $rets[] = $o['ID'];
        }
        return $rets;
    }
}

