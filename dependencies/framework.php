<?php
require_once __DIR__ . "/config.php";
global $webroot, $permission_needed, $mte_url, $mte_secret, $pdo, $domain, $mte_lunch_data;

use JetBrains\PhpStorm\NoReturn;

if (isset($current_day)) {
    $mte_lunch_data = GetDataFromDBIfNotExistGetFromAPI($current_day, "mte", $mte_url, $mte_secret, $pdo);
} else {
    $mte_lunch_data = "%mte%";
}

if (!isset($page)) {
    $page = "";
}
if(isset($_GET["logout"])) {
    if ($_GET["logout"] == "true") { //Logout script
        Logout($domain);
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
            Redirect($domain . '/error/cookie/');
        } else { //Token war korrekt
            //Setze neuen Token
            try {
                $neuer_securitytoken = random_string();
            } catch (Exception $e) {
                ConsoleLog("Securitytoken konnte nicht erstellt werden");
                Alert("Bitte wende dich an einen Administrator");
                die("Securitytoken konnte nicht erstellt werden");
            }
            $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
            $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
            setcookie("asl_identifier", $identifier, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit
            setcookie("asl_securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit

            //Logge den Benutzer ein
            $_SESSION['asl_userid'] = $securitytoken_row['user_id'];
        }
    }

    if (isset($_SESSION['asl_userid'])) {
        if (!GetUserByID($_SESSION['asl_userid'],"available", $pdo)) {
            Logout($webroot . "/login/?message=please-login&return_to=" . GetCurrentUrl());
        }
    } else {
        Redirect($webroot . "/login/?message=please-login&return_to=" . GetCurrentUrl());
    }

    if (!$permission_needed > GetUserByID($_SESSION['asl_userid'], "permission_level", $pdo)) {
        Redirect($webroot . "/dashboard/?message=unauthorized");
    }

    $id = $_SESSION['asl_userid'];
    $permission_level = GetUserByID($id, "permission_level", $pdo);
    settype($permission_level, "int"); //Convert perm level in INT

    $vorname = GetUserByID($id, "vorname", $pdo);
    $nachname = GetUserByID($id, "nachname", $pdo);

    //GetSetting("maintenance", $pdo);
}

//Framework functions
function GetCurrentUrl(): string {
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
function GetOldUrl(): string|null {
    if (isset($_SERVER['HTTP_REFERER']) AND $_SERVER['HTTP_REFERER'] != "") {
        $url_array = explode("?", $_SERVER['HTTP_REFERER']);
        return $url_array[0];
    } else return null;
}
function UserStayedOnSite(): bool {
    $old_url = explode("?", GetOldUrl());
    $new_url = explode("?", GetCurrentUrl());
    if ($new_url[0] == $old_url[0]) return true;
    else return false;
}

function validateDate($date, $format = 'Y-m-d H:i:s'): bool {
//Thanks to php.net for the code snippet https://www.php.net/manual/en/function.checkdate.php
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function replacePlaceholders($string, $date): string {

    global $mte_lunch_data;
    if (!validateDate($date, 'Y-m-d')) {
        return $string;
    }
    $placeholders = array(
        '%knr%'   => modNumber(intval(date("W")), 3) + 2,
        '%knr:1%' => modNumber(intval(date("W")) + 1, 3) + 2,
        '%knr:2%' => modNumber(intval(date("W")) + 2, 3) + 2,
        '%knr:3%' => modNumber(intval(date("W")) + 3, 3) + 2,
        '%knr:4%' => modNumber(intval(date("W")) + 4, 3) + 2,
        '%knr:5%' => modNumber(intval(date("W")) + 5, 3) + 2,
        '%mte%' => $mte_lunch_data,
        '%<3%' => "manu ist der beste",
    );
    foreach ($placeholders as $placeholder => $replacement) {
        $string = str_replace($placeholder, $replacement, $string);
    }

    return $string;
}

function GetDataFromDBIfNotExistGetFromAPI($date, $type, $APIUrl, $APISecret, $pdo) {
    if ($type == "mte2") {
        $DBString = GetLunchData($date, $pdo);
        if (!isset($DBString) OR $DBString == "") {
            $data = json_decode(RequestAPI($APIUrl, $APISecret, $date), true);

            if ($data['available']) {
                SetLunchData($date, $data['lunch'], $pdo);
            }
            //FIXME: Try to Remove the str replace entirely
            return DecodeFromJson(str_replace('"', '', $data['lunch']));
        }
        return $DBString;
    } else {
        return "Please specify type";
    }
}

function CodeToJson($string):string {
    return trim(json_encode($string), '"');
}
function DecodeFromJson($string):string {
    $string ??= "Da ist irgendwo was schiefgelaufen...";
    return json_decode("\"" . $string . "\"");
}


/**
 * @throws Exception
 */
function random_string(): string {
    if(function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else {
        $str = md5(uniqid('kA5Ql0s2M2hv' . 'eb7uEoTrj7vOFwrLsWDe', true));
    }
    return $str;
}

function PrintLessonToPlan($date, $time, $room, $pdo, $webroot, $echo = true): string {
    $pdo = restorePDOifNeeded($pdo);

    if (!GetLessonInfo($date, $time, $room, "available", $pdo)) {
        return("");
    }
    $sick = false;
    $userid = GetLessonInfo($date, $time, $room, "userid", $pdo);
    if (GetLessonInfo($date, $time, $room, "disabled", $pdo)) {
        $sick = true;
    } else {
        foreach (GetAllSickNotesRaw($pdo) as $sickNote) {

            if (intval($sickNote['userid']) == $userid) {
                $dates = array();
                $dates[1] = $sickNote['start_date'];
                $dates[2] = $sickNote['end_date'];
                if (IsDateBetween($dates, $date)) {
                    $sick = true;
                }

            }

        }
    }

    $lesson_name        = replacePlaceholders(DecodeFromJson(GetLessonInfo($date, $time, $room, "name", $pdo)), $date);
    $lesson_username    = replacePlaceholders(DecodeFromJson(GetUserByID($userid, "vorname", $pdo)), $date);
    $lesson_description = replacePlaceholders(DecodeFromJson(GetLessonInfo($date, $time, $room, "description", $pdo)), $date);

    $return = "<div onclick='window.location=\"" . $webroot  . "/lessons/details/?id=" . GetLessonInfo($date, $time, $room, "id", $pdo) . "&date=" . $date . "\"' class='lessons pointer' style='background-color: " . GetLessonInfo($date, $time, $room, 'box-color', $pdo) . ";'><b class='lesson'>";
    if ($sick) $return .= "<s>";
    $return .= $lesson_name;
    if ($sick) $return .= "</s>";
    $return .= "</b><br><p class='author'>";
    if ($sick) $return .= "<s>";
    $return .= "(" . $lesson_username . ")";
    if ($sick) $return .= "</s>";
    $return .= "</p><p class='description'>";
    if ($sick) $return .= "<s>";
    $return .= $lesson_description;
    if ($sick) $return .= "</s>";
    $return .= "</p></div>";
    if ($echo) {
        echo $return;
    }
    return $return;
}

function PrintInfoWithDesc($date, $time, $room, $db, $webroot, $echo = true): string {
    $pdo = restorePDOifNeeded($db);

    if (!GetLessonInfo($date, $time, $room, "available", $pdo)) {
        return("");
    }
    $userid = GetLessonInfo($date, $time, $room, "userid", $pdo);
    $sick = false;
    foreach (GetAllSickNotesRaw($pdo) as $sickNote) {

        if (intval($sickNote['userid']) == $userid) {
            $dates = array();
            $dates[1] = $sickNote['start_date'];
            $dates[2] = $sickNote['end_date'];
            if (IsDateBetween($dates, $date)) {
                $sick = true;
            }

        }

    }

    $lesson_description = replacePlaceholders(GetLessonInfo($date, $time, $room, "description", $pdo), $date);
    $lesson_name        = replacePlaceholders(GetLessonInfo($date, $time, $room, "name", $pdo), $date);

    $return = "<div onclick='window.location=\"" . $webroot  . "/lessons/details/?id=" . GetLessonInfo($date, $time, $room, "id", $pdo) . "&date=" . $date . "\"' class='no_padding bold lessons pointer'><b class='no_padding lesson'>";
    if ($sick) $return .= "<s>";
    $return .= $lesson_name;
    if ($sick) $return .= "</s>";
    $return .= "</b>";
    $return .= "<p class='no_padding font-weight-normal description'>";
    if ($sick) $return .= "<s>";
    $return .= $lesson_description;
    if ($sick) $return .= "</s>";
    $return .= "</p></div>";
    if ($echo) {
        echo $return;
    }
    return $return;
}
function modNumber($number, $mod): int {
    return $number % $mod +1;
}

function PrintInfo($date, $time, $room, $db, $webroot, $echo = true): string {
    $pdo = restorePDOifNeeded($db);

    if (!GetLessonInfo($date, $time, $room, "available", $pdo)) {
        return("");
    }
    $value = replacePlaceholders(DecodeFromJson(GetLessonInfo($date, $time, $room, "name", $pdo)), $date);

    $user_names = ExplodeStringToArray($value);
    $sick_notes = GetAllSickNotesRaw($pdo);
    foreach ($sick_notes as $sickNote) {

        $username = GetUserByID($sickNote["userid"], "vorname", $pdo);
        if (in_array($username, $user_names)) {
            $dates = array();
            $dates[1] = $sickNote['start_date'];
            $dates[2] = $sickNote['end_date'];
            if (IsDateBetween($dates, $date)) {

                $value = surroundString($value, $username);
            }
        }
    }
    $return = "<div class='lessons pointer' onclick='window.location=\"" . $webroot  . "/lessons/details/?id=" . GetLessonInfo($date, $time, $room, 'id', $pdo) . "&date=" . $date . "\"'>" . $value .  "</div>";
    if ($echo) {
        echo $return;
    }
    return $return;
}

function surroundString($originalString, $stringToSurround) {
    $position = strpos($originalString, $stringToSurround);
    if ($position !== false) {
        $surroundedString = "<s class='strikethrough'>" . $stringToSurround . "</s>";
        $originalString = substr_replace($originalString, $surroundedString, $position, strlen($stringToSurround));
    }
    return $originalString;
}

function ExplodeStringToArray($string): array {
    $string = preg_replace('/[^A-Za-z0-9\-üöä]/', ' ', $string);

    $words = explode(' ', $string);
    $return = array();

    foreach ($words as $word) {
        if ($word != "") {
            $return[] = $word;
        }
    }
    return $return;
}

/**
 * @throws DOMException
 */
function prepareTableToDisplay($html, $current_day, $webroot, $pdo, $date, $sick): bool|string {
    if (is_array($html)) {
        $html2 = str_replace('"', '(quotes)', reset($html));
    } else {
        $html2 = str_replace('"', '(quotes)', $html);
    }
    $html2 = DecodeFromJson($html2);
    $html2 = str_replace('(quotes)', '"', $html2);

    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html2, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    $elements = $xpath->query('//*[@room and @time]');

    // Remove elements with class hideCell or Header, including their child elements
    $hideCellElements = $xpath->query('//*[contains(@class, "hideCell")] | //*[@class="Header"]');
    foreach ($hideCellElements as $element) {
        $element->parentNode->removeChild($element);
    }

    $dbString = serialize($pdo);
    foreach ($elements as $element) {
        $fragment = $element->ownerDocument->createDocumentFragment();
        $room = $element->getAttribute('room');
        $time = $element->getAttribute('time');

        if ($room == '9' OR $time == '1' OR ($time == '12' AND $room != '10') OR ($time == '13' AND $room == '10')) {
            $lesson = PrintInfo($current_day, $time, $room, $dbString, $webroot, false);
        } elseif ($time == '12' OR $time == '14' AND $room == '10') {
            $lesson = PrintInfoWithDesc($current_day, $time, $room, $dbString, $webroot, false);
        } else {
            $lesson = PrintLessonToPlan($current_day, $time, $room, $dbString, $webroot, false);
        }
        $script = $dom->createTextNode($lesson);
        $element->appendChild($script);
    }

    $modifiedHtml = $dom->saveHTML();


    $modifiedHtml = preg_replace('/^<!DOCTYPE.+?>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/^<html><body>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/%date%/i', $date, $modifiedHtml);
    $modifiedHtml = preg_replace('/%sick%/i', $sick, $modifiedHtml);
    return          html_entity_decode($modifiedHtml, ENT_NOQUOTES);
}

function Alert($msg): void
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function ConsoleLog($msg): void
{
    echo "<script type='text/javascript'>console.log('$msg');</script>";
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

function GetDaysOfWeek($date): array {
    $days = array();
    $input_date = strtotime($date);
    $week_day = date('w', $input_date);

    // Add days to shift input date to the following Monday
    $days_to_add = $week_day == 0 ? 1 : ($week_day == 6 ? 2 : 0);
    $next_monday = date('Y-m-d', strtotime("+$days_to_add day", $input_date));

    $days[] = date("Y-m-d", strtotime("monday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("tuesday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("wednesday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("thursday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("friday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("saturday this week", strtotime($next_monday)));
    $days[] = date("Y-m-d", strtotime("sunday this week", strtotime($next_monday)));

    return $days;
}

function PrintDays($date, $weekday_names_long): void {
    global $mte_url, $mte_secret, $pdo;
    foreach (GetDaysOfWeek($date) as $key => $day) {
        if ($key >= 5) {
            continue;
        }
        echo '<div onclick="window.location=\'../plan/?date='. $day . '\'" class="pointer align-items-center col-md-4 center2">
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
                                            <h1 class="h5 mt-4 mb-1">' . $weekday_names_long[$key + 1] . '</h1>
                                        </a>
                                        <p>Mittagessen: ' . GetDataFromDBIfNotExistGetFromAPI($day, "mte", $mte_url, $mte_secret, $pdo) . '</p>
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

function CheckPermission($required, $present, $redirect): void {
    if($present < $required) {
        Redirect($redirect);
    }
}

function IsPermitted($required, $present): bool {
    if($present >= $required) {
        return true;
    }
    return false;
}

function IsDateBetween($dates, $date_to_check): bool {
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

function IsMailAllowed($mail, $allowed_domains): bool {
    $domain = substr(strrchr($mail, "@"), 1);

    if (in_array($domain, $allowed_domains)) {
        return false;
    } else {
        return true;
    }
}

function IsDateOlderThat10Minutes($date): bool {
    $date2 = strtotime($date);
    $now = time();
    $diff = $now - $date2;
    $max_age = 10 * 60;
    return $diff <= $max_age;
}

function GenerateRandomString($length = 128): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charLength - 1)];
    }

    return $randomString;
}

function RequestAPI($url, $secret, $date): string {
    //Thanks goes to https://reqbin.com/ for the code

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = '{"secret": "' . $secret . '", "date": "' . $date . '"}';

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
function restorePDOifNeeded($pdo): PDO {
    if (!is_string($pdo)) return $pdo;
    $pdo = unserialize($pdo);
    if (!is_array($pdo)) return $pdo;
    $db_host = $pdo['host'];
    $db_port = $pdo['port'];
    $db_name = $pdo['name'];
    $db_user = $pdo['user'];
    $db_password = $pdo['password'];
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";
    return new PDO($dsn, $db_user, $db_password);
}
function prepareHtml($html): array|string|null {
    if (is_array($html)) {
        $html2 = str_replace('"', '(quotes)', reset($html));
    } else {
        $html2 = str_replace('"', '(quotes)', $html);
    }
    $html2 = DecodeFromJson($html2);
    $html2 = str_replace('(quotes)', '"', $html2);


    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="UTF-8">' . $html2, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new DOMXPath($doc);

    // Remove elements with class hideCell or Header, including their child elements
    $hideCellElements = $xpath->query('//*[contains(@class, "hideCell")] | //*[@class="Header"]');
    foreach ($hideCellElements as $element) {
        $element->parentNode->removeChild($element);
    }

    // Add the class 'open' to elements with either room or time attribute
    $elements = $xpath->query('//*[@room or @time]');
    foreach ($elements as $element) {
        $class = $element->getAttribute('class');
        $element->setAttribute('class', trim("$class preview-hover"));
    }

    // Get the modified HTML content
    $modifiedHtml = $doc->saveHTML();

    // Remove unnecessary doctype and html/body tags
    $modifiedHtml = preg_replace('/^<!DOCTYPE.+?>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/^<html><body>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/%date%/i', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/%sick%/i', '', $modifiedHtml);
    return          preg_replace('/<\/body><\/html>$/', '', $modifiedHtml);
}

#[NoReturn] function Redirect($redirectURL): void {
    //header("Location: $redirectURL");
    echo "<script>window.location.href='$redirectURL';</script>";
    exit();
}

#[NoReturn] function GoPageBack(): void {
    //header("Location: " . $_SERVER['HTTP_REFERER'] . $Parameter);
    echo "<script>history.back()</script>";
    exit();
}

#[NoReturn] function Logout($webroot): void {
    session_start();
    session_destroy();

    //Cookies entfernen
    setcookie("asl_identifier", "", time() - 3600, "/login");
    setcookie("asl_securitytoken", "", time() - 3600, "/login");

    Redirect($webroot);
}