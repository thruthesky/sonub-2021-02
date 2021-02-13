<?php
$o = get_widget_options();
$profile = profile();
?>
<div class="box mb-2 <?=$o['class']??''?>">
    어서오세요, <?=$profile['name'] ?? ''?>
</div>
