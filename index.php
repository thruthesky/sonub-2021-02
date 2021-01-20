<?php
if ( trim($_SERVER['REQUEST_URI']) != ''  && trim($_SERVER['REQUEST_URI']) != '/' ) {
    $script = "./wp-content/themes/wigo$_SERVER[REQUEST_URI].php";
} else {
    $script = "./wp-content/themes/wigo/main.php";
}
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/wp-content/themes/wigo/index.css">
</head>
<body>

<section id="layout">
<h1>WiGo</h1>
    <div>
        Menu:
        <a href="/">Home</a> |
        <a href="/user/register">Register page</a> |
        <a href="/user/login">Login page</a>
    </div>
    {{ message }}
<ul>
    <li>Done Install Bootstrap 4</li>
    <li>Done Vue.js 3 https://v3.vuejs.org/guide/introduction.html#what-is-vue-js</li>
    <li>Node SASS</li>
    <li>Create <a href="/user/register">Register page</a>, Login Page.</li>
    <li>Create Forum.</li>
</ul>

    <section id="router">
        <?php
        include $script;
        ?>
    </section>
</section>

<script src="https://unpkg.com/vue@next"></script>

<script>
    const AttributeBinding = {
        data() {
            return {
                message: 'You loaded this page on ' + new Date().toLocaleString();
            }
        }

        methods: {
            onRegisterFormSubmit() {
                console.log('register form submitted');
            }
        }
    }
    Vue.createApp(AttributeBinding).mount('#layout');
</script>
</body>
</html>