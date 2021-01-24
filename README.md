# wigo

Withcenter Wordpress Multisite Theme

* It's a theme that a multisite theme functionality inside.
* And it has an API for clientend.


# Server Environment

* Server passwords. See Withcenter working 

# Installation


## Requirement

* Wordpress 5.6
* PHP 7.4.x and above
* Nginx
* MariaDB

## Wordpress Installation

* Install wordpress on HTTPS domain
* And make it working.

## Git repo source

```sh
git clone https://github.com/thruthesky/wigo wp-content/themes/wigo
```


## Setup on Local Development Computer


* Setting on local development computer may be slightly different on each developer depending on their environment.


* First, set test domains in hosts.

  * local.sonub.com as the main root site
  * api-local.sonub.com as the api site
  * apple.sonub.com as multisite
  * banana.sonub.com as multisite
  * cherry.sonub.com as multisite.
 
 
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




## Nginx Configuration on Live Server

* Skip if you are not installing on live server.
* This is the sample configuration on live Nginx server.

```text
server {
  server_name  .sonub.com;
  listen       80;
  rewrite ^ https://$host$request_uri? permanent;
}
server {
  server_name .sonub.com;
  listen 443 ssl http2;
  root /home/sonub/www;
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

  ssl_certificate /etc/letsencrypt/live/sonub.com/fullchain.pem; # managed by Certbot
  ssl_certificate_key /etc/letsencrypt/live/sonub.com/privkey.pem; # managed by Certbot
  include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
  ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}
```

## Installing SASS Reloader

Installation if npm not set
```
    npm init -y
    npm i -D sass
```
Or Simply run `npm install` to install node packages
```
    npm i
```

node watch folder
```
 ./node_modules/.bin/sass --watch scss:css
```
node watch specific file
```
 ./node_modules/.bin/sass --watch scss/index.scss css/index.css
```





# Development Guideline

## Theme

* Theme folder is only for themes. Theme folder does not have any information or meta data(files) that are related in API.
* All api things are inside 'api' folder.

### Hot reload

* Run the command below.

```
 % node wp-content/themes/wigo/live-reload.js
```






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


## Coding Guideline

### Precautions

* There are some functions that have confusing names.
  * `api_error()` is the one to check if API function call is error or not.
  * `isError()` is to test if the result of API call is error or not.

* Return value of route must be an array.


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

### Extension - Write your own route

* When you need to write your own routes, write your route class under `ext` folder.

### Customizing

* You should not edit the core source files that are
  - index.php
  - defines.php
  - lib/*.php
  - routes/*.php
  
* If you need to add your own routes, you can save your routes files under `ext` folder.
* And if you need to write extra files, then write it under `var` folder.



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
php phpunit.phar phpunit 
```

  * Running each test

```shell script
php phpunit.phar phpunit/AppVersionTest.php
```

```shell script
phpunit phpunit/VerifyIOSPurchaseTest.php 
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




# Push notification

* Subscribing to a specific topic for some conditions are not encouraged.
  * Suppose, user subscribed for a chat room named 'C1' using his phone named 'P1'.
  * And the user (with same login auth) changes his another phone named 'P1'.
    Now he has two devices with two tokens.
  * But the token of 'P1' subscribed only. Not the token of 'P2'.
  * When there is a new message, the message will only delivered to 'P1', not to 'P2'.
    Meaning, the user may not get push notification.
  * You may need to go for a heavy surgery of your code to make it perfectly.
  

