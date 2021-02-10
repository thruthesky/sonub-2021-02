<?php
$category = get_category_by_slug(in('category'));
?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List - <?= $category->slug ?></h2>
    <a class="btn btn-success" href="/?page=forum/edit&category=<?= $category->slug ?>">Create</a>
</div>

<hr>
