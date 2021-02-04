<?php

if ( ! admin() ) {
    jsBack('You are not an admin!');
}


$re = wp_insert_category( ['cat_name'=> in('cat_name'), 'category_description'=> in('cat_name') ], true );
if ( is_wp_error($re) ) {
    print_r($re->get_error_message());
}
else {
    jsGo("/?page=admin/forum/list");
}