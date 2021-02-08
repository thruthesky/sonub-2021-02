<?php

$wo = get_widget_options();



$o = get_option($wo['id']);

if ( !$o ) {
    $o = [
        'widget_title' => $wo['id'],
        'path' => 'dynamic/default',
    ];
}
$o['id'] = $wo['id'];

include widget($o['path'], $o);

if ( in('update_widget') == $o['id'] ) {
    include widget_config($o['path'], $o);
}