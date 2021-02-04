<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;



require_once("../../../wp-load.php");



final class UpdateCategoryTest extends TestCase {

    public function testInput() {
        $re = update_category([]);
        self::assertTrue($re === ERROR_EMPTY_CATEGORY_ID, ERROR_EMPTY_CATEGORY_ID);

        $re = update_category(['cat_ID' => 123456789]);
        self::assertTrue($re === ERROR_CATEGORY_NOT_EXIST_BY_THAT_ID, ERROR_CATEGORY_NOT_EXIST_BY_THAT_ID);


//        $re = update_category(['cat_ID' => 1]);
//        self::assertTrue($re === ERROR_EMPTY_NAME, ERROR_EMPTY_NAME);
//
//        $re = update_category(['cat_ID' => 1, 'field' => 'cat_name']);
//        self::assertTrue($re === ERROR_EMPTY_VALUE, ERROR_EMPTY_VALUE);
    }
    public function testUpdateName() {
        $re = update_category(['cat_ID' => 1, 'field' => 'cat_name', 'value' => 'Apple']);
        self::assertTrue($re['cat_name'] == 'Apple');
    }
    public function testUpdateDescription() {
        $desc = "I like to eat, eat, eat apples and bananas!";
        $re = update_category(['cat_ID' => 1, 'field' => 'category_description', 'value' => $desc]);
        self::assertTrue($re['category_description'] === $desc);
    }

    public function testUpdateMeta() {
        $re = update_category(['cat_ID' => 1, 'field' => 'key1', 'value' => 'value1']);
        self::assertTrue($re['key1'] === 'value1');
        $re = update_category(['cat_ID' => 1, 'field' => 'a', 'value' => 'Apple']);
        self::assertTrue($re['a'] === 'Apple');
    }


    public function testMultiData() {
        $re = update_category(['cat_ID' => 2, 'a' => 'apple', 'b' => 'banana']);
        self::assertTrue($re['a'] === 'apple');
        self::assertTrue($re['b'] === 'banana');
    }



}


