<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");



$error = false;
$error_message = "";


if (!isset($_POST['type'])) {
    $error = true;
    $error_message = "Please specify your desired action";
} else {
    $type = $_POST['type'];
}



if (!$error) {
    if ($type == "plan") {
        $plan = CodeToJson($_POST['plan']);
        $plan = str_replace('\"', '"', $plan);
        $plan = str_replace('\n', '', $plan);
        $plan = str_replace('<\/td>', '', $plan);
        $plan = str_replace('<\/tr>', '', $plan);
        $plan = str_replace('<\/tbody>', '', $plan);
        $tmp = SetSettingWithSuffix("plan", $_POST['name'], $plan, $pdo);
        if ($tmp) {
            $response = array(
                'message' => "Gespeichert"
            );
        } else {
            $response = array(
                'message' => "Ein Fehler ist aufgetreten"
            );
        }
    }
    if ($type == "updateActivePlanTemplate") {
        $tmp = UpdateSettingWithSuffix("plan-template", "active", $_POST['name'], $pdo);
        if ($tmp) {
            $response = array(
                'message' => "Gespeichert"
            );
        } else {
            $response = array(
                'message' => "Ein Fehler ist aufgetreten"
            );
        }
    }
} else {

    $response = array(
        'error' => $error_message
    );

}
// set to json
header('Content-Type: application/json');

// return the json
echo json_encode($response);


?>