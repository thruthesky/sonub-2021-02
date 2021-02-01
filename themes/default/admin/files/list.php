<?php

$category = in('category', 'qna');
$q = ['category_name' => $category, 'post_type' => 'post'];

print_r($q);
$posts = forum_search($q);

$categories = get_categories();

// d($categories);

?>


<h1>Files: <?= $category ?></h1>


<hr>
<div>
    <?php foreach ($categories as $category) { ?>
        <a href="/?page=admin/files/list&category=<?= $category->slug ?>"><?= $category->name ?></a> |
    <?php } ?>
</div>
<div class="container mt-3">
    <div class="row">
        <?php foreach ($posts as $post) { ?>
            <?php foreach ($post['files'] as $file) { ?>
                <div class="col-3 position-relative border">
                    <i class="fa fa-trash red fs-sm me-3 pointer" @click="deleteFile(<?= $file['ID'] ?>)"></i>
                    <a href="<?= $post['url'] ?>">
                        <i class="fa fa-external-link-alt green fs-sm"></i>
                    </a>
                    <img class="w-100" src="<?= $file['url'] ?>" />
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>


<script>
    const mixin = {
        data() {
            return {}
        },
        methods: {
            deleteFile(ID) {
                app.onFileDelete(ID, refresh);
            },
        }
    }
</script>