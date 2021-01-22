<?php

$categories = get_categories();
// print_r($categories);
?>

<hr>
<h1>FORUM CATEGORIES</h1>

<section class="p-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Slug</th>
                <th scope="col">Name:</th>
                <th scope="col">Description</th>
                <th scope="col">List on post view</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($categories as $category) {
                $category->list_on_view = get_term_meta($category->cat_ID, 'list_on_view', true);
                print_r($category);
            ?>
                <tr>
                    <td><?php echo $category->cat_ID ?></td>
                    <td><?php echo $category->slug ?> </td>
                    <td><?php echo $category->name ?></td>
                    <td><?php echo $category->description ?></td>
                    <td><input type="checkbox" <?php if ($category->list_on_view) echo 'checked'?>></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>