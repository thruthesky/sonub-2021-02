<?php

$o = get_widget_options();
$dwo = get_dynamic_widget_options($o['id']);
if ( ! $dwo ) {
    $dwo['path'] = 'dynamic/default';
}

$dwo['id'] = $o['id'];


include widget($dwo['path'], $dwo);

if ( in('update_widget') == $o['id'] ) {
    include widget_config($dwo['path'], $dwo);
}