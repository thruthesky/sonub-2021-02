<?php
/**
 * @file kill-wrong-routes
 */
///
///
/// The code of this script detects if wrong routes had accessed.
/// And if so, it kills.
///
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







