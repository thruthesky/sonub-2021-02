<?php

$category = '';
$post = null;


if (isset($_REQUEST['category'])) {
    $category = $_REQUEST['category'];
} else {
    $post = post_response($_REQUEST['ID']);
    $post_ID = $post['ID'];
    $post_title = $post['post_title'];
    $post_content = $post['post_content'];
    $category = get_the_category($post_ID)[0]->slug;
}

?>

<h1> POST EDIT : <?php echo $category ?></h1>

<!-- TODO: Error on content when it contains <br/>-->
<post-edit-form 
    :category="'<?=$category?>'"
    :post_id="<?=$post_ID?>"
    :post_title='"<?=htmlentities2(str_replace('"', "'", $post_title))?>"'
    :post_content='"<?=htmlentities2(str_replace('"', "'", $post_content))?>"'
    :files='<?=json_encode($post['files'])?>'
    >
</post-edit-form>
