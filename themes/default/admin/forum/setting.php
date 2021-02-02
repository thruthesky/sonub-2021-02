<?php

// $cat = get_category_settings(['slug' => in('slug')]);

$cat = get_category_by_slug(in('slug'));
// $metas = category_meta($cat->ID, 'list_on_view', true);

// d($cat);
// d($metas);


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
                select_list_widgets($cat->ID, 'forum-list', 'forum_list_widget');
                ?>
            </td>
        </tr>



        <tr>
            <td>Pagination Widget</td>
            <td>
                <?
                select_list_widgets($cat->ID, 'forum-list-pagination', 'forum_list_pagination_widget');
                ?>
            </td>
        </tr>




        <tr>
            <td>Post list under view page</td>
            <td>
                <input 
                    type="checkbox" 
                    name="list_on_view"
                    <?php if (category_meta($cat->cat_ID, 'list_on_view', 'N') == 'Y' ) echo 'checked' ?>>
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

<ul>
    <li>
        Post list under view page - is enabled if the box is checked.
    </li>
</ul>

<script>
    const mixin = {

        created() {

        },
        methods: {
            updateCategorySettings(name) {
                console.log('name', name);
                const dom = document.querySelector("[name='"+name+"']");
                let value;

                if ( name === 'list_on_view' ) {
                    if ( dom.checked ) value = 'Y';
                    else value = 'N';
                } else {
                    value = dom.value;
                }
                console.log('v: ', value);
                const data = {
                    'cat_ID': <?= $cat->term_id ?>,
                    'name': name,
                    'value': value
                };
                request('forum.updateCategory', data, function(setting) {
                    console.log("settings updated: ", setting);
                }, app.error);
            },

        }
    }
</script>