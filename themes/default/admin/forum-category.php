<?php

$cat = get_category_by_slug(in('slug'));
$catMeta = get_term_meta($cat->term_id);

$postListOnView = $catMeta['list_on_view'] ? $catMeta['list_on_view'][0] : false;
$postPerPage = $catMeta['posts_per_page'] ? $catMeta['posts_per_page'][0] : 20;

// d($cat);
?>
<h1><?= in('slug') ?> Settings</h1>


<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Options</th>
            <th scope="col">Values</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Title</td>
            <td><input name="name" value="<?= $cat->name ?>" @keyup="debounce(updateTitle($event), 500, 'name')"></td>
        </tr>
        <tr>
            <td>Post list under view page</td>
            <td><input type="checkbox" @change="debounce(updateListOnView($event), 500, 'no')" <?php if ($postListOnView) echo 'checked' ?>></td>
        </tr>
        <tr>
            <td>No of posts per page</td>
            <td><input type="number" value="<?= $postPerPage ?>" @keyup="debounce(updatePostsPerPage($event), 500, 'no')"></td>
        </tr>
        <tr>
            <td>No of pages on navigator</td>
            <td><input type="text"></td>
        </tr>
    </tbody>
</table>

<script>
    const mixin = {

        created() {

        },
        methods: {
            updateListOnView(event) {
                this.updateCategory('list_on_view', event.target.checked);
            },
            updateTitle(event) {
                this.updateCategory('cat_name', event.target.value);
            },
            updatePostsPerPage(event) {
                this.updateCategory('posts_per_page', event.target.value);
            },
            updateCategory(key, value) {
                data = {
                    'cat_ID': <?= $cat->term_id ?>,
                    'name': key,
                    'value': value
                };
                console.log(data);
                request('forum.updateCategory', data, function(data) {
                    console.log('category updated!');
                }, app.error);
            }
        }
    }
</script>