<?php

$category = in('category', 'qna');
$q = ['category_name' => $category, 'post_type' => 'attachment'];

print_r($q);
$files = forum_search($q);

$categories = get_categories();

// d($categories);

?>

<h1>Files: <?=$category?></h1>

<div>
<?php foreach ($categories as $category) { ?>
    <a href="/?page=admin/files/list&category=<?= $category->slug ?>"><?=$category->name?></a> |
<?php } ?>
</div>

<?php d($files) ?>