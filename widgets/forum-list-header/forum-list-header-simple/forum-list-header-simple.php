<?php
$o = get_widget_options();
?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2><?= $o['category']->cat_name ?></h2>
    <div>
        <a class="btn btn-success" href="/?page=forum/edit&category=<?= $o['category']->slug ?>">Create</a>
    </div>
</div>
<hr>
