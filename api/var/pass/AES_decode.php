<?php
function aes_dec($str, $client_secret) {
    $key = $client_secret;
    $key_128 = substr($key, 0, 128 / 8);
    return openssl_decrypt(base64_decode($str), 'AES-128-CBC', $key_128, true, $key_128);
}
