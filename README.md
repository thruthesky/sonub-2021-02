# Sonub Theme API

* Sonub(Sonub Network Hub) is an open source, complete CMS with modern functionalities like realtime update, push notification, and more.
* It is build on Apache(or Nginx) + MySQL(or MariaDB) + PHP stack based Wordpress. It works as a theme but has very much fixed.

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

## Git repo source

* Clone the source into wordpress themes folder.

```sh
cd wp-content/theme/sonub
git clone https://github.com/thruthesky/sonub
```

* And enable it on admin page.


## Firebase

Many of features are depending on firebase. So it is mandatory to setup firebase.

* First, create a firebase project in firebase console
* Then, put the `firebase admin sdk account key` file in `keys` folder. If `wp-content/themes/sonub/keys` folder does not exist, then create the folder.
* Lastly, set the path to `FIREBASE_ADMIN_SDK_SERVICE_ACCOUNT_KEY_PATH` constant in config.php

* Setup Realtime Database.
  * Create realtime database on firebase console
  * And set the database uri to `FIREBASE_DATABASE_URI`.



## In app purchase key

* If you are using in_app_purchase, then put a proper key file.


## Installing SASS Reloader

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



# Development Guideline

## Modules & Components

* It uses

  * Vue.js in PHP page scripts.
  * Bootstrap v5
  * Font awesome
  * Firebase Javascript SDK
  * Axios Javascript


## Theme

* Theme folder is only for themes. Theme folder does not have any information or meta data(files) that are related in API.
* All api things are inside 'api' folder.

### Hot reload

* Run the command below.

```
 % node wp-content/themes/wigo/live-reload.js
```




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





## Reference

* The very first version on this module is on [`0.1` branch](https://github.com/thruthesky/v3/tree/0.1). It has user, forum, push notification functionality.
  * This [`0.1` branch](https://github.com/thruthesky/v3/tree/0.1) works with [nalia_app flutter-v3 branch](https://github.com/thruthesky/nalia_app/tree/flutter-v3) which works with the v3 0.1 branch. These two would be a good example.



## Multi Themes

* it can be set by wigo/config.php

* if theme script does not exist, then default theme script file will be used.


## SEO Friendly URL

* To make the URL (of the post view page) friendly as human-readable, we use this format below.

```url
https://xxx.domain.com/post_ID/post-title
```
where the `post_ID` is the post ID and `post-title` is the post title(part of guid.).




# API

* Api folder has all the api related codes and its `index.php` serves as the endpoint.
  * Since `api/index.php` is served directly by client end, `api` folder must contain all the necessary code likes defines, configurations, etc.
* `api/lib/` is shared by the theme.

## API methods & Protocols


### Login

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

## Booting

### Theme booting

* When theme is loading, the following scripts will be loaded in order.
  * wordpress index.php and its initialization files.
  * functions.php ( will be loaded by Wordpress before index.php. Don't put anything here except the hooks and filters. )
    * `functions.php` loads
      * `api/lib/functions.php`,
      * `defines.php`
      * `config.php`
  * index.php ( this is the theme/index.php that is the layout )
    * `index.php` loads
      * Bootstrap 4.6 css
      * css/index.css ( compiled from scss/index.scss sass code )
      * `theme/[DOMAIN_THEME]/[MODULE]/[SCRIPT_NAME].css` if exists.
      * Page script file `wp-content/themes/wigo/themes/[DOMAIN_THEM]/[MODULE]/[SCRIPT_NAME].php` will be loaded.
      * vue.prod.js
      * axios.min.js
      * firebase-app.js, firebase-messaging.js and other firebase-****.js files.
      * `theme/[DOMAIN_THEME]/[MODULE]/[SCRIPT_NAME].js` if exists.
      * js/app.js

### API booting

* When client-end connects to backend Restful API, the following scripts will be loaded in order
  * First, client will connect to `themes/wigo/api/index.php`
  * Then, `api/index.php` will load `wp-laod.php`
  * Then, functions.php will be loaded by Wordpress,
    and it will do all initialization and make all functions ready.

  
## Javascript for each script page

* It is recommend to write Javascript code inside the PHP script like below.
  * Use `mixin` const name for applying it as Vue.js Mixin into the `app.js`.

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
<style>
    body {
        background-color: #333B38;
        color: white;
    }
</style>
NOTE: style 태그를 여기서 뺀 다음, template 다음으로 밀어 넣는다.
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

* There are two methods to do unit test.
  * `V3 unit test` is developed by the core team. And is not recommended simply because it is not a standard.
  * The other one is `PHPUnit` which is more likely a standard unit testing tool for PHP. And `PHPUnit` is recommended simply because it is a de-facto standard.

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

## V3 Unit Testing

* Install `phprun` node module globally.
```text
$ npm i -g phprun
```

* You can test like below

```text
$ phprun tests/xxxx.test.php
```

Examples)
```text
% phprun tests/app.version.test.php
% phprun tests/loginOrRegister.test.php
% phprun tests/loginOrRegister_with_metadata.php
% phprun tests/loginOrRegister.function_call.test.php
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
  



# Debugging Tips

## For comment edit and upload

* You can open the edit form when it is refreshed.

```js
    later(function() {
        app.editNo = 45;
    })
```