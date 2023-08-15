<?php
$include_path = __DIR__ . "/../..";
require_once $include_path . "/dependencies/config.php";
require_once  $include_path . "/dependencies/mysql.php";
require_once  $include_path . "/dependencies/framework.php";

$date = ($_POST['date'] ?? '');
$location = ($_POST['location'] ?? '');
$time = ($_POST['time'] ?? '');
$id = ($_POST['id'] ?? '');

if (str_contains($date, "/")) {
    $single_date = explode("/", $date);
    $newDate = $single_date[2] . "-" . $single_date[1] . "-" . $single_date[0];
} else {
    $newDate = $date;
}
if (!GetLessonInfo($newDate, $time, $location, "available", $pdo)) {
    echo '<div class="alert alert-success center" role="alert">';
    echo '<span class="fe fe-alert-octagon fe-16 mr-2"></span>Dieser Slot ist noch frei.';
} elseif (GetLessonInfo($newDate, $time, $location, "id", $pdo) == $id) {
    echo '<div class="alert alert-success center" role="alert">';
    echo '<span class="fe fe-alert-octagon fe-16 mr-2"></span>Das ist dein Slot.';
} else {
    echo '<div class="alert alert-danger center" role="alert">';
    echo '<span class="fe fe-minus-circle fe-16 mr-2"></span>In diesem Slot wurde schon das Angebot <b>' . replacePlaceholders(GetLessonInfo($newDate, $time, $location, "name", $pdo), $newDate) . "</b> von <b>" . GetUserByID(GetLessonInfo($newDate, $time, $location, "userid", $pdo), "vorname", $pdo) . " </b> eingespeichert.";
}
echo '</div>';
$pdo = null;
?>

