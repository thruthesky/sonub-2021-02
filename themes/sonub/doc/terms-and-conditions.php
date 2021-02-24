<?php

$str = file_get_contents(THEME_DIR . '/themes/sonub/terms-and-conditions.txt');

$str = str_replace("\n", "<br>", $str);
echo $str;