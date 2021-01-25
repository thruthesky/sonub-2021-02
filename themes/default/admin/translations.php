<?php



$trans = api_get_translations(['format' => 'language-first']);

$languages = $trans['languages'];
$translations = $trans['translations'];

print_r($languages);
print_r($translations);

?>


<hr>
<h1>TRANSLATIONS PAGE</h1>

<section class="p-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Code</th>
                <?php foreach ($languages as $ln) { ?>
                    <th scope="col"><?php echo $ln ?></th>
                <?php } ?>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <!-- <tbody>
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
                    <td><input type="checkbox" <?php if ($category->list_on_view) echo 'checked' ?>></td>
                </tr>
            <?php } ?>
        </tbody> -->
    </table>
</section>