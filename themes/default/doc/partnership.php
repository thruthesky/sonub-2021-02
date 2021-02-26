<?php

$str = file_get_contents(THEME_DIR . '/etc/docs/partnership.txt');

$str = str_replace("\n", "<br>", $str);
echo $str;

