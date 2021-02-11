<?php
$posts = get_files(['posts_per_page' => 40]);
?>

<h1>사진</h1>

<hr>
<div class="container mt-3">
    <div class="row">
        <?php foreach ($posts as $post) {
        ?>
            <div class="col-3 mt-3">
                <i class="fa fa-trash red fs-sm me-3 pointer" @click="deleteFile(<?= $post['ID'] ?>)"></i>
                <? if ( $post['post_parent'] ?? false ) {
                    $parent = post_response($post['post_parent']);
                    ?>
                <a href="<?=$parent['url']?>">
                <i class="fa fa-external-link-alt green fs-sm"></i>
                </a>
                <? } ?>

                <img class="w-100" src="<?= $post['thumbnail_url'] ?>" />
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