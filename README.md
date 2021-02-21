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
  * Important! Site will produce 404 error if permalinks are changed. So, set it from the very first set up.

* On "Settings -> Media", set thumbnail size to "150x150" and medium size to "300x300".


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
  * `composer` is installed in this folder. But `vendor/autoload.php` is included by `functions.php`.
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

* Widgets that do not have `.ini` file will no be shown in admin settings.
  That means, the widget cannot be set in admin page. It may only be used programmatically.

* When including widgets, you can pass variables over the second parameter.
  It is an optional and if you can get the needed data without passing the param, then you can it in your way.

* Important, widgets must get data from `get_widget_options()` since, the parameta has hook patched data.
  * For instance, on forum list widget, the category variable has patached by hook before the widget is loaded.


### Dynamic Widget Config

* It can display widget config by accessing `/?page=home&update_widget=widget_id#widget_id`.
  * `widgets/[widget-type]/[widget-name]/[widget-naem].config.php` will be shown below the widget for configuration the widget.
  * If widget is not selected, `widgets/dynamic/default` widget will be used.

* 위젯 설정 FORM 을 전송하면, `etc/widget/config.head.php` 에서 저장한다.

* 주의: 위젯 설정은 그대로 get_posts() 함수로 전달된다. 따라서, 위젯에서 저장하는 변수를 임의대로 작정하면 안되고,
  카테고리를 지정하는 경우,'category_name' 와 같이 검색 옵션에 사용되는 옵션 이름으로 기록해야한다.
  
  * 카테고리: category_name
  * 글 수: posts_per_page
  * 특정 사용자가 쓴 글:  도움말: 글의 URL 을 복사해 넣으면 그 사용자의 최신글을 표시.

* 설정에 `dyanmic=yes` 로 된 위젯은 dynamic 으로도 사용 될 수 있고, 또 그냥 사용 될 수 있다.
  `dynamic=yes` 설정 옵션이 없는 경우는 다이나믹 위젯 선택 목록에 나오지 않는다.
  * 참고로, dynamic 위젯을 그냥 위젯으로 사용 할 때에는 일반적인 방법으로 위젯에 옵션 값을 넘기면 된다.
* 다이나믹 위젯을 원하는 위치에서
  `include dynamic_widget('widget_id')` 와 같이 호출하면, 그 위치에
  관리자가 원하는 위젯을 선택 할 수 있다.
  스크립트에서는 내부적으로 widget_id 에 해당하는 설정을, get_option() 으로 읽어
  자동 적용을 한다.
* 그리고, 위젯 설정 모드로 들어가면, 위젯 설정 아이콘을 클릭해서, 설정 화면을 열 수 있다.
  설정 화면에서 위젯 타입 선택, 게시판 카테고리 선택, 제목 변경 등 다양하게 변경을 할 수 있다.

* 옵션에 특정 사용자가 쓴 글 옵션을 줄 필요 없다. 왜냐하면 그 사용자가 정말 여러가지의 글을 쓸 수 있기 때문에 특정 카테고리화를 할 수 없다.
* 카페 기능에서, 위젯을 사용 할 때, '내 카페에서 쓴 글만 표시' 옵션을 사용하지 않는다. 왜냐하면,
  내 카페에서 쓴 글이 다른 카페에서 보여 질 수 있고, 코멘트가 다른 카페에서 쓰여지고, 그 연관 새 글이 쓰여질 수 있기 때문이다.
  
* 다음은 다이나믹 위젯을 사용하는 경우, 위젯에서 받는 일반적인 값으로 각 위젯마다 값이 다를 수 있다.

````text
Array
(
    [class] => border-radius-md
    [widget_id] => cafe-left-sidebar-widget-4
    [path] => posts/latest
    [widget_type] => posts
    [widget_title] => 아이디는?
    [category] => 
)
````

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



### theme.functions.php

* `themes/theme-name/theme-name.functions.php` 에 관리자에서 사용될 코드를 집어 넣는다.
  이유는 `configs/theme.config.php` 는 소스 코드가 테마에 한정적이 않아서이다.
  
  * theme.functions.php 의 활용 용도는 무궁무진하다.
    예를 들어, 관리자가 위젯 수정 모드로 들어가려고 할 때, 관리자 링크에서 `href="/?page=set&key=widget&value=edit"` 와 같이
    링크를 걸고, theme.functions.php 에서 아래와 같이 쿠키를 저장할 수 있다.
    
  * theme.functions.php 는 HTML 이 출력되기 전에 호출된다. 그래서 TITLE 훅을 해서 제목을 변경 할 때도 사용 할 수 있다.
    
```php
if ( in('page') == 'set' ) {
    setcookie(in('key'), in('value'));
    jsGo('/');
    exit;
}
```


* 관리자 페이지에서는 해당 theme.functions.php 를 호출하고, 추가적으로 themes/admin/admin.functions.php 가 로드된다.


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
   app.loadProfile();
});
```

### User profile update - 회원 정보 수정

* Define your own method to update user profile data instead of using `onProfileUpdateFormSubmit` that is
  defined on `app.js`, which updates the whole `app.profile` into backend and that might be the right way.

  By creating you own method, you can update only the minimal data to backend.

```html
<form @submit.prevent="onProfileFormSubmit">
    <div class="form-group mt-5 mb-3">
        <label for="profile_form_email" class="form-label">이메일 주소</label>
        <input class="form-control" type="email" placeholder="메일 주소를 입력해주세요." v-model="profile.email">
    </div>
    <div class="form-group mb-3">
        <label for="name">좌우명</label>
        <input type="text" class="form-control" v-model="profile.motto">
    </div>
    <button type="submit" class="btn btn-primary">저장</button>
</form>

<script>
    later(function () { // Since `app` is defined at the bottom of the page.
        app.loadProfile();
    });
    const mixin = {
        methods: {
            onProfileFormSubmit() { // submit the form
                this.userProfileUpdate({
                    email: this.profile.email,
                    motto: this.profile.motto
                }, function(profile) {
                    console.log('success: ', profile);
                    alert("프로필 정보를 수정하였습니다.");
                });
            }
        }
    }
</script>
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

* `app.loadProfile()` will fill the `app.profile` object. So, you can display it in the form.
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

## 카페 개요

* 참고 문서: 기획: https://docs.google.com/document/d/183T26WZtfaa0SrQRF7Ut2h_pFn3qorZk1OrdH-1VWrU/edit#heading=h.39a89ueox04d


카페 기능이 따로 있는 것이 아니라, 각 테마(theme) 에서 적절히 구현을 해야 한다. 여기서는 어떻게 하면 되는지 간략하게 설명을 한다.

* 기본적으로 2차 도메인을 사용하는 것이 원칙이다. 예) https://my-cafe.sonub.com
* 카페 관리자 정보는 update_option(key: cafe-[ID]) 을 통해서 한다. 예) cafe-my-cafe.
  * 카페 아이디가 get_option("cafe-[id]") 설정에 존재하면 카페가 존재하는 것이다.
* 각 글마다 카페 아이디를 기록한다.
* 가능하면 메인 사이트에서 각 카페 홍보를 할 때, URL 링크를 카페 도메인으로 해서 절대 경로로 연결해준다.

## Sonub Cafe 기능

* 카페 생성은 `/sonub/thems/sonub/cafe/create.submit.php` 에서 한다. 즉, 카페 생성 자체가 코어에 포함되어져 있지 않다.



# Hook System

* 워드프레스 자체의 훅을 사용 할 것을 강력히 권장한다. 하지만, 워드프레스로 할 수 없거나, 번거로운 경우, 직접 훅을 쓰면 된다.

참고) 워드프레스 훅 중에 글 가져오는 쿼리를 변경 할 수 있는 훅
```php
function set_custom_isvars( $_this ) {
    d($_this);
}
add_action('parse_query', 'set_custom_isvars');
```

* Hook 함수를 먼저 선언 해야한다. 예) functions.php 에서 선언
* 그리고 원하는 곳(함수 등)에서 훅을 호출하도록 하면 된다.
* 동일한 hook 이름에 여러개 훅을 지정 할 수 있다.
* 훅 함수에는 변수를 얼마든지 마음데로 지정 할 수 있으며 모두 reference 로 전달된다.
* 훅 함수가 값을 리턴 할 수 있다. 동일한 훅에서 리턴되는 값을 모아서, run_hoo() 의 결과로 리턴한다.
  
* 모든 훅 함수는 값을 리턴하거나 파라메타로 받은 레퍼런스 변수를 수정하는 것이 원칙이다.
  * 가능한, 어떤 값을 화면으로 출력하지 않도록 해야하지만,
  * 글 쓰기에서 권한이 없는 경우, 미리 체크를 해야하지만, 그렇지 못한 경우 훅에서 검사해서
    Javascript 로 goBack() 하고 exit 할 수 있다.
    이 처럼 꼭 필요한 경우에는 직접 HTML 출력을 한다.
  
* 훅의 목적은 가능한 기본 코드를 재 사용하되, 원하는 기능 또는 UI/UX 로 적용하기 위한 것이다.
  * 예를 들면, 게시판 목록의 기본 widget 을 사용하되, 사용자 화면에 보이거나, 알림 등의 재 활용 할 수 있도록 하는 것이다.
  
* 위젯에서 훅을 사용하는 경우, `widgets/posts/latest option` 와 같이 공백을 두고, `위젯경로 훅이름`으로 훅 이름을 정한다.

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
    return '2nd hook ';
});
add_hook('myFunc', function(&$name, &$v) {
    $v ++;
    $name = 'abc';
    return '3rd hook ';
});
$n = 'User name';
$v = 1;
echo run_hook('myFunc', $n, $v);
d($n);
d($v);
```



## 훅 목록과 설명

### 전체 훅 목록

* html_head

* html_title
  
* site_name - HTML 에 사이트 이름을 출력 할 때

* `favicon` - 파비콘을 변경 할 수 있는 훅

예제) 훅에서 값을 리턴하면 그 값을 경로로 사용. 아니면, favicon.ico 를 사용.

```html
<link rel="shortcut icon" href="<?= ($_ = run_hook('favicon')) ? $_ : 'favicon.ico'?>">
```



* category_meta,


* forum_search_option - 글 가져오는 옵션을 변경 할 수 있는 훅. 예) 국가별 카테고리에서, 카테고리 지정이 없으면, 국가 카테고리로 기본 지정한다.
  
* forum_list_header_top - 게시판 목록 최 상단에 표시
* forum_list_header_bottom - 게시판 목록의 헤더의 맨 아래 부분에 표시.

* forum_category - 포럼의 전체 영역(카테고리 목록이나 글 쓰기 등)에서 해당 게시판의 category 정보를 변경 할 수 있다.
  이를 통해 cat_name 등을 변경 하여 게시판 이름을 다르게 출력 할 수 있다.

* `widgets/posts/latest option` - 최근 글 위젯에서 글을 가져오기 전에 옵션을 수정 할 수 있는 훅
  `widgets/**/**` 에 기본적으로 모든 위젯의 훅이 들어있도록 한다.


* `widget/config.category_name categories`
  다이나믹 위젯 설정에서 카테고리를 재 지정 할 수 있다.
  전달되는 변수는 get_categories() 의 결과인데,
  변경을 하려면 배열에 category term object 를 넣어주거나
  [stdClass(slug ='', cat_name=>'')] 과 같이 slug 와 cat_name 을 가지는 stdClass 를 넣어줘도 된다.
  
  특히, 교민 포털 카테고리에서는 게시판 카테고리가 존재하지 않을 수도 있으므로, stdClass 로 만들어 넣어줘야한다.

* `widget/config.category_name default_option`
  카테고리 선택에서, 선택된 값이 없을 경우, 기본적으로 보여 줄 옵션이다. 보통은 빈 값에, "카테고리 선택" 을 표시하면 된다.
  하지만, 카페에서는 카테고리 선택이 되지 않은 경우, 국가별 카테고리로 검색을 제한해야 한다.
  

### 게시판 설정 훅

- 게시판 설정을 가져오는 함수 `category_meta()` 가 있는데, 이 함수는 단순히, 게시판의 wp_termmeta, 값을 가져오는 helper 함수이다.
  이 함수를 사용 할 때, `category_meta` 훅을 발생시킨다.
  주로 게시판 설정을 변경하고자 할 때 사용가능하다.

### 훅으로 HTML TITLE 변경하기

* 먼저 아래와 같이 HTML 페이지의 제목에서, `html_title` 훅을 통해서, 리턴 값이 있으면 그 리턴 문자열을 HTML TITLE 로 사용하게 한다.

````html
<TITLE><?= ($_ = run_hook('html_title'))? $_ : ($settings['site_name'] ?? '') ?></TITLE>
````

* 그리고 `theme.functions.php` 아래와 같이 적절한 값을 리턴하면 된다.

````php
add_hook('html_title', function() {
    if ( is_in_cafe() ) {
        $co = cafe_option();
        return $co['name'];
    }
});
````

### 훅으로 CSS 지정하기

* CSS 를 훅으로 지정해야하는 이유 중 하나는,
  * 페이지 스크립트에 `<style>` 을 추가하면 맨 HTML 에서 맨 밑에 추가되어 body background 를 지정하는 경우,
    먼저 추가된 css 의 body background 가 보이기 때문에 화면이 번쩍인다.
    그래서 아래와 같이 css 을 head 에 추가해서, 먼저 적용이 되도록 할 수 있다.

```php
<?php
add_hook('html_head', function() {
    return <<<EOS
<style>
    body {
        background-color: #5c705f !important;
        color: white !important;
    }
    header {
        margin-top: 1em;
        border-radius: 25px;
        background-color: white;
        color: black;
    }
    header a {
        display: inline-block;
        padding: 1em;
    }
    footer {
        margin-top: 1em;
        padding: 1em;
        border-radius: 25px;
        background-color: white;
        color: black;
    }
    .l-sidebar {
        margin-right: 1em;
        padding: 1em;
        border-radius: 25px;
        background-color: white;
        color: black;
    }
    .l-body-middle {
        border-radius: 25px;
        min-height: 1024px;
        background-color: white;
        color: black;
    }
</style>
EOS;

});
```

# Markdown 사용

* https://commonmark.thephpleague.com/1.5/ 을 사용한다.
* 도움말 페이지 등을 내용이 긴 HTML 을 markdown 으로 출력한다.
  `etc/markdown/display-markdown.php` 을 참조.


# 포인트 시스템

* 사용자 meta 의 point 키에 저장된다.
  * 이 `point` 는 직접 수정 할 수 없으며, `point_update()` 함수를 통해서만 가능하다.

# Settings

* 관리자 페이지에서 설정을 할 수 있다.
* 루트 도메인(1차 도메인) 별로 따로 설정을 한다.
  * 서브도메인의 경우는 따로 설정이 없다. 단, 소너브 카페 기능 처럼 직접 구현 할 수 있다.
* 주의: API 를 호출 할 때, 해당 1차 도메인으로 접속해야 한다. 다른 도메인 설정 정보를 가져와서 혼동 될 수 있으니 주의한다.


# 관리자 페이지

## 관리자 페이지 커스터마이징

* 테마 별로 홈페이지가 완전히 다를 수 있다. 예를 들면, 어떤 홈페이지는 id/password 로 회원 가입을 하고 또 어떤 홈페이지에서는 
  패스 로그인으로 실명 인증만 할 수 있다. 
  이에 따라, 관리자 페이지의 사용자 목록, 정보 보기, 수정 등의 페이지가 완전히 달라져야한다.
  
* 테마에 해당 페이지가 없으면, theme/default/**/**.php 의 것을 로드하는데, 관리자페이지도 이와 동일하다.
  다만, 관리자 페이지 스크립트와 일반 페이지 스크립트에서 파일명 충돌을 피하기 위해서 관리자 스크립트는
  항상 `themes/default/admin` 아래에 들어가게 된다. 그리고 이것은 자연스럽게 되는 것이다.
  
  예를 들어, 현재 테마가 sonub 이고, `?page=admin/user/list` 로 접속을 했다면,
  themes/admin/user/list.php 를 찾고 없으면,
  themes/default/admin/user/list.php 를 찾는다.
  
  각종 sidebar 도 이런 원리이다.

* 관리자 테마에서
  `themes/admin/sidebar.left.php` 는 layout 을 이루는 한 부분이고,
  `themes/admin/sidebar.php` 는 최 상위 sidebar 내용을 출력한다.
  그리고 각 `themes/admin/**/sidebar.php` 와 같이 폴더마다 sidebar 가 존재한다.
  
* 파일 우선 순위
  `themes/admin/**/**.php` 의 파일을 먼저 찾고, 없으면,
  `themes/해당테마/admin/**/**.php` 에서 찾고 없으면,
  `themes/default/admin/**/**.php` 가 사용된다.

  * 예를 들면, `themes/admin/sidebar.php` 가 기본 사이드바로 출력되는데, 처음 부터 이 파일이 존재하지 않는다.
    따라서, `themes/default/admin/sidebar.php` 가 사용되는 것이다.
    만약, 해당 테마에서 `themes/해당테마/`


# Cafe

## 관리자 모드


쿠키에 widget_edit=on 값이 있으면, 위젯을 수정하는 것으로 표시한다.
위젯을 수정하려면, set_cookie_url 을 링크를 걸어 on/off 를 하면 된다.


카페 관리자는 다이나믹 위젯으로 위젯 설정을 할 수 있다.

## 도메인 별 설정

* 특정 도메인에 교민 사이트 국가를 미리 정할 수 있다. 그래서 카페를 개설 할 때, 선택 할 필요없이 고정된다.
  cafe.config.php 에서 설정을 하면 된다.
  설정 예)
  `CAFE_DOMAIN_SETTING => ['countryCode' => 'KR']`
  
* 위젯에서, 국가별 게시판 카테고리를 정해 주어야하는데, 설정에서 국가별 카테고리를 선택 할 수 있도록 한다.



# 이미지 자원

* 이미지가 없는 경우나 잘못된 경우, /img 아래의 xbox.jpg 를 보여주면 된다.



# Trouble Shotting

* When use meet, 'ERROR_WRONG_PASSWORD', check if the password is really wrong. like when user do pass-login, the salt in config may be changed.

* When a user(or admin) logged in wordpress dashboard, then logout by easing session id in cookie may not work. you need to logout from wordpress.


# 우편번호

- 우체국 최신 우편번호(2021년 2월)자료를  zipcode 에 넣어 놨다.

검색 예)
select * from zipcode where eupmyun like '테헤란로%' or doro like '테헤란로%' or buildname like '테헤란로%' or ri like '테헤란로%';



# 포인트 시스템

- `api_point_history` 에 포인트 기록이 남는다.
  - 포인트 이벤트를 발생시키는 사용자 from_user_ID 와 포인트 이벤트의 대상(포인트를 받는) 사용자를 to_user_ID 에 기록한다.
    그리고 각각의 사용자에게 적용되는 포인트와, 적용된 후의 포인트 변화를 같이 기록해서 알아보기 쉽도록 한다.

- 글 쓰기를 할 때, 포인트를 차감한다면, 해당 차감 포인트 만큼 포인트를 보유해야 코멘트/글을 쓸 수 있다. 아니면 에러가 난다.


- 글/코멘트 쓰기 포인트를 양수로 정하면, 글/코멘트 쓸 때마 포인트를 증가 시킨다.
  이 때, 시간/수 제한 또는 일/수 제한을 걸면, 그 제한에 걸리는 경우, 글/코멘트를 계속 쓸 수 있지만, 포인트 증/감은 하지 않는다.
  
- 카테고리에서 제한은 글과 코멘트가 같이 정해진다. 일/수 제한을 하루 5개로 한 경우, 글을 4개 섰다면, 코멘트를 1개 밖에 포인트 증/감을 하지 못한다.

- 포인트 증/감은 안되어도 계속 해서 글/코멘트를 쓸 수 있다.

- 만약, "글/코멘트에 제한" 설정을 "예"로 하면, 포인트 증/감과 상관 없이, 시간/수, 일/수 제한에 걸리면, 글이나 코멘트를 새로 작성 할 수 없다.

- 참고로 DB 에 `from_userID`, `reason`, `category` 그리고 `stamp` 와 같이 4 개에 복합 index 가 걸려 있어,
  - 24시간 마다 한번씩 출석 포너스를 인정 한다던가,
  - 날짜별로 글 보너스를 10개만 준다던지
  - 또는 5시간 마다 최대 몇개의 글만 보너스를 준다던지 할 수 있다.
    제한을 할 때 사용된다.
    
기타 자세한 사항은 아래 항목을 참고한다.




## 포인트 증/감 제한 규정


- 포인트 증/감 제한
  추천 / 비추천은 전체 설정만 있고, 게시판 별 설정은 없다. 즉, 전체 설정이 모든 게시판의 추천/비추천에 적용된다.
  게시판 별 설정은 전체 설정이 없다. 그냥 게시판 별로 설정을 해야 한다.

  모든 포인트 증/감에는 "시간/수" 제한과 "일/수" 제한이 있는데, 2가지 방식을 둔 이유는 연속으로 포인트 증/감을 하지 못하도록 하기 위한 것이다.

  시간/수 제한: 5/6 과 같이 하면 5시간에 6번 할 수 있다. 즉, '시간 별 몇 회'로 제한 하는 것이다.
  일/수 제한: '추가로 1일 6회로 제한하면', '하루에 6번'으로 제한 하는 것이다.

  10월 10일 밤 11시 50분 부터 55분 사이에 6번 하고,
  10월 11일 새벽 0시에 다시 할 수 없다. 왜하면 5시간에 6번으로 제한이 되어져 있기 때문에 5시간을 기다려야 한다.

  만약, 시간/수 제한을 하지 않으면, 10월 10일 11시 59분에 6번 하고, 10월 11일 0시 0분에 6번 할 수 있다. 즉, 연속으로 12번 할 수 있다.
  만약, 일/수 제한을 하지 않으면, 10월 10일 0시 0분에 6번하고, 같은 날 또, 10월 10일 5시 0분에 6분 할 수 있다.

  이 처럼, 추천/비추천과 게시판에 시간/수와 일/수의 조합으로 제한을 할 수 있다.

  예) 일주일에 한번으로 제한하고 싶다면, 단순히 시간/수 제한을 168/1 로 하면 된다.

  예) 하루에 글 10번 까지 포인트를 주고 싶은데 연속으로 10번을 쓰지 못하게 하고 싶다면, 일/수 제한 10, 시간/제한 1/2 로 하면 된다.
  즉, 하루 최대 10개 까지만 되지만, 1시간에 2개까지만 인정된다. 즉, 시간 단위로 1시간에 2개씩 총 5시간에 걸쳐 하루 10개 포인트를 획득할 수 있다.

- 제한이 없으면, 포인트/증감이 계속해서 적용된다.

- 추천/비추천을 하는 사람과 받는 사람 모두 포인트 변경이 된다.


- 추천/반대는 게시판별 설정이 없고, 모든 게시판에 적용된다.
  관리자 설정에는 추천 받는 경우, 비추천 받는 경우, 추천 하는 경우, 비추천 하는 경우 4가지 설정이 있고 시간/수, 일/수 제한이 있는데,
  시간/수, 일/수 제한은 추천/비추천 하는 사람에게만 제한 된다.
  즉, 추천/비추천 받는 사람은 제한 없이 계속 해서 포인트를 받을 수 있다.

- 추천/비추천은 제한에 걸리면 포인트 증/감을 하지 않지만, 추천/비추천은 계속 할 수 있다.

- 추천/비추천을 받는 경우, 포인트가 감소한다면,
  추천/비추천 받는 사람이 포인트가 모자라도 추천/비추천을 받을 수 있으며, 이 때, 포인트가 음수로 내려가지 않고 최소 0이 된다.
  
- 추천을 받는 경우 반드시 0 또는 양의 정수 값만 입력해야 한다. 음수를 입력하면 안된다.
  - 추천을 하는 경우, (0, 양수, 음수 상관없이) 정수를 입력한다.
- 반대를 받는 경우, 반드시 0 또는 음의 정수만 입력한다. 양수를 입력하면 안된다.
  - 비추천을 하는 경우, (0, 양수, 음수 상관없이) 정수를 입력한다.
  
- 자기 자신을 추천/비추천하는 경우, 추천 하는 포인트와 추천 받는 포인트가 적용되지 않는다.(증/감하지 않는다.)

- 추천/비추천을 한 경우, 처음 한번만 포인트 증/감을 하고, 그 다음에 취소 후 다시 추천이이나 또는 반대로 추천/비추천을 해도 포인트가 증/감되지 않는다.
  즉, 한번 추천/비추천을 한 글에는 두번 적용이 안되며, 취소를 할 수 없다.

  포인트 복구하는 시스템을 만들면, 문제가 생길 수 있다.
  예를 들어, A 가 B 에게 추천을 했는데, B 가 포인트를 모두 써 버려, A 가 취소하려는데, B 포인트가 없는데, A 의 포인트를 복구한다면, 시스템적으로 포인트 손실이 생기기 때문이다.
  
  추천 취소를 할 때, 추천 기록 레코드를 DB에서 삭제하지 않고, 그대로 두기 때문에 추적 가능하다.

- 추천/비추천은 내가 포인트가 없어도 할 수 있다.
  예를 들어 추천을 할 때, 추천을 하는 사람(나)의 포인트가 -50 차감되는데, 내 포인트가 -50이 안된다면, 나의 포인트는 0으로 저장이 된다.
  하지만, 상대방의 포인트는 설정에 따라 증/감한다.
  그리고 만약, 제한에 글리면, 추천/비추천을 할 수 있지만, 나의 포인트 뿐만아니라 상대방의 포인트에 변화가 없다.
  즉, 제한에 걸리면, 추천/비추천을 할 수 있지만, 포인트 증/감이 안된다.
  비 추천도 마찬가지이다. 비추천을 할 때, 내가 포인트가 모자라면, 나의 포인트는 0이 되고, 상대방의 포인트는 설정에 따라 변한다.

- 게시판 새글을 작성하는 경우, 정수(0, 양수, 음수)를 입력한다.
  즉, 유료 게시판의 경우, 게시판에 글 작성 포인트를 음수로 두어, 사용자가 글 작성시 포인트를 차감 시킬 수 있다.

  

## 증/감 제한 활용


- 로그인 포인트는 로그인을 할 때에만 포인트 증가가 발생한다.
  따라서 회원이 한번 로그인을 해 놓고, 로그인 아웃하지 않고 사용하면 왠지 손해보는 느낌이 들 수 있다.
  이 같은 경우는 로그인 포인트를 사용하지 않도록 관리자가 시스템 설정에서 로그인 포인트를 0 으로 하면 된다.
  대신, 출석 게시판에 제한을 6/1(6시간에 1번) 과 1일 1회로 제한 해서, 특정 포인트를 주게한다.


### 제한에 따른 글/코멘트 쓰기/삭제 방지

- 시간/수, 일/수 제한에 걸리면 포인트는 증/감하지 않지만, 글/코멘트 쓰기/삭제는 계속 할 수 있다.
  이 때, '글/코멘트에 제한' 옵션을 '예'로 선택하면, 시간/수, 일/수 제한에 걸리면, 글/코멘트 쓰기를 못하게 한다. 단, 삭제는 할 수 있다. 삭제에는 제한이 없다.
  만약, '글/코멘트에 제한' 옵션을 '아니오'로 선택하면, 포인트가 증/감하지 않지만, 글/코멘트 쓰기에 제한 없이 계속 할 수 있다.


  - 예를 들어, 장터 게시판에 하루 1번만 쓰게하려고 한다면,
    - 시간/수를 5(12시간이 지나도 글 2개를 연속으로 쓰는게 아니라, 최소 5시간 간격두기),
    - 일/수를 1로 제한하면 된다.
    - 이 때, 포인트를 줘도 되고, 포인트가 굳이 필요 없으면, 모두 0 으로 두면 된다.
  
- 장터에 하루에 1번만 글 쓰기를 할 때, 상단에 자신의 글을 표시하기 위해서, 사용자는 삭제하고 다시 글을 쓰려고 할 수 있다. 이를 방지하기 위해서,
  - 주의 깊게 생각해야 할 것은, 글/코멘트 쓰기를 할 때에는 시간/수, 일/수 제한이 적용지만, 글/코멘트 삭제를 할 때에는 적용이 되지 않는다.
    그래서, 삭제하고 다시 쓸 수 있다고 생각하지만, 아니다.
    삭제하기 전에 한번 썼고, 삭제하고 다시 쓴다면, 두번째 쓰는 것이다.
    즉, 삭제하는 것은 마음데로 얼마든지 할 수 있지만, 글/코멘트 쓰는 것은 삭제를 해도 카운트 된다.
    따라서, 조금 전에 쓴 글을 삭제하고 다시 써도 안된다. 조금전에 글을 썼기 때문에, 삭제를 해도, 또 쓰지 못한다.
    


# 개발자 매뉴얼

## 데이터베이스

- 시스템에서 사용하는 모든 데이터베이스는 api- 로 시작한다.
- 그리고 대부분의 설정은 wp_options 에 저장이 된다.
- 따라서, api-** 데이터베이스를 모두 삭제하고 다시 추가해도 설정은 유지가 된다.
