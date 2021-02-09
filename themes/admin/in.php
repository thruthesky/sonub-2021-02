<?php


$_path = THEME_DIR . '/themes/' . get_domain_theme(false) . '/' . str_replace('.', '/', in('script')) . '.php';

include $_path;