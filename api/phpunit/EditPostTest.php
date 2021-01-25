<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;



require_once("../../../wp-load.php");


final class EditPostTest extends TestCase
{
    public function testInput(): void
    {
        $re = api_edit_post([]);
        self::assertTrue($re === ERROR_EMPTY_CATEGORY_OR_ID, 'Expect Error: ERROR_EMPTY_CATEGORY_OR_ID');
    }

    public function testCreate() {
        $re = api_edit_post(['category' => 'uncategorized', 'post_title' => 'title'] );
        self::assertTrue($re['ID'] > 0, 'Success: create');
    }
    public function testEdit() {
        $re = api_edit_post(['category' => 'uncategorized', 'post_title' => 'title', 'post_content' => 'content'] );
        self::assertTrue($re['ID'] > 0, 'Success: create');
        self::assertTrue($re['post_title'] == 'title', 'Success: title');
        self::assertTrue($re['post_content'] == 'content', 'Success: content');
        self::assertTrue($re['category'] == 'uncategorized', 'Expect success: Category == uncategorized');



        $edited = api_edit_post(['ID' => $re['ID']]);
        self::assertTrue(api_error($edited) === false, "Expect edit success: ");
        self::assertTrue($edited['ID'] === $re['ID'], "ID check: $edited[ID] === $re[ID]");
        self::assertTrue($edited['post_title'] == 'title', 'Success: title');
        self::assertTrue($edited['post_content'] == 'content', 'Success: content');



        self::assertTrue($edited['category'] == $re['category'], 'Category');

    }
}



