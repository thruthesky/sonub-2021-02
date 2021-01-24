<?php
/**
 * @file index.php
 */



$script = get_theme_script();

?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/wp-content/themes/wigo/css/index.css">
    <?php load_theme_css($script); ?>
    <?php live_reload_js() ?>
</head>
<body>
<section id="app" class="container">
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
    <section id="router">
        <?php
            begin_capture_style();
            include $script;
            end_capture_style();
        ?>
    </section>
</section>

<? insert_extracted_styles_from_script() ?>


<script>
    const config = {
        apiUrl: "<?=API_URL?>",
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
<?php load_theme_js($script); ?>
<script src="<?php echo THEME_URL . '/js/app.js'?>?v=1"></script>
<script>
    request('app.version', {}, function (x) {
        console.log('version: ', x);
    }, function(e) {
        console.log('error: ', e);
    });
</script>


</body>
</html>