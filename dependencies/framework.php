<?php
use JetBrains\PhpStorm\NoReturn;

function random_string(): string {
    if(function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else {
        $str = md5(uniqid('kA5Ql0s2M2hveb7uEoTrj7vOFwrLsWDe', true));
    }
    return $str;
}

function PrintLessonToPlan($date, $time, $room, $pdo) {
    if (!GetLesson($date, $time, $room, "available", $pdo)) {
        return;
    }
    $sick = false;
    $userid = GetLesson($date, $time, $room, "userid", $pdo);
    foreach (GetAllSickNotesRaw($pdo) as &$sickNote) {

        if (intval($sickNote['userid']) == $userid) {
            $dates = array();
            $dates[1] = $sickNote['start_date'];
            $dates[2] = $sickNote['end_date'];
            if (IsDateBetween($dates, $date)) {
                $sick = true;
            }

        }

    }

    echo "<p><b>"; if ($sick) { echo "<s>"; } echo GetLesson($date, $time, $room, "name", $pdo); if ($sick) { echo "</s>"; } echo "</b></p>";
    echo "<br>";
    echo "<p class='author'>"; if ($sick) { echo "<s>"; } echo "(" . GetInfomationOfUser($userid, "vorname", $pdo) . ")"; if ($sick) { echo "</s>"; } echo "</p>";

    echo "<p class='description'>"; if ($sick) { echo "<s>"; } echo GetLesson($date, $time, $room, "description", $pdo); if ($sick) { echo "</s>"; } echo "</p>";
}

function GetCurrentUrl(): string
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

function Alert($msg): void
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function GetHighestValueBelowValueName($value, $array) {
    $highest_level_below = 1;

    foreach ($array as $key => $value2) {
        if ($key <= $value && $key > $highest_level_below) {
            $highest_level_below = $key;
        }
    }

    return $array[$highest_level_below];
}

function GetDaysOfWeek($date) {
    $days = array();
    $monday = date("Y-m-d", strtotime("monday this week", strtotime($date)));
    $days[] = $monday;
    $days[] = date("Y-m-d", strtotime("tuesday this week", strtotime($date)));
    $days[] = date("Y-m-d", strtotime("wednesday this week", strtotime($date)));
    $days[] = date("Y-m-d", strtotime("thursday this week", strtotime($date)));
    $days[] = date("Y-m-d", strtotime("friday this week", strtotime($date)));
    $days[] = date("Y-m-d", strtotime("saturday this week", strtotime($date)));
    $days[] = date("Y-m-d", strtotime("sunday this week", strtotime($date)));
    return $days;
}

function PrintDays($date, $weekday_names_long) {
    foreach (GetDaysOfWeek($date) as $key => $day) {
        if ($key >= 5) {
            continue;
        }
        echo '<div onclick="window.location=\'../plan/?date='. $day . '\'" class="pointer align-items-center col-md-4 center2">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <img class="right" src="../img/preview.png" alt="Preview" style="width:150%;height:150%;">
                                    </div>
                                    <div class="col">
                                        <a href="#">
                                            <h1 class="h5 mt-4 mb-1">' . $weekday_names_long[$key + 1] . '</h1>
                                        </a>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="../plan/?date='. $day . '" class="d-flex justify-content-between text-muted"><span>Angebote ansehen</span><i class="fe fe-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>'; }
}
function PrintDay($date, $name) {

        echo '<div onclick="window.location=\'../plan/?date='. $date . '\'" class="pointer align-items-center col-md-4 center2">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                          <span class="circle circle-lg bg-light">
                            <i class="fe fe-calendar fe-24 text-primary"></i>
                          </span>
                                    </div>
                                    <div class="col">
                                        <a href="#">
                                            <h1 class="h5 mt-4 mb-1">' . $name . '</h1>
                                        </a>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="../plan" class="d-flex justify-content-between text-muted"><span>Angebote ansehen</span><i class="fe fe-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>';
}

function CheckPermission($required, $present, $redirect): void {
    if($present < $required) {
        Redirect($redirect);
    }
}

function IsDateBetween($dates, $date_to_check) {
    $start_timestamp = strtotime($dates[1]);
    $end_timestamp = strtotime($dates[2]);
    $date_to_check_timestamp = strtotime($date_to_check);

    if ($date_to_check_timestamp >= $start_timestamp && $date_to_check_timestamp < $end_timestamp) {
        return true;
    } elseif ($date_to_check_timestamp == $start_timestamp || $date_to_check_timestamp == $end_timestamp) {
        return true;
    } else {
        return false;
    }
}

function IsMailAllowed($mail, $allowed_domains) {
    $domain = substr(strrchr($mail, "@"), 1);

    if (in_array($domain, $allowed_domains)) {
        return false;
    } else {
        return true;
    }
}

function IsDateOlderThat10Minutes($date) {
    $date2 = strtotime($date);
    $now = time();
    $diff = $now - $date2;
    $max_age = 10 * 60;
    return $diff <= $max_age;
}


function GenerateRandomString($length = 128) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }

    return $randomString;
}

#[NoReturn] function Redirect($newURL) {
    header("Location: $newURL");
    echo "<script>window.location.href='$newURL';</script>";
    $pdo = null;
    exit();
}

#[NoReturn] function GoPageBack($Parameter) {
    header("Location: " . $_SERVER['HTTP_REFERER'] . $Parameter);
    echo "<script>history.back()</script>";
    $pdo = null;
    exit();
}

#[NoReturn] function Logout($webroot) {
    session_start();
    session_destroy();

    //Cookies entfernen
    setcookie("asl_identifier", "", time() - 3600, "$webroot/login");
    setcookie("asl_securitytoken", "", time() - 3600, "$webroot/login");

    Redirect($webroot);
    die();
}


$old_url_array = explode("?", GetCurrentUrl());
$old_url = $old_url_array[0];
if (isset($_SERVER['HTTP_REFERER'])) {
    $new_url_array = explode("?", $_SERVER['HTTP_REFERER']);
    $new_url = $new_url_array[0];
} else {
    $new_url = "";
}

if (!isset($page)) {
    $page = "";
}

if(isset($_GET["logout"])) {
    if ($_GET["logout"] == "true") { //Logout script
        Logout($webroot);
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


    if (isset($_SESSION['asl_userid'])) {
        if (!GetInfomationOfUser($_SESSION['asl_userid'],"available", $pdo)) {
            Logout($webroot . "/login/?message=please-login&return_to=" . GetCurrentUrl());
        }
    } else {
        Redirect($webroot . "/login/?message=please-login&return_to=" . GetCurrentUrl());
    }

    if (!$permission_needed > GetInfomationOfUser($_SESSION['asl_userid'], "permission_level", $pdo)) {
        Redirect($webroot . "/dashboard/?message=unauthorized");
    }


    $id = $_SESSION['asl_userid'];
    $permission_level = GetInfomationOfUser($id, "permission_level", $pdo);
    settype($permission_level, "int"); //Convert perm level in INT

    $email = GetInfomationOfUser($id, "email", $pdo);
    $vorname = GetInfomationOfUser($id, "vorname", $pdo);
    $nachname = GetInfomationOfUser($id, "nachname", $pdo);

}
