<?php
use JetBrains\PhpStorm\NoReturn;


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


function PrintLesson($date, $time, $room, $pdo) {
    if (!GetLesson($date, $time, $room, "available", $pdo)) {
        return;
    }
    echo "<p><b>" . GetLesson($date, $time, $room, "name", $pdo) . "</b></p>";
    echo "<br>";
    echo "<p class='author'>(" . GetInfomationOfUser(GetLesson($date, $time, $room, "userid", $pdo), "vorname", $pdo) . ")</p>";
    echo "<br><br>";
    echo "<p class='description'>" . GetLesson($date, $time, $room, "description", $pdo) . "</p>";
}

#[NoReturn] function redirect($newURL) {
    header("Location: $newURL");
    echo "<script>window.location.href='$newURL';</script>";
    $pdo = null;
    exit();
}

#[NoReturn] function goPageBack($Parameter) {
    header("Location: " . $_SERVER['HTTP_REFERER'] . $Parameter);
    echo "<script>history.back()</script>";
    $pdo = null;
    exit();
}

function getCurrentUrl(): string
{

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

$old_url_array = explode("?", getCurrentUrl());
$old_url = $old_url_array[0];
if (isset($_SERVER['HTTP_REFERER'])) {
    $new_url_array = explode("?", $_SERVER['HTTP_REFERER']);
    $new_url = $new_url_array[0];
} else {
    $new_url = "";
}
function alert($msg): void
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

if (!isset($page)) {
    $page = "";
}
#[NoReturn] function Logout() {
    session_start();
    session_destroy();

    //Cookies entfernen
    setcookie("asl_identifier", "", time() - 3600, "/login");
    setcookie("asl_securitytoken", "", time() - 3600, "/login");

    redirect($webroot);
    die();
}
if(isset($_GET["logout"])) {
    if ($_GET["logout"] == "true") { //Logout script
        Logout();
    }
}

if (!$page == "external") {

    session_start();

//Überprüfe auf den 'Angemeldet bleiben'-Cookie
    if (!isset($_SESSION['asl_userid']) && isset($_COOKIE['asl_identifier']) && isset($_COOKIE['asl_securitytoken'])) {
        $identifier = $_COOKIE['asl_identifier'];
        $securitytoken = $_COOKIE['asl_securitytoken'];

        $statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
        $result = $statement->execute(array($identifier));
        $securitytoken_row = $statement->fetch();

        if (sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
            die('Ein vermutlich gestohlener Security Token wurde identifiziert');
        } else { //Token war korrekt
            //Setze neuen Token
            $neuer_securitytoken = random_string();
            $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
            $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
            setcookie("asl_identifier", $identifier, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit
            setcookie("asl_securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit

            //Logge den Benutzer ein
            $_SESSION['asl_userid'] = $securitytoken_row['user_id'];
        }
    }


    if (!isset($_SESSION['asl_userid'])) {
        redirect($webroot . "/login/?message=please-login&url=" . getCurrentUrl());
        exit;
    }

    if (!$permission_needed > GetInfomationOfUser($_SESSION['asl_userid'], "permission_level", $pdo)) {
        redirect($webroot . "/dashboard/?message=unauthorized");
        exit;
    }


    $id = $_SESSION['asl_userid'];
    $permission_level = GetInfomationOfUser($id, "permission_level", $pdo);
    settype($permission_level, "int"); //Convert perm level in INT

    $email = GetInfomationOfUser($id, "email", $pdo);
    $vorname = GetInfomationOfUser($id, "vorname", $pdo);
    $nachname = GetInfomationOfUser($id, "nachname", $pdo);

}