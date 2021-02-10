
<section class="l-sidebar d-none d-md-block mt-3 of-hidden">
    <?php
    if ( is_admin_user_page() ) {
        include 'sidebar.user.php';
    } else if ( is_admin_forum_page() ) {
        include 'sidebar.forum.php';
    } else {
        include 'sidebar.home.php';
    }
    ?>
</section>

<style>
    .l-sidebar {
        width: 360px !important;
        min-width: 360px !important;
        max-width: 360px !important;
    }
    .l-sidebar::after {
        content: '';
        display: block;
        width: 360px !important;
        min-width: 360px !important;
        max-width: 360px !important;
    }
</style>