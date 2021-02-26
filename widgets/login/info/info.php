<?php
$o = get_widget_options();
$profile = profile();
?>
<div class="box mb-2 fs-sm <?=$o['class']??''?>">
    <div>어서오세요, <?=$profile['name'] ?? ''?>님</div>
</div>
