<?php

$str = file_get_contents(THEME_DIR . '/etc/docs/withcenter-company-info.txt');

$str = str_replace("\n", "<br>", $str);
echo $str;

