<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $permission_needed, $permission_level, $webroot;

CheckPermission($permission_needed, $permission_level, $webroot . "/dashboard/?message=unauthorized");



$error = false;
$error_message = "";


if (!isset($_POST['type'])) {
    $error = true;
    $error_message = "Please specify your desired action";
    $response = array(
        'error' => $error_message
    );
} else {
    $type = $_POST['type'];
    if ($type == "setUserSetting") {
        $tmp = UpdateUserSetting($id, $_POST['setting'], $_POST['value'], $pdo);
        if ($tmp) {
            $response = array(
                'message' => "Gespeichert",
                'successful' => true
            );
        } else {
            $response = array(
                'message' => "Ein Fehler ist aufgetreten",
                'successful' => false
            );
        }
    }
}
// set to json
header('Content-Type: application/json');
// return the json
echo json_encode($response);

?>