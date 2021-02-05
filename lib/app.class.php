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
}

