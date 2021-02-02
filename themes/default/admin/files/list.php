<?php

// $category = in('category', '');
// $q = ['category_name' => $category];

// print_r($q);
$posts = get_files([]);

// $categories = get_categories();

// d($categories);
// d($posts);

?>


<h1>Uploaded Files:</h1>


<hr>
<!-- <div>
    <?php foreach ($categories as $category) { ?>
        <a href="/?page=admin/files/list&category=<?= $category->slug ?>"><?= $category->name ?></a> |
    <?php } ?>
</div> -->
<div class="container mt-3">
    <div class="row">
        <?php foreach ($posts as $post) {
            // print_r($post);
        ?>
            <div class="col-3 position-relative border">
                <i class="fa fa-trash red fs-sm me-3 pointer" @click="deleteFile(<?= $post['ID'] ?>)"></i>
                    <i class="fa fa-external-link-alt green fs-sm"></i>
                </a>
                <img class="w-100" src="<?= REQUESTED_HOME_URL . '/wp-content/uploads/' . $post['_wp_attached_file'] ?>" />
            </div>
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