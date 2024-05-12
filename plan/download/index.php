<?php
global $db, $webroot, $weekday_names, $pdo, $version, $relative_path;
$include_path = __DIR__ . "/../..";
$current_day = $_GET['date'];
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

// import domPDF
require_once $include_path. '/dependencies/dompdf/autoload.inc.php';

$weekday = (new DateTime($current_day))->format('N');
$date = '<b class="white_text modt">';
$date .= $weekday_names[$weekday] . " " . date('d.m.Y', strtotime($current_day));
$date .= '</b>';
$names = array();
$sick_return = '<p class="white_text modt">';
foreach (GetAllSickNotesRaw($pdo) as &$sickNote) {
    $dates = array();
    $dates[1] = $sickNote['start_date'];
    $dates[2] = $sickNote['end_date'];

    if (IsDateBetween($dates, $current_day)) {
        if (!in_array($sickNote['vorname'], $names)) {
            $names[] = $sickNote['vorname'];
        }
    }

}
foreach ($names as $key => $name) {
    $sick_return .= $name;
    if ($key != count($names)-1) {
        $sick_return .= ", ";
    }
}
$sick_return .= '</p>';




try {
    $table = prepareTableToDisplay(
        GetSettingWithSuffix("plan", GetSettingWithSuffix("plan-template", "active", $pdo), $pdo),
        $current_day,
        $webroot,
        $db,
        $date,
        $sick_return
    );
} catch (DOMException $e) {
    ConsoleLog("Error while loading plan. Please contact an administrator");
    Alert("Error while loading plan. Please contact an administrator");
    die();
}
$html = '<html lang="de">
<head>
      <!-- Fonts CSS -->
      <style>' . file_get_contents($include_path . '/css/abel.css') . '</style>
      <!-- Icons CSS -->
      <style>' . file_get_contents($include_path . '/css/overpass.css') . '</style>
      <!-- App CSS -->
      <style>' . file_get_contents($include_path . '/css/app-light.css') . '</style id="lightTheme">
      <!-- Custom CSS -->
      <style>' . file_get_contents($include_path . '/css/printstyle.css') . '</style>
      <style>
             table {
                 width: 100%;
                 height: 100%;
                 font-size: 80%;
             }
      </style>
</head>
<body>
    <table>' . $table . '</table>
</body>
</html>';
//echo $html;
//return;
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Wochenplan.pdf', array('Attachment' => 0));
