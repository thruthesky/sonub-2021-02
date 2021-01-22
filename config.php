<?php

$config = new stdClass();

define('_THEMES', [
    'apple' => 'apple',
    'banana' => 'banana',
]);

$_host = get_host_name();
$config->theme = 'default';
foreach( _THEMES as $_domain => $_theme ) {
    if ( stripos($_host, $_domain ) !== false ) {
        $config->theme = $_theme;
    }
}
