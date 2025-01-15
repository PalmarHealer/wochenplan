<?php

$include_path = __DIR__ . "/..";
$current_day = ($_POST['date'] ?? '');
$mode = ($_POST['mode'] ?? '');
require_once $include_path . "/dependencies/config.php";
require_once  $include_path . "/dependencies/mysql.php";
require_once  $include_path . "/dependencies/framework.php";
global $pdo, $webroot, $relative_path, $permission_level, $create_lessons, $weekday_names;

$weekday = (new DateTime($current_day))->format('N');
$date = '<p class="white_text modt bold">';
$date .= $weekday_names[$weekday] . " " . date('d.m.Y', strtotime($current_day));
$date .= '</b>';
$names = array();
$sick_return = '<p class="white_text modt">';
$sickNoteRaw = GetAllSickNotesRaw($pdo, $current_day);
foreach ($sickNoteRaw as &$sickNote) {
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

?>
<!--
<div class="alert-message col-12 mb-4">
</div>
-->
<table class="full tg">
    <?php
    try {
        echo prepareTableToDisplay(
            GetSettingWithSuffix("plan", GetSettingWithSuffix("plan-template", "active", $pdo), $pdo),
            $current_day,
            $webroot,
            $pdo,
            $date,
            $sick_return,
            $sickNoteRaw
        );
    } catch (DOMException $e) {
        ConsoleLog("Error while loading plan. Please contact an administrator");
        Alert("Error while loading plan. Please contact an administrator");
    }
    ?>
</table>
<div class="btnPanel">
    <span onclick="openFullscreen()"    class="plan_btn open_fullscreen fe fe-24 fe-maximize-2 pointer"></span>
    <span onclick="closeFullscreen()"   class="plan_btn close_fullscreen fe fe-24 fe-minimize-2 pointer"></span>
    <?php
    if (!($mode == "week")) {
        echo '
            <span onclick="updateDateInUrl(-1, this).done(function() {isUpdating = false;})" class="plan_btn fe fe-24 fe-arrow-left pointer"></span>
            <span onclick="updateDateInUrl(1, this).done(function() {isUpdating = false;})"  class="plan_btn fe fe-24 fe-arrow-right pointer"></span>
            <span onclick="customPrint()" class="plan_btn fe fe-24 fe-download pointer"></span>';
    }
    if (IsPermitted($create_lessons, $permission_level) and !($mode == "week")) {
        echo '<span onclick="window.location.href=\'../lessons/details/?date=' . $current_day . '\'" class="plan_btn fe fe-24 fe-plus pointer"></span>';
    }
    if (!($mode == "week")) {
        echo '
            <span onclick="window.location.href=\'../dashboard\'" class="plan_btn fe fe-24 fe-home pointer"></span>';
    }
    ?>
</div>