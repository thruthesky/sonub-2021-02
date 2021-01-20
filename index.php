<?php
if ( isset($_REQUEST['page']) ) {
    $script = "./wp-content/themes/wigo/$_REQUEST[page].php";
} else {
    $script = "./wp-content/themes/wigo/main.php";
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
        <a href="/?page=user/register">Register page</a> |
        <a href="/?page=user/login">Login page</a> |
        <a href="/?page=user/profile">Profile page</a> |
        <a href="/?page=user/logout">Logout</a> |
        <a href="/?page=forum/list&category=reminder">Reminder</a> |
        <a href="/?page=forum/list&category=qna">QnA</a> |
        <a href="/?page=forum/list&category=discussion">Discussion</a>
    </div>
    {{ message }}
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
        console.log('data', data);
        axios.post(apiUrl, data).then(function (res) {
            if ( res.data.code !== 0 ) {
                if ( typeof errorCallback === 'function' ) {
                    errorCallback(res.data.code);
                }
            } else {
                successCallback(res);
            }
        }).catch(errorCallback);
    }
    const AttributeBinding = {
        data() {
            return {
                message: 'You loaded this page on ' + new Date().toLocaleString(),
                register: {},
                login: {},
                user: {},
            }
        },
        methods: {
            onRegisterFormSubmit() {
                console.log('register form submitted');
                console.log(this.$data.register);
                const _this = this;
                request('user.register', _this.$data.register, function(re) {
                    Object.assign(_this.$data.user, re['data']);
                    console.log('this.$data.user: ', _this.$data.user);
                }, function(errcode) {
                    console.log("ERROR CODE: ", errcode);
                });

            },
            onLoginFormSubmit() {
                const _this = this;
                request('user.login', _this.$data.login, function(re) {
                    Object.assign(_this.user, re['data']);
                    console.log('this.$data.user: ', _this.$data.user);
                }, function(errcode) {
                    console.log("ERROR CODE: ", errcode);
                });
            }

        }
    };
    Vue.createApp(AttributeBinding).mount('#layout');
</script>
</body>
</html>