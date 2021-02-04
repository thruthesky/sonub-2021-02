<?php

// $cat = get_category_settings(['slug' => in('slug')]);

$cat = get_category_by_slug(in('slug'));
// $metas = get_term_meta($cat->term_id);
//
//// d($cat);
// d($metas);

$root_categories = get_root_categories();


?>
<h1><?= in('slug') ?> Settings</h1>


<form @submit.prevent="onForumSettingFormSubmit($event)">
<input type="hidden" name="cat_ID" value="<?=$cat->cat_ID?>">
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Options</th>
            <th scope="col">Values</th>
        </tr>
    </thead>
    <tbody>

    <tr>
        <td>Parent Category</td>
        <td>
            <select name="category_parent">
                <option value="0">None</option>
                <? foreach( $root_categories as $_category ) {
                    if ( $_category->term_id == $cat->term_id ) continue;
                    ?>
                    <option value="<?=$_category->term_id?>" <? if ($_category->term_id == $cat->category_parent) echo "selected"?>><?=$_category->cat_name?></option>
                <? } ?>
            </select>
        </td>
    </tr>


    <tr>
            <td>Title</td>
            <td>
                <input 
                    name="cat_name"
                    value="<?= $cat->cat_name ?>">
            </td>
        </tr>

        <tr>
            <td>Description</td>
            <td>
                <input
                        name="category_description"
                        value="<?= $cat->category_description ?>">
            </td>
        </tr>

        <tr>
            <td>List Widget</td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'forum-list', 'forum_list_widget');
                ?>
            </td>
        </tr>



        <tr>
            <td>Pagination Widget</td>
            <td>
                <?
                select_list_widgets($cat->term_id, 'pagination', 'pagination_widget');
                ?>
            </td>
        </tr>




        <tr>
            <td>Post list under view page</td>
            <td>
                <label>
                    <input
                            type="radio"
                            name="list_on_view"
                            value="Y"
                        <?php if (category_meta($cat->cat_ID, 'list_on_view', '') == 'Y' ) echo 'checked' ?>> Yes,
                </label>
                &nbsp;
                <label>
                    <input
                            type="radio"
                            name="list_on_view"
                            value="N"
                        <?php if (category_meta($cat->cat_ID, 'list_on_view', '') != 'Y' ) echo 'checked' ?>> No
                </label>
            </td>
        </tr>
        <tr>
            <td>No of posts per page</td>
            <td>
                <input
                        name="posts_per_page"
                    type="text"
                    value="<?=category_meta($cat->cat_ID, 'posts_per_page', POSTS_PER_PAGE)?>">
            </td>
        </tr>
        <tr>
            <td>No of pages on navigator</td>
            <td>
                <input
                        name="no_of_pages_on_nav"
                    type="text"
                    value="<?=category_meta($cat->cat_ID, 'no_of_pages_on_nav', NO_OF_PAGES_ON_NAV)?>">
            </td>
        </tr>


        <tr>
            <td></td>
            <td>
                <button type="submit">Submit</button>
            </td>
        </tr>
    </tbody>
</table>


</form>

<ul>
    <li>
        Post list under view page - is enabled if the box is checked.
    </li>
</ul>

<script>
    const mixin = {
        methods: {
            onForumSettingFormSubmit(event) {
                console.log('form data: ', getFormData(event));
                request('forum.updateCategory', getFormData(event), function(setting) {
                    console.log("settings updated: ", setting);
                }, app.error);
            }
        }
    }
</script>