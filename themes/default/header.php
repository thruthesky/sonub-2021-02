<?php

if ( is_admin_page() ) {
    include 'admin/admin.menu.php';
} else {
    include 'menu.php';
}
