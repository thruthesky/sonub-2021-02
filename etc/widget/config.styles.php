<?php

$o['fontColor'] = $o['fontColor'] ?? 'black';
$o['fontSize'] = ($o['fontSize'] ?? '14') . 'px';
$o['borderWidth'] = ($o['borderWidth'] ?? '1') . 'px';
$o['borderColor'] = $o['borderColor'] ?? '#dae1e6';
$o['borderRadius'] = ($o['borderRadius'] ?? '16') . 'px';
$o['backgroundColor'] = $o['backgroundColor'] ?? '#f7f9fa';


?>
<style>
    .<?=$o['widget_id']?> * {
        color: <?=$o['fontColor']?> !important;
        font-size: <?=$o['fontSize']?> !important;
    }
    .<?=$o['widget_id']?> {
        border: <?=$o['borderWidth']?> solid <?=$o['borderColor']?> !important;
        border-radius: <?=$o['borderRadius']?>;
        background-color: <?=$o['backgroundColor']?>;
    }
</style>
