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
                <th scope="col">No.</th>
                <th scope="col">Category ID</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">View List</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($categories as $category) {
                $category->list_on_view = get_term_meta($category->cat_ID, 'list_on_view', true);
//                print_r($category);
            ?>
                <tr>
                    <td><?php echo $category->cat_ID ?></td>
                    <td><a href="?page=admin/forum-category&slug=<?php echo $category->slug ?>"><?php echo $category->slug ?></a></td>
                    <td><?php echo $category->name ?></td>
                    <td><?php echo $category->description ?></td>
                    <td><?=$category->list_on_view ? '/' : 'X'?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>


<ul>
    <li>For the detail settings, click category ID.</li>
    <li>
        View List - is the option for listing posts under view page.
    </li>
</ul>