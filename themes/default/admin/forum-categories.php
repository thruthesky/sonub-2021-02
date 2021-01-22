<?php

$categories = get_categories();
// print_r($categories);
?>

<hr>
<h1>FORUM CATEGORIES</h1>


<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Slug</th>
            <th scope="col">Name:</th>
            <th scope="col">Description</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($categories as $category) {
            // print_r($category);
        ?>
            <tr>
                <td><?php echo $category->cat_ID ?></td>
                <td><?php echo $category->slug ?> </td>
                <td><?php echo $category->name ?></td>
                <td><?php echo $category->description ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>