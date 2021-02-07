<?php

$wo = get_widget_options();



$o = get_option($wo['id']);

if ( !$o ) {
    $o = [
        'widget_title' => $wo['id'],
        'path' => 'dynamic/default',
    ];
}

include widget($o['path'], $o);
