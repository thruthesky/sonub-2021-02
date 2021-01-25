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
    <link href="<?=THEME_URL?>/css/fontawesome-pro-5.15.2-web/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=THEME_URL?>/css/index.css?v=<?=build_version()?>">
    <?php load_theme_css($script); ?>
    <?php live_reload_js() ?>
</head>
<body>
<section id="app" class="container">
    <h1>WiGo Theme</h1>
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

        | <a href="/?page=user/settings">Settings</a>

        <span v-if="isAdmin()">
            | <a href="/?page=admin/index">Admin</a>
        </span>
    </div>

    <section id="router">
        <?php
            begin_capture_script_style();
            include $script;
            end_capture_script_style();
        ?>
    </section>
    <div class="modal" :class="{ 'd-block': modal.active }" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{modal.title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="hideModal()">Close</button>
                    <button type="button" class="btn btn-primary" @click="hideModal(true)">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</section>

<? insert_extracted_styles_from_script() ?>


<script>

    addEventListener('pushNotification', function(){
        console.log('hi');
    });


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
<script src="<?php echo THEME_URL . '/js/helpers.js'?>?v=<?=build_version()?>"></script>
<script src="<?php echo THEME_URL . '/js/app.forum.js'?>?v=<?=build_version()?>"></script>
<script src="<?php echo THEME_URL . '/js/app.js'?>?v=<?=build_version()?>"></script>
<script>
    request('app.version', {}, function (x) {
        console.log('version: ', x);
    }, function(e) {
        console.log('error: ', e);
    });


</script>

</body>
</html>