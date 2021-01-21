<?php
include_once('load.php');

if ( isset($_REQUEST['page']) ) {
    $script = "./wp-content/themes/wigo/themes/default/$_REQUEST[page].php";
} else {
    $script = "./wp-content/themes/wigo/themes/default/main.php";
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/wp-content/themes/wigo/css/index.css">
    <!-- <?php live_reload_js() ?> -->
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
    </div>

    <ul>
        <li>Done Install Bootstrap 4</li>
        <li>Done Vue.js 3 https://v3.vuejs.org/guide/introduction.html#what-is-vue-js</li>
        <li>Node SASS</li>
        <li>Create <a href="/?page=user/register">Register page</a>, Login Page.</li>
        <li>Create Forum.</li>
    </ul>

    <section id="router">
        <?php
        include $script;
        ?>
    </section>
</section>

<script src="https://unpkg.com/vue@3.0.5/dist/vue.global.prod.js"></script>
<script src="/wp-content/themes/wigo/js/axios.min.js"></script>

<script>
    const apiUrl = "https://local.nalia.kr/v3/index.php";
    function request(route, data, successCallback, errorCallback) {
        data = Object.assign({}, data, {route: route});
        if (this.loggedIn) {
            data['session_id'] = this.$data.user.session_id;
        }
        console.log('data', data);
        axios.post(apiUrl, data).then(function (res) {
            if ( res.data.code !== 0 ) {
                if ( typeof errorCallback === 'function' ) {
                    errorCallback(res.data.code);
                }
            } else {
                successCallback(res.data.data);
            }
        }).catch(errorCallback);
    }

    post = {};

    const AttributeBinding = {
        data() {
            return {
                register: {},
                login: {},
                post: post ?? {},
                user: null,
                // loggedIn: user.session_id,
                // notLoggedIn: !this.$data.user.loggedIn,
            }
        },
        created() {
            console.log('created!');
            this.getUser();

//            // add event listener to subscribe and send subscription to server
//            self.addEventListener('activate', this.pnSubscribe);
//            // and listen to incomming push notifications
//            self.addEventListener('push', this.pnPopupNotification);
//            // ... and listen to the click
//            self.addEventListener('notificationclick', this.pnNotificationClick);
        },
        mounted() {
            console.log('mounted!');
        },
        methods: {
            loggedIn() {
                return this.$data.user !== null && this.$data.user.session_id;
            },
            notLoggedIn() {
                return ! this.loggedIn();
            },
            onRegisterFormSubmit() {
                console.log('register form submitted');
                console.log(this.$data.register);
                request('user.register', vm.$data.register, this.setUser, this.error);
            },
            onLoginFormSubmit() {
                request('user.login', vm.$data.login, this.setUser, this.error);
            },
            logout() {
                localStorage.removeItem('user');
                this.$data.user = null;
            },
            error(e) {
                console.log('e');
                alert(e);
            },
            setUser(profile) {
                this.set('user', profile);
                this.$data.user = profile;
            },
            getUser() {
                this.$data.user = this.get('user');
                return this.$data.user;
            },
            set(name, value) {
                value = JSON.stringify(value);
                localStorage.setItem(name, value);
            },
            get(name) {
                const val = localStorage.getItem(name);
                if ( val ) {
                    return JSON.parse(val);
                } else {
                    return val;
                }
            },
            alert(title, body) {
                alert(title + "\n" + body);
            },
            saveToken(token) {
                console.log('token::\n', token);
                request('notification.updateToken', { token: token }, function (re) {
                    console.log(re);
                }, this.error);
            },
            
            /// Forum
            onPostEditFormSubmit() {
                var category = <?php echo "'" . $category . "'";  ?>;
                if (this.$data.post.category == '') this.error('Category empty');
                this.$data.post.category = category;

                this.$data.post.session_id = this.$data.user.session_id;
                console.log(this.$data.post);

                request('forum.editPost', vm.$data.post, function(post) {
                    console.log('post created', post);
                    window.location.href = "/?page=forum/list&category=" + category; 
                }, this.error);
            },
        }
    };
    const vm = Vue.createApp(AttributeBinding).mount('#layout');
</script>

<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js"></script>
<script src="/wp-content/themes/wigo/js/firebase.js"></script>

</body>
</html>