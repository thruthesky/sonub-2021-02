<?php

$o = get_widget_options();
$dwo = get_dynamic_widget_options($o['id']);
if ( ! $dwo ) {
    $dwo['path'] = 'dynamic/default';
}

$dwo['id'] = $o['id'];
if ( !isset($dwo['widget_type']) ) $dwo['widget_type'] = '';


?>
<?php if ( is_cafe_admin() ) { ?>
    <div  id="<?=$o['id']?>" class="widget-wrapper position-relative">
<?php } ?>
<?php
include widget($dwo['path'], $dwo);
?>
<?php if ( is_cafe_admin() ) { ?>

    <div class="position-absolute bottom right" style="z-index: 12345; padding: .5em;">
        <?=update_widget_icon($o['id'])?>
    </div>
    </div>
<?php } ?>
<?php
if ( in('update_widget') == $o['id'] ) {
    include THEME_DIR . '/etc/widget/config.head.php';
    include widget_config($dwo['path'], $dwo);
    include THEME_DIR . '/etc/widget/config.tail.php';
}