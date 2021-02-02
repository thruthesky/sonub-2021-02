<?php
/**
 * @file kill-wrong-routes
 */
///
///
/// The code of this script detects if wrong routes had accessed.
/// And if so, it kills.
///
/// 때로는, 알 수 없는 이상한 접속이 있다. 예를 들면, 해킹을 시도하는 접속이나, Javascript 등에서 잘못된 접속이나 이미지 경로 등이 잘못된 경우 등.
/// 이러한 알 수 없는 접속을 이 위치에서 종료 시킨다.
///


/// Favicon
///
/// @attention Favicon must exists and should not come here.
///

if ( isset($_SERVER['REQUEST_URI']) ) {
    if ( strpos($_SERVER['REQUEST_URI'], 'favicon.ico') !== false ) {
        exit;
    }
}

/// Map files
///
/// @attention Request of `.map` files are killed. So, map cannot be used even for development.
///
if ( isset($_SERVER['REQUEST_URI']) ) {
    if ( strpos($_SERVER['REQUEST_URI'], '.map') > 0 ) {
        exit;
    }
}


/// Strange connection for strange 'undefined'.
///
/// - First, this often happens when the URL of image is 'undefined' in Javascript(like profile photo url) and it is referred to display.
/// - Other reasons, ... guessing it is because one of the Chrome extends installed on developer's computer.
///
if ( isset($_SERVER['REQUEST_URI']) ) {
    if ( strpos($_SERVER['REQUEST_URI'], '/undefined') !== false ) {
        exit;
    }
}







