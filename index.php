<?php
/**
 * @file index.php
 */


$script = get_theme_page_script_path();
?>
<!doctype html>
<html>
<head>

    <link href="<?=THEME_URL?>/css/bootstrap-5.0.0-b1-min.css" rel="stylesheet">
    <link href="<?=THEME_URL?>/css/fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=THEME_URL?>/css/index.css?v=<?=build_version()?>">
    <?php load_theme_css($script); ?>
    <?php live_reload_js() ?>
    <?php insert_initial_javascript() ?>
</head>
<body class="<?=get_theme_page_class_name()?>">
<section id="app" class="container">
    <h1>Sonub</h1>
    <div class="d-flex justify-content-between">
        <div>

            <a href="/">Home</a>
            <span v-if="notLoggedIn()">
            <a href="/?page=user/register">Register page</a>
            <a href="/?page=user/login">Login page</a>
            </span>
                <span v-if="loggedIn()">
            <a href="/?page=user/profile">Profile page</a>
            <a href="/?page=user/logout">Logout</a>
            </span>
            <a href="/?page=forum/list&category=reminder">Reminder</a>
            <a href="/?page=forum/list&category=qna">QnA</a>
            <a href="/?page=forum/list&category=discussion">Discussion</a>

        </div>
        <div>
            <a href="/?page=admin/index" v-if="isAdmin()">Admin</a>
            <a class="ms-2" href="/?page=user/settings" v-if="loggedIn()"><i class="fa fa-cog"></i></a>
            <a href="/?page=user/profile"><img class="size-40 circle" :src="user.profile_photo_url" v-if="user && user.profile_photo_url !== 'undefined'"></a>
        </div>

    </div>

    <section id="router">
        <?php
        begin_capture_script_style();
        include $script;
        end_capture_script_style();
        ?>
    </section>
</section>

<? insert_extracted_styles_from_script() ?>


<script>

    addEventListener('pushNotification', function(){
        console.log('hi');
    });


    const config = {
        apiUrl: "<?=API_URL?>",
        themeFolderName: "<?=THEME_FOLDER_NAME?>",
        firebaseConfig: {
            apiKey: "AIzaSyBqOcOhdonMMimHAt7Iq4aodp2KwQBc61M",
            authDomain: "nalia-app.firebaseapp.com",
            projectId: "nalia-app",
            storageBucket: "nalia-app.appspot.com",
            messagingSenderId: "973770265003",
            appId: "1:973770265003:web:dd304f98a421a733d8c2ee"
        },
        defaultTopic: "<?=DEFAULT_TOPIC?>",
        post_notification_prefix: '<?=NOTIFY_POST?>',
        comment_notification_prefix: '<?=NOTIFY_COMMENT?>',
        cookie_domain: '<?=get_cookie_domain()?>',
    };
</script>
<script src="<?=THEME_URL?>/js/bootstrap-5.0.0-b1-min.js"></script>
<script src="<?=THEME_URL?>/js/vue.3.0.5-min.js"></script>
<script src="<?=THEME_URL?>/js/axios.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js"></script>
<script src="<?=THEME_URL?>/js/firebase.js"></script>
<?php load_theme_js($script); ?>
<script src="<?php echo THEME_URL . '/js/helpers.js'?>?v=<?=build_version()?>"></script>
<? if ( is_forum_page() ) { ?><script src="<?php echo THEME_URL . '/js/app.forum.js'?>?v=<?=build_version()?>"></script><? } ?>
<script src="<?php echo THEME_URL . '/js/app.js'?>?v=<?=build_version()?>"></script>


</body>
</html>