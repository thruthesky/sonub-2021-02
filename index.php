<?php

global $config;
include_once('load.php');

function get_theme_script($theme) {
    if ( isset($_REQUEST['page']) ) {
        $script = THEME_DIR . "/themes/$theme/$_REQUEST[page].php";
    } else {
        $_uri = $_SERVER['REQUEST_URI'];
        if ( empty($_uri) || $_uri == '/' ) $script = THEME_DIR . "/themes/$theme/home.php";
        else $script = THEME_DIR . "/themes/$theme/forum/view.php";
    }

    return $script;
}

function get_error_script($title, $content) {
    global $config;
    $config->error_title = $title;
    $config->error_content = $content;
    return THEME_DIR . "/themes/default/error.php";
}


$script = get_theme_script($config->theme);



if ( !file_exists($script) ) {
    $script = get_theme_script('default');
}

if ( !file_exists($script) ) {
    $script = get_error_script('File not found', 'The file you are referring does not exists on server');
}

?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/wp-content/themes/wigo/css/index.css">
    <?php live_reload_js() ?>
</head>
<body>

<section id="layout">
    <h1>WiGo</h1>

    <div>
        Menu:
        <a href="/">Home</a> |

        <span v-if="notLoggedIn()">
        <a href="/?page=user/register">Register page</a> |
        <a href="/?page=user/login">Login page</a> |
        </span>

        <span v-if="loggedIn()">
        <a href="/?page=user/profile">Profile page</a> |
        <a href="/?page=user/logout">Logout</a> |
        </span>

        <a href="/?page=forum/list&category=reminder">Reminder</a> |
        <a href="/?page=forum/list&category=qna">QnA</a> |
        <a href="/?page=forum/list&category=discussion">Discussion</a>

        <span v-if="isAdmin()">
            | <a href="/?page=admin/index">Admin</a>
        </span>
    </div>


    <ul>
        <li>Done Install Bootstrap 4</li>
        <li>Done Vue.js 3 https://v3.vuejs.org/guide/introduction.html#what-is-vue-js</li>
        <li>Node SASS</li>
        <li>Create <a href="/?page=user/register">Register page</a>, Login Page.</li>
        <li>Create Forum.</li>
        <li>
            Goal:
            Travel Diary.

            User can run the app and start filming(or photo shotting) or capturing scense with the phone.
            Every place when the user moves, he can open the app and take photo or memo.
            And in the end of the jurney, the app will display nice diary. And it can be shared to the public.
            The user can share travel information.

        </li>
    </ul>

    <section id="router">
        <?php
        include $script;
        ?>
    </section>
</section>

<script>
    const config = {
        apiUrl: "https://local.nalia.kr/v3/index.php",
        firebaseConfig: {
            apiKey: "AIzaSyBqOcOhdonMMimHAt7Iq4aodp2KwQBc61M",
            authDomain: "nalia-app.firebaseapp.com",
            projectId: "nalia-app",
            storageBucket: "nalia-app.appspot.com",
            messagingSenderId: "973770265003",
            appId: "1:973770265003:web:dd304f98a421a733d8c2ee"
        },
        allTopic: "allTopic"
    };
</script>
<script src="https://unpkg.com/vue@3.0.5/dist/vue.global.prod.js"></script>
<script src="/wp-content/themes/wigo/js/axios.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js"></script>
<script src="/wp-content/themes/wigo/js/firebase.js"></script>
<script src="<?php echo THEME_URL . '/js/app.js'?>"></script>
<script>
    request('app.version', {}, function (x) {
        console.log('version: ', x);
    }, function(e) {
        console.log('error: ', e);
    });
</script>


</body>
</html>