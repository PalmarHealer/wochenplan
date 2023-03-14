<?php
$include_path = __DIR__ . "../../../";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

$date = ($_GET['date'] ?? '');
$location = ($_GET['location'] ?? '');
$time = ($_GET['time'] ?? '');

if (str_contains($date, "/")) {
    $newDate = date("Y-m-d", strtotime($date));
} else {
    $newDate = $date;
}
if (GetLesson($newDate, $time, $location, "available", $pdo)) {
    echo '<div class="alert alert-success center" role="alert">';
    echo '<span class="fe fe-alert-octagon fe-16 mr-2"></span>Dein Angebot kann dort stattfinden.';
    echo '</div>';
} else {
    echo '<div class="alert alert-danger center" role="alert">';
    echo '<span class="fe fe-minus-circle fe-16 mr-2"></span>Leider ist dieser Slot schon belegt von <b>' . GetLesson($newDate, $time, $location, "name", $pdo) . "</b> bitte sprich mit <b>" . GetInfomationOfUser(GetLesson($date, $time, $location, "userid", $pdo), "vorname", $pdo) . "</b>";
    echo '</div>';
}
$pdo = null;
?>

