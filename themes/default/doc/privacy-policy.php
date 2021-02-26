<?php

$str = file_get_contents(THEME_DIR . '/etc/docs/privacy-policy.txt');

$str = str_replace("\n", "<br>", $str);
echo $str;

