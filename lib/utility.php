<?php

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function startsWith( $haystack, $needle ): bool {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith( $haystack, $needle ): bool {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}
