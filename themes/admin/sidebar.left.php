


<section class="l-sidebar d-none d-md-block mt-3 of-hidden">
    <?php
    if (in('user_ID')) include script('admin/user/sidebar-user-info');

    $p = script('admin/'.script_folder_name().'/sidebar', 'admin/sidebar');
    include $p;
    ?>

</section>
