<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $permission_level, $create_lessons, $create_lessons_plus, $permission_level_names, $relative_path, $version, $create_lessons_for_others, $id, $pdo;
$room_names = GetSetting("rooms", $pdo);
$times = GetSetting("times", $pdo);

CheckPermission($create_lessons, $permission_level, "../?message=unauthorized");

$lesson_deleted = false;
$lesson_disabled = false;
$disable_enable_text = "deaktivieren";
$delete_text = "Angebot";
$buttons_attr = "";

$return_to = ($_POST['return_to'] ?? "");
$get_date = ($_GET['date'] ?? null);

if (isset($_GET["remove_lesson_with_id"])) {
    $lesson_to_delete = $_GET["remove_lesson_with_id"];
    $user_permission_level = GetUserByID($_SESSION['asl_userid'], "permission_level", $pdo);
    if ($user_permission_level >= $create_lessons_for_others) {
        try {
            DeleteLesson($lesson_to_delete, $pdo);
        } catch (DateMalformedStringException $e) {
            Alert("Error while deleting lesson");
        }
        if ($return_to == null) {
            GoPageBack();
        } else {
            Redirect($return_to);
        }
    } elseif ($_SESSION['asl_userid'] == GetLessonInfoByID($lesson_to_delete, "userid", $pdo) and $user_permission_level >= $create_lessons) {
        try {
            DeleteLesson($lesson_to_delete, $pdo);
        } catch (DateMalformedStringException $e) {
            Alert("Error while deleting lesson");
        }
        Redirect($return_to);
    } else {
        GoPageBack();
    }
}
if(UserStayedOnSite() AND $_SERVER["REQUEST_METHOD"] == "POST") {

    //Get form data
    if (isset($_POST['date-repeat'])) {
        $new_date = $_POST['date-repeat'];
        $date_type = "1";
    } elseif (isset($_POST['date'])) {
        $date = $_POST['date'];
        $single_date = explode("/", $date);
        $new_date = $single_date[2] . "-" . $single_date[1] . "-" . $single_date[0];
        $date_type = "2";
    } else {
        $new_date = "0";
        $date_type = "0";
    }

    $new_name = ($_POST['name'] ?? 'test');
    $new_description = ($_POST['description'] ?? '');
    $new_location = ($_POST['location'] ?? '');
    $new_time = ($_POST['time'] ?? '');
    $new_box_color = ($_POST['box-color'] ?? '');
    $new_notes = ($_POST['notes'] ?? '');
    $new_assigned_user_id = ($_POST['creator'] ?? null);
    if (is_array($new_assigned_user_id)) {
        $new_assigned_user_id = implode(':', $new_assigned_user_id);
    }


    $dub_id = ($_GET['dub_id'] ?? null);
    $lesson_id = ($_POST["update_lesson_with_id"] ?? null);
    $tmp_plan_date = ($_POST['plan_date'] ?? '');
    $plan_date = date("Y-m-d", strtotime($tmp_plan_date));
    $enable_disable_lesson = ($_GET['disable_enable_lesson'] ?? null);

    if($permission_level < $create_lessons_for_others) {
        $new_assigned_user_id = $id;
    }

    if (!CodeToJson("Test" . $new_name . $new_description)) {
        if (!CodeToJson("Test" . $new_name)) {
            $error = "Der Name des Angebotes enthält ein Zeichen das nicht abgespeichert werden konnte.";
        } else {
            $error = "Die Beschreibung des Angebotes enthält ein Zeichen das nicht abgespeichert werden konnte.";
        }
        Alert($error);
        die();
    }
    if (isset($dub_id)) {

        UpdateOrInsertLesson("create", $pdo, "",
            $dub_id,
            "2",
            $plan_date,
            $new_name,
            $new_description,
            $new_location,
            $new_time,
            $new_box_color,
            $new_notes,
            $new_assigned_user_id,
            $_SESSION['asl_userid'],
            0
        );
        Redirect($return_to);
    }
    // Update Lesson
    elseif (isset($_POST["update_lesson_with_id"])) {

        if ($date_type == 1) {
            CheckPermission($create_lessons_plus, $permission_level, "../?message=unauthorized");
        }

        UpdateOrInsertLesson("update", $pdo, $_POST["update_lesson_with_id"], null,
            $date_type,
            $new_date,
            $new_name,
            $new_description,
            $new_location,
            $new_time,
            $new_box_color,
            $new_notes,
            $new_assigned_user_id,
            $_SESSION['asl_userid'],
            0
        );

        Redirect($return_to);

        // Create Lesson
    } elseif (($_POST['save'] ?? 0) == "1") {


        UpdateOrInsertLesson("create", $pdo, "", null,
            $date_type,
            $new_date,
            $new_name,
            $new_description,
            $new_location,
            $new_time,
            $new_box_color,
            $new_notes,
            $new_assigned_user_id,
            $_SESSION['asl_userid'],
            0
        );
        Redirect($return_to);
    }

    elseif (isset($enable_disable_lesson)) {
        if (GetLessonInfo($plan_date, $new_time, $new_location, "date_type", $pdo) == 1 AND
            $plan_date != "" AND
            GetLessonInfoByID($enable_disable_lesson, "disabled", $pdo
            )) {
                $parent_id = (is_numeric($enable_disable_lesson) ? $enable_disable_lesson : null);
                UpdateOrInsertLesson("create", $pdo, "",
                $parent_id,
                "2",
                $plan_date,
                $new_name,
                $new_description,
                $new_location,
                $new_time,
                $new_box_color,
                $new_notes,
                $new_assigned_user_id,
                $_SESSION['asl_userid'],
                1
            );
        }
        elseif (!GetLessonInfoByID($enable_disable_lesson, "disabled", $pdo)) {

            DisableLesson($_SESSION['asl_userid'], $enable_disable_lesson, $pdo);

        } else {

            EnableLesson($_SESSION['asl_userid'], $enable_disable_lesson, $pdo);
        }

        Redirect($return_to);

    }
}


//Get lesson
if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];

    if($permission_level < $create_lessons_for_others and ($_SESSION['asl_userid'] ?? '') != GetLessonInfoByID($lesson_id, "userid", $pdo)) {
        Redirect($return_to);
    }

    if (GetLessonInfoByID($lesson_id, "available", $pdo)) {
        $lesson_details['name']        = GetLessonInfoByID($lesson_id, "name", $pdo);
        $lesson_details['parent_lesson_id']        = GetLessonInfoByID($lesson_id, "parent_lesson_id", $pdo);
        $lesson_details['description'] = GetLessonInfoByID($lesson_id, "description", $pdo);
        $lesson_details['location']    = GetLessonInfoByID($lesson_id, "location", $pdo);
        $lesson_details['time']        = GetLessonInfoByID($lesson_id, "time", $pdo);
        $lesson_details['notes']       = GetLessonInfoByID($lesson_id, "notes", $pdo);
        $lesson_details['box-color']   = GetLessonInfoByID($lesson_id, "box-color", $pdo);
        $lesson_details['userid']      = GetLessonInfoByID($lesson_id, "userid", $pdo);

        $lesson_details['userid'] = processUserId($lesson_details['userid']);

        $lesson_details['date-raw']    = GetLessonInfoByID($lesson_id, "date", $pdo);
        if (GetLessonInfoByID($lesson_id, "disabled", $pdo)) {
            $disable_enable_text = "aktivieren";
            $lesson_disabled = true;
        }
        if (is_numeric($lesson_details['parent_lesson_id'])) {
            $delete_text = "Bearbeitung";
        }
        if (GetLessonInfoByID($lesson_id, "deleted_at", $pdo) !== null) {
            $lesson_deleted = true;
            $buttons_attr = "disabled";
        }
        if (str_contains($lesson_details['date-raw'], "-")) {
            $lesson_details['date-type'] = 2;
            $lesson_details['lesson-type-text'] = "Einmaliges Angebot";
            $lesson_details['date'] = date("d/m/Y", strtotime($lesson_details['date-raw']));
        } else {
            $lesson_details['date-type'] = 1;
            $lesson_details['lesson-type-text'] = "Wiederholendes Angebot";
            $lesson_details['date'] = $lesson_details['date-raw'];
        }
    } else {
        Redirect($return_to);
    }

}

if (isset($lesson_details['userid'])) $userArray = $lesson_details['userid'];
else $userArray[] = $id;

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">


    <title>Angebot Verwalten</title>


    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
    <!-- Fonts CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/abel.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/overpass.css?version=<?php echo $version; ?>">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/feather.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/dataTables.bootstrap4.css?version=<?php echo $version; ?>">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/daterangepicker.css?version=<?php echo $version; ?>">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) == "true") echo "disabled"; ?>>
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) != "true") echo "disabled"; ?>>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">
    <!-- Site Css -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/select2.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/quill.snow.css?version=<?php echo $version; ?>">
</head>
<body class="vertical light">
<div class="wrapper">
    <?php
    $keep_pdo = true;
    require $include_path. "/include/nav.php";
    ?>
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div id="availability" class="availability sticky-using-fixed">
                    <div class="alert alert-secondary center" role="alert">
                        <span class="fe fe-alert-octagon fe-16 mr-2"></span>Lade Slot info...
                    </div>
                </div>

            </div>
            <form action="./" method="post">
                <label>
                    <input hidden type="text" name="return_to" value="<?php echo $_SERVER['HTTP_REFERER'] ?? "/dashboard"; ?>">
                </label>
                <label>
                    <input hidden type="text" name="plan_date" value="<?php echo ($get_date ?? null); ?>">
                </label>
                <div class="col-12">
                    <?php
                    if(isset($_GET['id'])) {

                        if (is_int($lesson_details['parent_lesson_id']) AND $lesson_details['date-type'] == 2) {
                            echo '<button type="submit" class="lesson-details-btn btn mb-2 btn-outline-primary" formaction="./?id=' . $lesson_details['parent_lesson_id'] . '">Zurück zur Vorlage</button><br>';
                        }
                        echo "<div class='vertical-alignment align-items-center'>";
                        echo "<h2 class='page-title'>Angebot bearbeiten</h2>";
                        echo '<div class="alert alert-success lesson-type-indicator display-inherit">  <span class="fe fe-info fe-16 mr-2"></span><p class="no_margin""> ' . $lesson_details['lesson-type-text'] . ' </p></div>';
                        if ($lesson_deleted) {
                            echo '<div class="alert alert-danger lesson-type-indicator display-inherit">  <span class="fe fe-alert-triangle fe-16 mr-2"></span><p class="no_margin"">Angebot gelöscht</p></div>';
                        }
                        if ($lesson_disabled) {
                            echo '<div class="alert alert-warning lesson-type-indicator display-inherit">  <span class="fe fe-alert-triangle fe-16 mr-2"></span><p class="no_margin"">Angebot deaktiviert</p></div>';
                        }
                        echo "</div>";
                        if($permission_level >= $create_lessons_for_others) {
                            echo '<p class="text-muted">Letzte Änderung am: ' . date("d.m.Y H:i", strtotime(GetLessonInfoByID($lesson_id, "updated_at", $pdo))) . ' von: '. GetUserByID(GetLessonInfoByID($lesson_id, "last_change", $pdo), "vorname", $pdo) . '</p>';
                        }
                    } else {
                        echo "<h2 class='page-title'>Angebot erstellen</h2>";
                    }
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Angebot details</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name">Name des Angebotes</label>
                                        <div type="text" id="nameEditor" class="form-control">
                                            <?php echo ($lesson_details['name'] ?? '');?>
                                        </div>
                                        <input pattern='[^"]*' title="Anführungszeichen sind nicht erlaubt!" name="name" type="text" id="name" class="hidden-input form-control" value="<?php echo ($lesson_details['name'] ?? '');?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="description">Weitere Beschreibung</label>
                                        <div name="description" type="text" id="descriptionEditor" class="form-control">
                                            <?php echo ($lesson_details['description'] ?? '');?>
                                        </div>
                                        <input pattern='[^"]*' title="Anführungszeichen sind nicht erlaubt!" name="description" type="text" id="description" class="hidden-input form-control" value="<?php echo ($lesson_details['description'] ?? '');?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / .card -->
                    <div class="row">


                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header overflow-scroll">
                                    <label for="location"></label>
                                    <label for="time"></label>
                                    <select hidden name="location" class="form-control dropdown" id="location" onchange="updateAvailability()">
                                        <?php
                                        $selected_location = array();
                                        if(isset($lesson_details['location'])) {
                                            $selected_location[$lesson_details['location']] = "selected";
                                        }
                                        $count = 0;
                                        foreach ($room_names as $value => $i) {
                                            $count++;
                                            echo '<option value="' . $value . '" ' . ($selected_location[$value] ?? '') . '>' . $i . '</option>';
                                        }
                                        ?>

                                    </select>
                                    <select hidden name="time" class="form-control" id="time" onchange="updateAvailability()">
                                        <?php
                                        $selected_time = array();
                                        if(isset($lesson_details['time'])) {
                                            $selected_time[$lesson_details['time']] = "selected";
                                        }
                                        $count = 0;
                                        foreach ($times as $value => $i) {
                                            $count++;
                                            echo '<option value="' . $value . '" ' . ($selected_time[$value] ?? '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <table class="full tg-small-preview">
                                        <?php
                                        echo prepareHtml(GetSettingWithSuffix("plan", GetSettingWithSuffix("plan-template", "active", $pdo), $pdo));
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="color-picker">Farbe</label>
                                        <select id="color-picker" class="color_picker form-control" type="text" name="box-color">
                                            <option disabled selected value="1">Farbe auswählen</option>
                                            <?php
                                            $array = GetSetting("colors", $pdo);
                                            if (is_array($array)) {
                                                ksort($array);
                                                $box_color = ($lesson_details['box-color'] ?? 'null');
                                                foreach ($array as $key => $value) {
                                                    if ($box_color == $value) {
                                                        echo "<option selected value='" . $value . "'>" . $key . "</option>";
                                                    } else {
                                                        echo "<option value='" . $value . "'>" . $key . "</option>";
                                                    }
                                                }
                                            } else {
                                                if ($lesson_details['box-color'] == $array) {
                                                    echo "<option selected value='" . $array . "'>" . $array . "</option>";
                                                } else {
                                                    echo "<option value='" . $array . "'>" . $array . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                        </div>

                        <?php
                        $date_type = array();

                        if(isset($lesson_details['date-type'])) {
                            $date_type[$lesson_details['date-type']] = "active";
                        } else {
                            $date_type[1] = "active";
                            $lesson_details['date-type'] = 1;
                        }
                        ?>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="d-flex flex-row tab-icon">
                                    <div id="date_type" class="nav flex-column nav-pills" aria-orientation="vertical" date_type="<?php echo $lesson_details['date-type']; ?>">


                                        <a onclick="updateAvailability()" title="Regelmäßiges Angebot" class="date_selector1 nav-link py-3 <?php echo ($date_type[1] ?? '');?>" data-toggle="pill" aria-selected="true"><span class="fe fe-16 fe-repeat"></span></a>
                                        <a onclick="updateAvailability()" title="Einmaliges Angebot" class="date_selector2 nav-link py-3 <?php echo ($date_type[2] ?? '');?>" data-toggle="pill" aria-selected="false"><span class="fe fe-16 fe-calendar"></span></a>
                                    </div>
                                    <div class="form-group mb-3 full-percentage">
                                        <div class="card-body">

                                            <label for="day">Tag des Angebotes</label>

                                            <div class="repeating" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") echo "style='display: none;'"; ?>>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><span class="fe fe-16 fe-repeat"></span></span>
                                                    </div>
                                                    <select id="day" onchange="updateAvailability()" name="date-repeat" class="form-control toggle_date_input1 dropdown" <?php if($lesson_details['date-type'] == "2") { echo "disabled"; } ?> id="type-select">
                                                        <?php
                                                        $selected_date = array();
                                                        $selected_date[$lesson_details['date'] ?? date("N")] = "selected";
                                                        ?>
                                                        <option value="1" <?php if(isset($selected_date[1])) { echo $selected_date[1]; }?>>Montag</option>
                                                        <option value="2" <?php if(isset($selected_date[2])) { echo $selected_date[2]; }?>>Dienstag</option>
                                                        <option value="3" <?php if(isset($selected_date[3])) { echo $selected_date[3]; }?>>Mittwoch</option>
                                                        <option value="4" <?php if(isset($selected_date[4])) { echo $selected_date[4]; }?>>Donnerstag</option>
                                                        <option value="5" <?php if(isset($selected_date[5])) { echo $selected_date[5]; }?>>Freitag</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="once" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "1" OR !isset($lesson_details['date-type'])) { echo "style='display: none;'"; } ?>>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><span class="fe fe-16 fe-calendar"></span></span>
                                                    </div>
                                                    <input id="day2" onchange="updateAvailability()" name="date" type="text" class="form-control drgpicker toggle_date_input2" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "1" OR !isset($lesson_details['date-type'])) { echo "disabled"; } ?> id="date-input1" value="
                                                      <?php
                                                    if(isset($get_date) OR isset($_POST['date'])) {
                                                        $date_tmp = ($get_date ?? $_POST['date']);
                                                        echo date("d/m/Y", strtotime($date_tmp));
                                                    } elseif (isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") {
                                                        echo $lesson_details['date'];
                                                    } else {
                                                        echo date("d/m/Y");

                                                    }
                                                    ?>
                                                      " aria-describedby="button-addon2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="creator">Wer macht dieses Angebot?</label>
                                        <select multiple="multiple" id="creator" name="creator[]" class="form-control select-multi" <?php
                                        if(!CheckPermission($create_lessons_for_others, $permission_level, null)) {
                                            echo "disabled";
                                        }
                                        ?>><?php
                                        GetAllUsersAndPrintForSelect($pdo, $id, $permission_level, $create_lessons, $userArray);
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header">
                                    <strong class="card-title">Zusätzliche Informationen (sind nur hier sichtbar und werden nicht auf dem Plan gezeigt)</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="notes"></label>
                                        <input id="notes" name="notes" class="form-control form-control-lg" type="text" placeholder="Notizen" maxlength="255" value="<?php echo ($lesson_details['notes'] ?? '');?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        if (isset($lesson_id)) echo displayChildLessons($lesson_id, $pdo);
                        ?>

                        <div class="col-md-12 mb-4">
                            <button <?php echo $buttons_attr; ?> type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zurück</button>
                            <?php
                            if(isset($_GET['id'])) {

                                if ($lesson_details['date-type'] == 1 && isset($get_date)) {
                                    echo '<button ' . $buttons_attr . ' style="float:right;" type="sumbit" class="lesson-details-btn btn mb-2 btn-outline-success" name="date" value="' . $get_date . '" formaction="./?dub_id=' . $_GET['id'] . '">Aktualisieren</button>';
                                    if (CheckPermission($create_lessons_plus, $permission_level, null)) {
                                        echo '<button ' . $buttons_attr . ' style="float:right;" type="submit" class="lesson-details-btn btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Angebot für alle Wochen Aktualisieren</button>';
                                    }
                                } else {
                                    echo '<button ' . $buttons_attr . ' style="float:right;" type="submit" class="lesson-details-btn btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Aktualisieren</button>';

                                }



                                echo '<button ' . $buttons_attr . ' type="submit" class="lesson-details-btn btn mb-2 btn-outline-warning" formaction="./?disable_enable_lesson=' . $_GET['id'] . '">Angebot ' . $disable_enable_text . '</button>';
                                echo '<button ' . $buttons_attr . ' type="submit" class="lesson-details-btn btn mb-2 btn-outline-danger" formaction="./?remove_lesson_with_id=' . $_GET['id'] . '">' . $delete_text . ' löschen</button>';
                            } else {
                                echo '<button ' . $buttons_attr . ' style="float:right;" type="submit" class="lesson-details-btn btn mb-2 btn-outline-success" name="save" value="1">Erstellen</button>';
                                echo '<button ' . $buttons_attr . ' type="button" class="disabled_cursor lesson-details-btn btn mb-2 btn-outline-secondary" disabled="">Angebot löschen</button>';
                            }
                            ?>
                        </div>
                    </div>
                    <!-- end section -->
                </div>
                <!-- .col-12 -->
            </form>
            <!-- .row -->
        </div>
        <!-- .container-fluid -->
        <?php include $include_path. "/include/footer.php"; ?>
    </main>
    <!-- main -->
</div>
<!-- .wrapper -->
<script src="<?php echo $relative_path; ?>/js/jquery.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/popper.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/moment.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/bootstrap.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/simplebar.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/daterangepicker.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.stickOnScroll.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/tinycolor-min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/config.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/d3.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/topojson.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/datamaps.all.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/datamaps-zoomto.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/datamaps.custom.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/Chart.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/gauge.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.sparkline.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/apexcharts.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/apexcharts.custom.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.mask.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/select2.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.steps.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.validate.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.timepicker.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/dropzone.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/uppy.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/quill.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
<!-- Custom JS code -->
<script src="<?php echo $relative_path; ?>/js/apps.js?version=<?php echo $version; ?>"></script>
<script>
    function updateAvailability() {

        $('#availability').html('<div class="alert alert-secondary center" role="alert"><span class="fe fe-alert-octagon fe-16 mr-2"></span>Lade Slot info...</div>');


        setTimeout(func, 100);
        function func() {
            let time = $('#time').val();
            let location = $('#location').val();

            let date;
            date = $('#day').val();

            let date_type = $('#date_type').attr('date_type');
            if (date_type === "2") {
                date = $('#day2').val();
            }


            $.ajax({
                url: './check.php',
                type: 'POST',
                data: {date: date, time: time, location: location, id: <?php echo ($_GET['id'] ?? 0) ?>},
                success: function (result) {
                    $('#availability').html(result);

                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                    $('#availability').html('<div class="alert alert-warning center" role="alert"><span class="fe fe-minus-circle fe-16 mr-2"></span>Ein Fehler ist aufgetreten. Bitte überprüfe deine Internetverbindung</div>');
                }
            });
        }
    }

    $(document).ready(function() {

        let time = $('#time').val();
        let location = $('#location').val();
        $(`[time="${time}"][room="${location}"]`).addClass("preview-selected");

        $(".date_selector1").click(function(){
            $(".repeating").show();
            $(".toggle_date_input1").removeAttr("disabled", "");
            $(".toggle_date_input2").attr("disabled", "");
            $(".once").hide();
            $("#date_type").attr("date_type", "1");
        });

        $(".date_selector2").click(function(){
            $(".once").show();
            $(".toggle_date_input2").removeAttr("disabled", "");
            $(".toggle_date_input1").attr("disabled", "");
            $(".repeating").hide();
            $("#date_type").attr("date_type", "2");
        });

        $('.preview-hover').click(function() {
            const room = $(this).attr('room');
            const time = $(this).attr('time');
            $('.preview-hover').removeClass("preview-selected");
            $(this).addClass("preview-selected");
            $('#location').val(room).change();
            $('#time').val(time).change();

            enableAndDisableInputs(room, time);
        });

        $('.ql-toolbar').on('mousedown mouseup click', function() {
            $(this).closest('.form-group').find('input').val($(this).closest('.form-group').find('.ql-editor').html()).change();
        });

        $('.ql-editor').on('keyup keydown input change', function() {
            let htmlContent = $(this).html();
            htmlContent = htmlContent.replace(/<span class="ql-cursor">\s*﻿?\s*<\/span>/g, '');
            let tempDiv = $('<div>').html(htmlContent);
            tempDiv.find('*').not('br').each(function() {
                if ($(this).is(':empty')) {
                    $(this).remove();
                }
            });
            $(this).closest('.form-group').find('input').val($(this).html()).change();
        });


    });
    $('.select-multi').select2(
        {
            multiple: true,
            theme: 'bootstrap4',
        });
    $('.drgpicker').daterangepicker(
        {
            singleDatePicker: true,
            timePicker: false,
            showDropdowns: true,
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                applyLabel: "Anwenden",
                cancelLabel: "Abbrechen",
                fromLabel: "Von",
                toLabel: "bis",
                customRangeLabel: "Custom",
                weekLabel: "W",
                daysOfWeek: [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
                monthNames: [
                    "Januar",
                    "Februar",
                    "März",
                    "April",
                    "Mai",
                    "Juni",
                    "Juli",
                    "August",
                    "September",
                    "Oktober",
                    "November",
                    "Dezember"
                ],
            }
        });



    const toolbarOptions = {
        container: [
            ['bold', 'italic', 'underline', 'strike'],
            ['clean']
        ],

    }

    const quill = new Quill('#nameEditor', {
        modules: {
            "toolbar": toolbarOptions
        },
        placeholder: 'Name des Angebotes',
        theme: 'snow',
    });
    const quill2 = new Quill('#descriptionEditor', {
        modules: {
            "toolbar": toolbarOptions
        },
        placeholder: 'Wenn du dein Angebot genauer beschreiben möchtest, kannst du das einfach hier machen.',
        theme: 'snow',
    });
</script>

</body>
</html>
