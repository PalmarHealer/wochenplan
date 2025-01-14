<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/mysql.php";
global $webroot, $permission_level ,$permission_needed, $manage_other_users, $mte_url, $mte_secret, $pdo, $domain, $mte_lunch_data, $relative_path;

use JetBrains\PhpStorm\NoReturn;

$version = GetSetting("version", $pdo);
if (is_array($version)) $version = 0;
if (isset($current_day)) {
    if (!validateDate($current_day, 'Y-m-d')) {
        $mte_lunch_data = "%mte%";
    } else {
        $mte_lunch_data = GetDataFromDBIfNotExistGetFromAPI($current_day, "mte", $mte_url, $mte_secret, $pdo);
    }
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
        if (!GetUserByID($_SESSION['asl_userid'], "available", $pdo)) {
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

    $maintenance = GetSetting("maintenance", $pdo);
    if ($maintenance and $permission_level < $manage_other_users) {
        if (!str_contains(GetCurrentUrl(), "maintenance")) {
            Redirect($relative_path . "/maintenance");
        }
    }
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

function replacePlaceholders($string): string {

    global $mte_lunch_data;

    $placeholders = array(
        '%mte%' => $mte_lunch_data,
        '%<3%' => "manu ist der beste",
        '%time%' => time(),
    );
    foreach ($placeholders as $placeholder => $replacement) {
        $string = str_replace($placeholder, $replacement, $string);
    }

    return $string;
}

function GetDataFromDBIfNotExistGetFromAPI($date, $type, $APIUrl, $APISecret, $pdo) {
    if ($type == "mte") {

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
    $return = json_decode("\"" . $string . "\"");
    if ($return == null) {
        return "";
    }
    return $return;
}
function convertSpecialCharsToEntities($inputString): ?string {
    $patterns = array("/ä/", "/ö/", "/ü/", "/Ä/", "/Ö/", "/Ü/", "/ß/");
    $replacements = array("&auml;", "&ouml;", "&uuml;", "&Auml;", "&Ouml;", "&Uuml;", "&szlig;");
    return preg_replace($patterns, $replacements, $inputString);
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

function PrintLessonToPlan($date, $time, $room, $pdo, $webroot, $sickNoteRaw, $data): string
{
    $pdo = restorePDOifNeeded($pdo);

    if (!GetLessonInfo($date, $time, $room, "available", $pdo, $data)) {
        return "";
    }
    $sick = false;
    $userid = GetLessonInfo($date, $time, $room, "userid", $pdo, $data);

    if (isset($userid)) {
        $userid = processUserId($userid);
        if (GetLessonInfo($date, $time, $room, "disabled", $pdo, $data)) {
            $sick = true;
            $userIdsFound = $userid;
        } else {
            $userIdsFound = array();
            foreach ($sickNoteRaw as $sickNote) {
                if (in_array(intval($sickNote['userid']), $userid)) {
                    $userIdsFound[intval($sickNote['userid'])] = true;
                    if (count($userIdsFound) === count($userid)) {
                        $sick = true;
                        break;
                    }
                }
            }
        }
        $lesson_username    = processUserNames($userid, $pdo);
    } else {
        if (GetLessonInfo($date, $time, $room, "disabled", $pdo, $data)) {
            $sick = true;
        }
        $lesson_username = false;
        $userIdsFound = array();
    }

    $lesson_name        = replacePlaceholders(DecodeFromJson(GetLessonInfo($date, $time, $room, "name", $pdo, $data)));

    $lesson_description = replacePlaceholders(DecodeFromJson(GetLessonInfo($date, $time, $room, "description", $pdo, $data)));

    $box_color_value = GetLessonInfo($date, $time, $room, 'box-color', $pdo, $data);
    if ($box_color_value != "") {
        $box_color = "style='background-color: " . $box_color_value . ";'";
    }

    $return = "<div onclick='window.location=\"" . $webroot  . "/lessons/details/?id=" . GetLessonInfo($date, $time, $room, "id", $pdo, $data) . "&date=" . $date . "\"' class='lessons pointer' " . ($box_color ?? "") . ">";

    if (!is_bool($lesson_username)) {
        $return .= "<p class='author'>";
        $names = [];
        $count = 0;
        foreach ($lesson_username as $key => $username) {
            $name = "";
            if (array_key_exists($key, $userIdsFound) AND $userIdsFound[$key] OR $sick) {
                $name .= "<s>";
            }
            $name .= $username;
            if (array_key_exists($key, $userIdsFound) AND $userIdsFound[$key] OR $sick) {
                $name .= "</s>";
            }
            $names[] = $name;
            $count = $count + 1;
        }
        //if (count($names) > 2) {
        //    $return .= implode(", ", array_slice($names, 0, 2)) . "...";
        //} else {
        //    $return .= implode(", ", $names);
        //}
        $return .= implode(", ", $names);

        $return .= "</p>";
    }

    if ($sick) $return .= "<s>";
    $return .= "<b>" . $lesson_name . "</b>";
    if ($sick) $return .= "</s>";
    $return .= "<div class='description'>";
    if ($sick) $return .= "<s>";
    $return .= $lesson_description;
    if ($sick) $return .= "</s>";
    $return .= "</div></div>";
    return $return;
}

function processUserId($userid): array {
    if (str_contains($userid, ':')) {
        $userid = explode(':', $userid);
    } else {
        $userid = array($userid);
    }

    return $userid;
}
function processUserNames(array $userIds, $pdo): array {
    $results = [];
    foreach ($userIds as $userId) {
        $userDataJson = GetUserByID($userId, "vorname", $pdo);
        $decodedData = DecodeFromJson($userDataJson);
        $processedData = replacePlaceholders($decodedData);
        $results[$userId] = $processedData;
    }
    return $results;
}
/**
 * @throws DOMException
 */
function prepareTableToDisplay($html, $current_day, $webroot, $pdo, $date, $sick, $sickNoteRaw): bool|string {
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

    $dbString = restorePDOifNeeded($pdo);

    $identifier = GetSetting("identifier", $pdo);
    if (str_contains($current_day, "-")) {
        $db_day = $current_day . " 23:59:59";
        $db_day2 = $current_day . " 00:00:00";
        $lessons = $pdo->prepare("SELECT * FROM angebot WHERE identifier = ? AND (created_at <= ? OR date_type = 2) AND (deleted_at >= ? OR deleted_at IS NULL) AND (date = ? OR date IS NULL) ORDER BY date_type DESC");
        $lessons->execute(array($identifier, $db_day, $db_day2, $current_day));
    } else {
        $lessons = $pdo->prepare("SELECT * FROM angebot WHERE identifier = ? ORDER BY id DESC");
        $lessons->execute(array($identifier));
    }

    $lessons_stored = $lessons->fetchAll(PDO::FETCH_ASSOC);
    $lessons_filtered = [];
    $lessons_parents = [];
    foreach ($lessons_stored as $lesson_data) {
        $parentId = $lesson_data['parent_lesson_id'];
        if ($parentId) {
            $lessons_parents[] = $parentId;
        }
    }
    foreach ($lessons_stored as $lesson_data) {
        if (in_array($lesson_data['id'], $lessons_parents)) {
            continue;
        }
        $lessons_filtered[] = $lesson_data;
    }
    foreach ($elements as $element) {
        $room = $element->getAttribute('room');
        $time = $element->getAttribute('time');

        $lesson = PrintLessonToPlan($current_day, $time, $room, $dbString, $webroot, $sickNoteRaw, $lessons_filtered);
        $script = $dom->createTextNode($lesson);
        $element->appendChild($script);
    }

    $modifiedHtml = $dom->saveHTML();


    $modifiedHtml = preg_replace('/^<!DOCTYPE.+?>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/^<html><body>/', '', $modifiedHtml);
    $modifiedHtml = preg_replace('/%date%/i', $date, $modifiedHtml);
    $modifiedHtml = replacePlaceholders($modifiedHtml);
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

function displayChildLessons($parentId, $pdo) {
    $output = '
                            
                            <div class="col-md-12 mb-4">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <strong class="card-title">Tages bearbeitungen</strong>
                                    </div>
                                    <div class="card-body">
                                        <!-- table -->
                                        <table class="table table-hover pointer">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>Angebot</th>
                                                <th>Beschreibung</th>
                                                <th>Ort</th>
                                                <th>Zeitpunkt</th>
                                                <th>Tag</th>
                                                <th>Farbe</th>
                                                <th>Person</th>
                                                <th>Notizen</th>
                                                <th>Aktionen</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            ';
    $tmp = GetAllChildLessons($parentId, $pdo);
    if ($tmp === "") return "";
    $output .= $tmp;
    $output .= '
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- simple table -->';
    return $output;
}


function CheckPermission($required, $present, $redirect): bool {
    if($present < $required) {
        if ($redirect == null) return false;
        Redirect($redirect);
    }
    return true;
}

function IsPermitted($required, $present): bool {
    if($present >= $required) {
        return true;
    }
    return false;
}

function IsDateBetween($dates, $date_to_check): bool {
    $start_timestamp = strtotime($dates[1]);
    $end_timestamp = strtotime($dates[2] ?? $dates[1]);
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
function deleteUpdateFolders($dir): void {
    if (!file_exists($dir)) {
        return;
    }
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $fileInfo) {
        if ($fileInfo->isDir()) {
            rmdir($fileInfo->getRealPath());
        } else {
            unlink($fileInfo->getRealPath());
        }
    }
    rmdir($dir);
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
    if ($redirectURL == "") GoPageBack();
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