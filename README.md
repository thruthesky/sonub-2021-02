# Sonub Theme API

* Sonub(Sonub Network Hub) is an open source, complete CMS with modern functionalities like realtime update, push notification, and more.
* It is build on Apache(or Nginx) + MySQL(or MariaDB) + PHP stack based Wordpress. It works as a theme but has very much fixed.
* The reason why we have chosen as its backend frame is because 1) It's easy. Team members can easily learn it. 2) It's almost a standard CMS and widely used all over the world.






# Overview

* Build with PHP.
  * Main reason is to support SEO naturally.\
    When you build web as SPA, there might be several ways for supporting SEO like SSR or half PHP and haf SPA.\
    But none of them are natural and takes extra effort.
  * Vanilla Vue.js 3.x
    * It uses Vue.js over jQuery and it does not use CLI bundling tools, simply to avoid extra compiling and publishing.
  * It may be a good choice to do SPA for sites(like admin site) that does not need SEO.

* Firebase
  * There are lots of benefits with Firebase.
    * With firebase, you can do Social login, push notification, realtime updates.
  * And yes, you may use the free version only.

* Supporting Full Restful API.
  * Sonub is built with Restful API in mind and all functionalities are supported as Restful API.
  * So, any client like Vue, Angular, React, Flutter, 


# TODO

* See [sonub git issues](https://github.com/thruthesky/sonub/issues).

# Installation


## Requirement

* Wordpress 5.6 and above.
* PHP 7.4.x and above
* Nginx
* MariaDB

## Wordpress Installation

* Install wordpress on HTTPS domain. It should work as normal.
* Permalink must be set to 'post name'.

## Git repo source

* Clone the source into wordpress themes folder.

```sh
cd wp-content/theme/sonub
git clone https://github.com/thruthesky/sonub
```

* Enable `sonub theme` on admin page.


## Database Setup

* Add `tmp/sql/sonub.sql` tables into Database.


## Firebase

Many of features are depending on firebase. So it is mandatory to setup firebase.

* First, create a firebase project in firebase console
* Then, put the `firebase admin sdk account key` file in `keys` folder. If `wp-content/themes/sonub/keys` folder does not exist, then create the folder.
* Lastly, set the path to `FIREBASE_ADMIN_SDK_SERVICE_ACCOUNT_KEY_PATH` constant in config.php

* Setup Realtime Database.
  * Create realtime database on firebase console
  * And set the database uri to `FIREBASE_DATABASE_URI`.


## In app purchase key

This is optional. Only if you are going to use in-app-purchase, set the purchase verification keys.

* If you are using in_app_purchase, then put a proper key file.


## Installing Node Modules

It uses node modules to compile sass into css, and watch file changes to live reload the browser.

* Install node modules.

```
cd wp-content/themes/sonub
npm i
```

* and watch folder and complile `scss/*.scss` to `css/*.css` like below.

```
 ./node_modules/.bin/sass --watch scss:css
```

* You may do below to watch specific file.

```
 ./node_modules/.bin/sass --watch scss/index.scss css/index.css
```

* If you want the browser reload whenever you edit php, css, javascript files, run the command below.

```
cd wp-content/themes/sonub
node live-reload.js
```




# Development Guideline

## Modules & Components

* It uses

  * Vue.js in PHP page scripts.
  * Bootstrap v5
  * Font awesome
  * Firebase Javascript SDK
  * helper.js includes the following
    * helper methods.
    * Axios js -
    * Cookie js - https://github.com/js-cookie/js-cookie
    * Lodash Full Build (24Kb gzipped) - https://github.com/lodash/lodash/wiki/Build-Differences


## Folder structures

* `sonub` is the theme folder.
* `sonub/api` is the api folder and most of codes goes in this folder.
  * `composer` is installed in this folder.
  * `sonub/api/lib/api-functions.php` is the PHP script that holds most of the core functions.
  * `sonub/api/phpunit` is the unit testing folder.
  * `sonub/api/ext` folder is where you can put your own custom routes.
  * `sonub/api/var` folder is where you can put any data there.
* `sonub/api/routes/*.route.php` is the routes(or interfaces) that client can connect using Restful API protocols.
* `sonub/themes` is the theme folder to support different themes based on different domains or options.
* `sonub/js` folder has common javascrit files.
* `sonub/css` folder has common css files.



## Setup on Local Development Computer

* Setting on local development computer may be slightly different on each developer depending on their environment.

* First, set test domains in hosts.
  * local.sonub.com as the main root site
  * apple.sonub.com as multisite
  * banana.sonub.com as multisite
 
 
* Nginx configuration. Careful on updating root, SSL certs paths. SSL certs is on wigo/tmp/ssl folder.
  * Available domains: sonub.com, www.sonub.com, local.sonub.com, api.sonub.com, api-local.sonub.com, apple.sonub.com, anana.sonub.com, cherry.sonub.com
 
 ```text
server {
  server_name  .sonub.com;
  listen       80;
  rewrite ^ https://$host$request_uri? permanent;
}
server {
  server_name .sonub.com;
  listen 443 ssl http2;
  root /Users/thruthesky/www/sonub;
  index index.php;
  location / {
    add_header Access-Control-Allow-Origin *;
    try_files $uri $uri/ /index.php?$args;
  }
  location ~ \.php$ {
    fastcgi_param REQUEST_METHOD $request_method;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
    fastcgi_pass 127.0.0.1:9000;
  }

    ssl_certificate /Users/thruthesky/www/sonub/etc/ssl/sonub.com/fullchain1.pem;
    ssl_certificate_key /Users/thruthesky/www/sonub/etc/ssl/sonub.com/privkey1.pem;

}
```

* Create database. Same database name, id, password.
* Pour tmp/sql/sonub.sql into database
* Fix urls in wp_options to 'https://local.sonub.com'



## Multi Themes

* Multi theme configuration can be set in config.php

* if theme script does not exist in that theme, then the same name of script file in default theme folder will be used.


## SEO Friendly URL

* To make the URL (of the post view page) friendly as human-readable, we use this format below.

```url
https://xxx.domain.com/post_ID/post-title
```
where the `post_ID` is the post ID and `post-title` is the post title(part of guid.).


# Web(Theme) Development

* In the section, only web development is discussed.


## Login

* Offical supported login methods are
  * Wordpress email & password login.
  * Pass login by https://developers.passlogin.com/ which does real user & adult authentication,
  * Google Firebase phone number login
  * Kakaotalk login
  * Naver login

### PASS LOGIN

* First create a project in https://developers.passlogin.com/
  * And set the callback redirect url.
* Then add settings on config.php
* When user successfully logged in, `pass-login-callback.php` (which is the redirect url) will be opened by web browser.
  * If the user is using mobile app like Flutter, then it will post message to the app.
  * If the user is using web browser, then it will redirect to home page after login.


* To link or open pass login page, code like below.
```html
<a class="btn btn-primary mt-5" href="<?=pass_login_url('openHome')?>">PASS LOGIN</a>
```


## Widget System

* Admin can change widget on admin page.
  * For instance, admin can change the forum list page look by changing the widget on forum category.

* Widget type is the folder name right under `widgets` folder.
  * For instance, the widget type of all widgets under `widgets/posts/` is `posts`.

* Widget scripts are saved under `widgets/[type]` folder.
  * `widgets/[type]/[widget-name]/[widget-name].php` is the main script of the widget.
  * `widgets/[type]/[widget-name]/[widget-name].ini` is the widget configuration file.
  * Each widget must have its `.ini` configuration file.
    * `description` is the description and it will be displayed in admin page.
* To develop a widget,
  * Create a folder name under `widgets/[type]` folder.
  * Create a php file with the same name of folder name. Ex) `widgets/sample-type/sample-default/sample-default.php`
    * The `-default` will be used if there is no widget chosen by admin.
  * Create a `.ini` configuration file.
    * And then, add `type` and `description`.
  * Lastly, you need to put it on admin page, so admin can choose which widget to display on the browser.
    * To see how to code on admin page, see `themes/sonub/themes/default/admin/forum/setting.php`.

* Widgets that are not used in admin page may not need `.ini` file.

* When including widgets, you can pass variables over the second parameter.
  It is an optional and if you can get the needed data without passing the param, then you can it in your way.

### Dynamic Widget Config

* It can display widget config by accessing `/?page=home&update_widget=widget_id#widget_id`.
  * `widgets/[widget-type]/[widget-name]/[widget-naem].config.php` will be shown below the widget for configuration the widget.
  * If widget is not selected, `widgets/dynamic/default` widget will be used.

* On the widget config, all form data is saved by `etc/widget/config.head.php`.

* 설정에 `dyanmic=yes` 로 된 위젯은 dynamic 으로도 사용 될 수 있고, 또 그냥 사용 될 수 있다.
* 그냥 사용 할 때에는 위젯 옵션으로 일일히 값을 넘기면 된다.
* dynamic 으로 사용 할 때에는 위젯 id 만 넘기면, option 에 저장된 설정을 읽어, 자동 적용을 한다.
* config 에서 원하는 값을 저장 할 수 있다.


# API & Protocols

* `sonub/api` folder has all the api codes and `sonub/api/index.php` serves as the endpoint.
* One thing to note that, `sonub` theme loads `api/lib/*.php` files and use a lot.




## Login

* For a user to log in on web browser, create a form and use `app.js::onLoginFormSubmit()` method.
  * See the HTML form example on `sonub/themes/default/user/login.php`
* When user logs in on web, `session_id`, `nickname`, `profile_photo_url` are saved through Javascript cookies.
* PHP can use the `session_id` in cookie and detect who is the user.
* To make the cookie available all sub domains, set root domain to `BROWSER_COOKIE_DOMAIN` in config.php.

### Register


### Getting Profile

### loginOrRegister

- with this one protocol, user can register or login (if they have registered already)
- When user login, the result data will have `['mode' => 'login']`, or the result data will have `['mode' => 'register']`

```
https://local.nalia.kr/v3/index.php?route=loginOrRegister&user_email=user1@test.com&user_pass=Abcde5,*&any=data&add=more&...
```


# Developer Guideline

## Precautions

* There are some functions that have confusing names.
  * `api_error()` is the one to check if the result of API function call is error or not.
  * `isError()` is used to test if the result of API call is error or not.

* Return value of route call must be an array. Or it's an error.

* Route is divided into two parts by 'comma'. The first one is class name of route, and the second is the name of the method of the route class.
  Ex) `user.login` where `user` is the route class at `routes/user.route.php` and `login` is the method of the class.

* Route class name must end with `Route` like `AppRoute`, `UserRoute`.
  * And the class call route, the route name must be lower case without `Route` from the route class name.

* Naming for vars and functions in `api/lib` folder scripts is kebab case.
  like `user_login`, `get_route`, `api_error`.
   * If a function name is conflicting with existing one, then add prefix of 'api_' like `api_edit_post()` 
   * Naming for other vars and functions outside of `api/lib` may go camel case.
   

* Error codes must begin with `ERROR_`.
  * Attention: Some error codes have extra information after clone(:).
  For instance, ERROR_FAILED_ON_EDIT_POST:Content, title, and excerpt are empty.
* Only routes functions can call `error()` or `success()`. All other functions must return an error code if there is an error.

* Route cannot return null or empty string to client. It will response error instead.

* PHP script does not have user information. That means, the user is not logged in PHP. User information (including session_id) is only saved on javascript's localStorage.
  So, you cannot code anything that is related with login.
  

## Booting

### Theme booting

* When theme is loading, the following scripts will be loaded in order.
  * wordpress index.php and its initialization files.
  * functions.php ( will be loaded by Wordpress before index.php. Don't put anything here except the hooks and filters. )
    * `functions.php` loads
      * `api/lib/api-functions.php`,
      * Preflight
      * `defines.php`
      * User login with `$_COOKIE['session_id']`. PHP can detect if user logged in or not, and can use all the user information.
      * `config.php`
      * Composer vendor auto load.
      * `api/lib/firebase.php`
  * theme index.php ( this is the theme/index.php that is the layout )
    * `index.php` loads
      * `/theme/[DOMAIN_THEME]/[DOMAIN_THEME].functions.php` is loaded if exists.
        참고: theme.functions.php 와 theme.config.php 는 관리자 페이지에 있는 경우, 해당 테마의 스크립트를 실행한다.
        참고: 따라서, 관리자 테마에서 항상 위젯의 .functions.php 를 실행한다.
      * Bootstrap 4.6 css
      * css/index.css ( compiled from scss/index.scss sass code )
      * `theme/[DOMAIN_THEME]/[MODULE]/[SCRIPT_NAME].css` if exists.
      * Page script file `wp-content/themes/wigo/themes/[DOMAIN_THEM]/[MODULE]/[SCRIPT_NAME].php` will be loaded.
      * Javascript `config` settings.
      * bootstrap v5 javascript
      * vue.prod.js
      * axios.min.js
      * firebase-app.js, firebase-messaging.js and other firebase-****.js files.
      * `theme/[DOMAIN_THEME]/[MODULE]/[SCRIPT_NAME].js` if exists.
      * `js/app.js`
        * User login in Vue.js client end. Vue.js can detect if user is logged in or not. But let PHP handle user login related code as much as possible.
      
### Admin Theme Booting

* When a page is access with `page=admin/....`, then it is considered that the user is access admin dashboard.
* When admin page is accessed,
  * sonub/index.php will be loaded,
  * sonub/themes/admin/header.php will be loaded,
  * sonub/themes/admin/home.php will be loaded,
  * sonub/themes/admin/footer.php will be loaded,
        


### API booting

* When client-end connects to backend Restful API, the following scripts will be loaded in order
  * First, client will connect to `themes/wigo/api/index.php`
  * Then, `api/index.php` will load `wp-laod.php`
  * Then, functions.php will be loaded by Wordpress,
    and it will do all initialization and make all functions ready.

  
## Javascript for each script page

It's upto you whether you use Vue.js or not. You may do what you want without Vue.js. If you like jQuery, you can do with jQuery. That's fine.


* It is recommend to write Javascript code inside the PHP script like below.
  * Use `mixin` const variable name to apply a mixin to Vue.js app in `app.js`. It is just works as what mixin is.
  * See example below and `# mxin` chapter

```html
<h1>Profile</h1>
<button type="button" @click="showProfile">Show Profile</button>
<hr>
<div v-if="show">
    {{ user }}
</div>
<script>
    const mixin = {
        created() {
            console.log('profile.created!');
        },
        data() {
            return {
                show: false,
            }
        },
        methods: {
            showProfile() {
                this.$data.show = !this.$data.show;
                console.log('user', this.$data.user);
            }
        }
    }
</script>
You can write css style like below.
<style>
    body {
        background-color: #333B38;
        color: white;
    }
</style>
<style>
    button {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
</style>
```
* Though, the script javascript file can be separately created and automatically loaded by the system as described in `Theme booting`.
  * For instance, on profile page, `theme/default/user/profile.js` will be loaded automatically if exists.
  
## CSS for each script page

* The system is using `Vue.js` and the entire body tag is wrapped as Vue.js template.
  * By default, `<style>` tags in Vue.js template are ignored. But the system handles it nicely.
    All `<style>` tags in the script file will be extracted and added after the vue template.
  
* Though, the script css file can be separately created and automatically loaded by the system as described in `Theme booting`.
  * For instance, on profile page, `theme/default/user/profile.css` will be loaded automatically if exists.



## mixin - Vue.js mixin on each page.

* The app(website) can create a mixin on each page, like below.
  * The example below shows how to interact with backend on mixin's `created()` method and display it in template.

```html
{{ settings }}
<script>
    const mixin = {
        data() {
            return {
                settings: {},
            }
        },
        created() {
            request('app.settings', undefined, function(re) {
                app.settings = re;
            }, alert);
        },
    }
</script>
```



## Extension - Write your own route

* When you need to write your own routes, write your route class under `ext` folder.

## Customizing

* You should not edit the core source files that are
  - index.php
  - defines.php
  - lib/*.php
  - routes/*.php
  
* If you need to add your own routes, you can save your routes files under `ext` folder.
* And if you need to write extra files, then write it under `var` folder.


## Files and Folders

* `lib/app.class.php` is used for holding state of app's life cycle.
* `lib/utility.php` holds all utility functions that are directly related with the system core functionalities.


* `themes/theme-name/theme-name.functions.php` 에 관리자에서 사용될 코드를 집어 넣는다.
  이유는 `configs/theme.config.php` 는 소스 코드가 테마에 한정적이 않아서이다.
  
  * theme.functions.php 의 활용 용도는 무궁무진하다.
    예를 들어, 관리자가 위젯 수정 모드로 들어가려고 할 때, 관리자 링크에서 `href="/?page=set&key=widget&value=edit"` 와 같이
    링크를 걸고, theme.functions.php 에서 아래와 같이 쿠키를 저장할 수 있다.
    
```php
if ( in('page') == 'set' ) {
    setcookie(in('key'), in('value'));
    jsGo('/');
    exit;
}
```

## 쿠키 저장

* 웹브라우저에서 간단하게 ON/OFF 용도로
  아래의 예제와 같이 md5('set')=md5('cookie') 와 같이 값을 주어 접속을 하면,
  key 의 cookie 이름으로 value 의 값을 저장한다.
  다양하게 활용을 하면 된다.

````html
<a href="/?<?=md5('set')?>=<?=md5('cookie')?>&key=<?=md5('widget')?>&value=<? echo is_widget_edit_mode() ? 'off' : 'on' ?>">
````
  
## User management

* Wordpress has `wp_users` database for storing default user information like user_login, user_email, user_pass and other information.
  * `ID`, `user_email` and `user_login` should never changed once it has set.
  * `user_pass` may be changed on a separated page(UI) from the profile edit page.
  * we only use `ID`, `user_email`, `user_login` and `user_pass` from `wp_users` table.
  * All other properties like nickname(display name), full name, gender, birthday goes into `wp_usermeta` table.
  * You may also maintain your own table for keeping user information by fixing routes.



## Protocols

### app.query

* You can directly query to database with your own SQL using `route=app.query` route.
* It is a little limited to prevent SQL Injection and accidents with wrong SQL query.
* Tables you can do SQL Query must be defined with `PUBLIC_TABLES` in config.php and you only do SELECT Query.

# Unit Test

We use `phpunit` as its primary unit testing tool. (Previous custom made unit testing tool named 'v3 test tool' has been removed by Jan 30).

* To run phpunit, just do it as phpunit way.

  * Running all unit tests at once.

```shell script
php phpunit.phar api/phpunit 
```

  * Running each test

```shell script
php phpunit.phar api/phpunit/AppVersionTest.php
```

```shell script
phpunit api/phpunit/VerifyIOSPurchaseTest.php 
```

## Watching PHP script changes.

* Use chokidar-cli to re-run the test whenever php script file changes.

```shell script
chokidar 
```


### How to write test code

* See `tests/route.test.php` for the best test example.
  * Recommended style guide
    * Prepare first,
    * Then, test functions
    * Then, test routes by creating its instance
    * Then, test with API call.

```php
<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/api-load.php');


/// Prepare test data set.
$A = 1;
$B = 2;
$C = 3;
$tokenA = 'A';
$tokenB = 'B';
$tokenC = 'C';
$extraTokenA = 'Apple';
$extraTokenB = 'Banana';
$extraTokenC = 'Cherry';


/// Step 1. Test functions
///
/// Step 2. Test route.
///
/// Step 3. Test Api call.


/** Display the summary of test results. */
displayTestSummary();
```

* Best way to write test is to following the steps below.
  * First, test all necessary functions.
  * Second, load the route class file and test route methods.
  * Lastly, test as client.



## Notable Javascript Codes

### Debouncer in app.js

* See `debounce` in app.js


### Run vue app code in script page with later()

* With `later()` function, you can use `app` in theme page script.  `later()` will be called after all javascript is ready.

```js
later(function () {
   app.loadProfileUpdateForm();
});
```

### Adding component into Vue App

* Define a component and add it with `addComponent()` function.
* `addComponent()` must be called before mounting.
* Example of adding a comment box component into Vue app)
```js
const commentForm = {
    props: ['comment_id', 'comment_parent', 'comment_content', 'comment_post_id'],
    template: '<form @submit.prevent="onSubmit"> parent comment id: {{ comment_ID }}' +
        '<i class="fa fa-camera fs-xl"></i>' +
        '<input type="text" v-model="comment_content">' +
        '<button class="btn btn-secondary ml-2" type="button" @click="hide" v-if="canShow">Cancel</button>' +
        '<button class="btn btn-success ml-2" type="submit">Submit</button>' +
        '</form>',
    data() {
        return {
            comment_ID: this.comment_id,
            comment_parent: this.comment_parent,
            comment_post_ID: this.comment_post_id,
            comment_content: this.comment_content,
        };
    },
    computed: {
        canShow() {
            return !!this.$data.comment_ID;
        }
    },
    watch: {

    },
    methods: {
        hide() {
            this.$root.replyNo = 0;
            this.$root.editNo = 0;
        },
        onSubmit() {
            request('forum.editComment', this.$data, refresh, app.error);
        },
        show() {
            console.log('show');
        }
    },
};
addComponent('comment-form', commentForm);
```

## Profile page

* `app.loadProfileUpdateForm()` will fill the `app.profile` object. So, you can display it in the form.
* `app.onProfileUpdateFormSubmit()` should be called on update button clicked.

# Push notification

* Subscribing to a specific topic for some conditions are not encouraged.
  * Suppose, user subscribed for a chat room named 'C1' using his phone named 'P1'.
  * And the user (with same login auth) changes his another phone named 'P1'.
    Now he has two devices with two tokens.
  * But the token of 'P1' subscribed only. Not the token of 'P2'.
  * When there is a new message, the message will only delivered to 'P1', not to 'P2'.
    Meaning, the user may not get push notification.
  * You may need to go for a heavy surgery of your code to make it perfectly.

  
## Limitations of push notification

* One device is limited to have no more than 2,000 topics. That means, a user cannot have more than 2,000 topics.
  If the user subscribed more than 2,000 forums or chat rooms, then there might an error.
  
  * This wouldn't be a big problem, since a user might only subscribe few chat rooms for push notification even if he/she has more than 2,000 chat rooms.

* Sending push notification is a bit slow.
  * When a user creates a comment, backend will send push notifications to users who are subscribed for that forum and to the post owner.
  * To improve this, the backend must not send push notification separately after the comment is created.\
  This means, there will be two backend calls.\
  One for creating comments, the other is for sending push notifications.
  

# Debugging Tips

## For comment edit and upload

* You can open the edit form when it is refreshed.

```js
    later(function() {
        app.editNo = 45;
    })
```

# Theme page script

* Dot(.) in `page` http var like `/?page=abc.def` will be translated to slash(/).
  * So, `page=abc.def` is same as `page=abc/def`.

# Theme Page Submission

* For any reason, if the theme page script ends with `.submit.php`, then it does not display the theme(layout).
  Instead, It only runs the script.
  This is good for submitting a form or running some code without displaying theme.
  
  Example) /?page=user/logout.submit
  Example) /?page=admin/forum/list.submit&cat_name=abc


# Theme development

## Theme layout

* Theme layout class is defined in `index.scss` that is default theme layout and can be overwritten by `!important` in each theme header.
  * To overwrite, simply define style like below
```css
    .l-center {
        max-width: 800px;
    }
```

* Theme layout can be applied like below.
```html
<header class="l-center">...</header>
<section class="l-center l-content">
  <section class="l-sidebar">...</section>
  <section class="l-body">... body ...</section>
</section>
<footer class="l-center">...</footer>
```

* If there are two sidebars on left and right, then use `.l-body-middle` over `.l-body`.

# File upload

* The code below shows how to do file upload.
* `uploadPercentage` is handled by `app.js`.

```html
<div class="position-relative">
    <div>
        <i class="fa fa-file-image fs-xxxl"></i>
    </div>
    <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileChange($event, 'A')">
    <div class="progress mt-3 w-100px" style="height: 5px;" v-if="uploadPercentage > 0">
        <div class="progress-bar" role="progressbar" :style="{width: uploadPercentage + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
<script>
    const mixin = {
        methods: {
            onFileChange(event, AB) {
                this.onFileUpload(event, function (res) {
                    console.log('uploaded file: res: ', res);
                });
            }
        }
    }
</script>
```

# 게시글에 각 항목 별 사진을 등록, 목록, 삭제하는 완전한 예제

* 기본 파일 업로드 기능 외에, 원하는 필드로 사진을 업로드하는 방법에 대한 설명.
* 참고: `widgets/forum-edit/forum-edit-shopping-mall/forum-edit-shopping-mall.php` 에 쇼핑몰 예제가 있다.


```html
<?php
if ( in('mode') == 'delete' ) {
    wp_delete_post(in('ID'));
}
?>
<section class="wrong-picture">
    <h1>틀린 그림 찾기</h1>
    <ul>
        <li>이미지 너비: 200px, 높이: 256px</li>
    </ul>
    <form @submit.prevent="onFormSubmit()">
        <div class="d-flex justify-content-center">
            <? function image_pair($ab, $name) { ?>
                <div class="position-relative of-hidden <?=$ab?>">
                    <div class="w-100px h-xxxl">
                        <i class="fa fa-file-image fs-xxxl" v-if="!<?=$ab?>"></i>
                        <img :src="<?=$ab?>" class="w-100" v-if="<?=$ab?>">
                    </div>
                    <div class="mt-2 text-center">
                        <?=$name?>
                    </div>
                    <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileChange($event, '<?=$ab?>')">
                </div>
            <? } ?>
            <? image_pair('A', '올바른 사진(A)') ?>
            <? image_pair('B', '다른 사진(B)') ?>
        </div>
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-column">
                <div class="progress mt-3 w-100px" style="height: 5px;" v-if="uploadPercentage > 0">
                    <div class="progress-bar" role="progressbar" :style="{width: uploadPercentage + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                    <button class="mt-3" type="submit">문제 등록</button>
            </div>
        </div>
    </form>
    <div class="posts">
        전체 글 목록
        <?
        $posts = forum_search(['category_name' => 'wrong_picture']);
        foreach($posts as $post) {
            ?>
            <article class="post p-3">
                <div class="d-flex">
                    <div>
                        번호: <?=$post['ID']?>
                        <div>
                            <a href="/?page=admin/game/find_wrong_picture&mode=delete&ID=<?=$post['ID']?>" class="btn btn-secondary">삭제하기</a>
                        </div>
                    </div>
                    <div class="ms-2 w-100px h-xxxl">
                        <img class="w-100" src="<?=$post['A']?>">
                    </div>
                    <div class="ms-2 w-100px h-xxxl">
                        <img class="w-100" src="<?=$post['B']?>">
                    </div>
                </div>
            </article>
        <?
        }
        ?>
    </div>
</section>

<script>
    const mixin = {
        data() {
            return {
                A: '',
                B: '',
            };
        },
        methods: {
            onFileChange(event, AB) {
                console.log(event);
                console.log(AB);
                this.onFileUpload(event, function (res) {
                    console.log('uploaded file: res: ', res);
                    app[AB] = res.url;
                });
            },
            onFormSubmit() {
                const data = {
                    category: 'wrong_picture',
                    post_title: 'wrong picture',
                    A: this.A,
                    B: this.B,
                }
                request('forum.editPost', data, function(post) {
                    console.log('post edit', post);
                    refresh();
                }, this.error);
            }
        }
    }
</script>
<style>
    .wrong-picture form .B {
        margin-left: 1em;
        color: red;
    }
</style>
```

# Configuration

* `config.php` is the configuration and the theme may have its own configuration.
* If the theme is `abc`, then `sonub/configs/abc.config.php` will be loaded if exists.

# Generating test data - posts and comments

* Use `https://wordpress.org/plugins/fakerpress/`

# Lab

* `lab` folder has experimental scripts or functions that are not required by running the system.




# Cafe (or Group)

카페 기능이 따로 있는 것이 아니라, 각 테마(theme) 에서 적절히 구현을 해야 한다. 여기서는 어떻게 하면 되는지 간략하게 설명을 한다.

* 기본적으로 2차 도메인을 사용하는 것이 원칙이다. 예) https://my-cafe.sonub.com
* 카페 관리자 정보는 update_option(key: cafe-[ID]) 을 통해서 한다. 예) cafe-my-cafe.
  * 카페 아이디가 get_option("cafe-[id]") 설정에 존재하면 카페가 존재하는 것이다.
* 각 글마다 카페 아이디를 기록한다.
* 가능하면 메인 사이트에서 각 카페 홍보를 할 때, URL 링크를 카페 도메인으로 해서 절대 경로로 연결해준다.

## Sonub Cafe 기능

* 카페 생성은 `/sonub/thems/sonub/cafe/create.submit.php` 에서 한다. 즉, 카페 생성 자체가 코어에 포함되어져 있지 않다.



# Hook System

* Hook 함수를 먼저 선언 해야한다. 예) functions.php 에서 선언
* 그리고 원하는 곳(함수 등)에서 훅을 호출하도록 하면 된다.
* 동일한 hook 이름에 여러개 훅을 지정 할 수 있다.
* 훅 함수에는 변수를 얼마든지 마음데로 지정 할 수 있으며 모두 reference 로 전달된다.

훅 함수 호출 예제)
```
function category_meta($cat_ID, $name, $default_value = '')
{
    $v = get_term_meta($cat_ID, $name, true);
    run_hook(__FUNCTION__, $v);
    if ($v) return $v;
    else return $default_value;
}
```

실제 예제)
```
add_hook('myFunc', function($name, &$v) {
    $v ++;
});
add_hook('myFunc', function($name, &$v) {
    $v ++;
});
add_hook('myFunc', function(&$name, &$v) {
    $v ++;
    $name = 'abc';
});
$n = 'User name';
$v = 1;
run_hook('myFunc', $n, $v);
d($n);
d($v);
```


# Trouble Shotting

* When use meet, 'ERROR_WRONG_PASSWORD', check if the password is really wrong. like when user do pass-login, the salt in config may be changed.

* When a user(or admin) logged in wordpress dashboard, then logout by easing session id in cookie may not work. you need to logout from wordpress.