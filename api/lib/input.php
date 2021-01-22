<?php


/**
 * JSON input from Client
 * @return mixed|null
 */
function get_JSON_input()
{

    // Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));

    // Attempt to decode the incoming RAW post data from JSON.
    $decoded = json_decode($content, true);

    // If json_decode failed, the JSON is invalid.
    if (!is_array($decoded)) {
        return null;
    }

    return $decoded;
}

/**
 *
 * @note By default it returns null if the key does not exist.
 *
 *
 * @param $name
 * @param null $default
 * @return mixed|null
 *
 */
function in($name = null, $default = null)
{

    // If the request is made by application/json content-type,
    // Then get the data as JSON input.
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

//
//debug_log("CONTENT_TYPE: $contentType");
//debug_log($_SERVER);
//debug_log($_REQUEST);



    if (stripos($contentType, 'application/json') !== false ) {
        $_REQUEST = get_JSON_input();
    }

    if ($name === null) {
        return $_REQUEST;
    }
    if (isset($_REQUEST[$name])) {
        return $_REQUEST[$name];
    } else {
        return $default;
    }
}
