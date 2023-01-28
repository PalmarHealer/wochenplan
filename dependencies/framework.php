<?php

function random_string() {
    if(function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('mcrypt_create_iv')) {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
    } else {
        $str = md5(uniqid('kA5Ql0s2M2hveb7uEoTrj7vOFwrLsWDe', true));
    }
    return $str;
}


function redirect($newURL) {
    header("Location: $newURL");
    echo "<script>window.location.href='$newURL';</script>";
    $pdo = null;
    exit();
}

function goPageBack($Parameter) {
    header("Location: " . $_SERVER['HTTP_REFERER'] . $Parameter);
    echo "<script>history.back()</script>";
    $pdo = null;
    exit();
}

function checkUrlHasntChanged() {

    //Thanks to https://www.javatpoint.com/how-to-get-current-page-url-in-php
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $current_url = "https://";
    else
        $current_url = "http://";
    // Append the host(domain name, ip) to the URL.
    $current_url.= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $current_url.= $_SERVER['REQUEST_URI'];


    return $current_url;
}
$old_url_array = explode("?", checkUrlHasntChanged());
$new_url_array = explode("?", $_SERVER['HTTP_REFERER']);
$old_url = $old_url_array[0];
$new_url = $new_url_array[0];

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
?>