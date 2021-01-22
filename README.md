# wigo
Withcenter Travel Application





### Installing SASS Reloader

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

## Multi Themes

* it can be set by wigo/config.php

* if theme script does not exist, then default theme script file will be used.


## SEO Friendly URL

* To make the URL (of the post view page) friendly as human-readable, we use this format below.

```url
https://xxx.domain.com/post_ID/post-title
```
where the `post_ID` is the post ID and `post-title` is the post title(part of guid.).






## Development Guideline

### Hot reload

* Run the command below.

```
 % node wp-content/themes/wigo/live-reload.js
```



