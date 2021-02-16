<?php

if ( is_in_admin_page() ) {
    include 'admin/admin.menu.php';
} else {
    include 'menu.php';
}
