<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $version, $relative_path, $create_lessons, $create_lessons_for_others, $permission_level, $permission_level_names, $webroot, $id, $pdo;

CheckPermission($create_lessons, $permission_level, $webroot . "/dashboard/?message=unauthorized");

if (isset($_GET["remove"])) {
    $sick_note_to_delete = $_GET["remove"];
    $user_permission_level = GetUserByID($_SESSION['asl_userid'], "permission_level", $pdo);

    if ($user_permission_level >= $create_lessons_for_others) {
        DeleteSickNote($sick_note_to_delete, $pdo);
        GoPageBack();
    } elseif ($_SESSION['asl_userid'] == GetSickNoteByID($sick_note_to_delete, "userid", $pdo) and $user_permission_level >= $create_lessons) {
        DeleteSickNote($sick_note_to_delete, $pdo);
        GoPageBack();
    } else {
        GoPageBack();
    }
}


if (UserStayedOnSite() AND $_SERVER["REQUEST_METHOD"] == "POST") {

    $new_assigned_user_id = ($_POST['userid'] ?? '');
    $date_range = ($_POST['date'] ?? '');
    $dates = explode(" - ", $date_range);
    $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $dates[0])));
    $end_date = date("Y-m-d", strtotime(str_replace('/', '-', ($dates[1] ?? ''))));


    if ($new_assigned_user_id == "" or $permission_level < $create_lessons_for_others) {
        $new_assigned_user_id = $_SESSION['asl_userid'];
    }

    if (isset($_POST["update"])) {

        $id = $_POST["update"];
        // Update Lesson
        UpdateOrInsertSickNote("update", $pdo, $id, $new_assigned_user_id, $start_date, $end_date);
        Redirect("../");


    } elseif (($_POST['save'] ?? 0) == "1") {

        UpdateOrInsertSickNote("create", $pdo, "", $new_assigned_user_id, $start_date, $end_date);
        Redirect("../");
    }

}

//Get lesson
if (isset($_GET['id'])) {
    $sick_note_id = $_GET['id'];

    if ($permission_level < $create_lessons_for_others and ($_SESSION['asl_userid'] ?? '') != GetSickNoteByID($sick_note_id, "userid", $pdo)) {
        Redirect("../?message=unauthorized");
    }

    if (GetSickNoteByID($sick_note_id, "available", $pdo)) {

        $sick_note_details['userid'] = GetSickNoteByID($sick_note_id, "userid", $pdo);
        $sick_note_details['date-start'] = GetSickNoteByID($sick_note_id, "start", $pdo);
        $sick_note_details['date-end'] = GetSickNoteByID($sick_note_id, "end", $pdo);

        $sick_note_details['date1'] = date("d/m/Y", strtotime($sick_note_details['date-start']));
        $sick_note_details['date2'] = date("d/m/Y", strtotime($sick_note_details['date-end']));

        $sick_note_details['date'] = $sick_note_details['date1'] . " - " . $sick_note_details['date2'];


    } else {
        Redirect("../");
    }

}

if (isset($sick_note_details['userid'])) $userArray[] = $sick_note_details['userid'];
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


      <title>Krankmeldungen Verwalten</title>


      <!-- Simple bar CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
      <!-- Fonts CSS -->
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
   </head>
   <body class="vertical light">
      <div class="wrapper">
          <?php
          $keep_pdo = true;
          require $include_path . "/include/nav.php";
          ?>
         <main role="main" class="main-content">
             <form action="" method="post">
                 <div class="container-fluid">
                     <div class="row justify-content-center">

                         <div class="col-12">
                             <h2 class="page-title">
                                 <?php
                                 if(isset($_GET['id'])) {
                                     echo "Krankmeldung bearbeiten";
                                 } else {
                                     echo "Krankmeldung erstellen";
                                 }
                                 ?>
                             </h2>
                             <div class="card shadow mb-4">
                                 <div class="card-header">
                                     <strong class="card-title">Krankmeldung Details</strong>
                                 </div>
                                 <div class="card-body">
                                     <div class="row">
                                         <div class="col-md-12">
                                             <div class="form-group mb-3">
                                                 <label for="simpleinput">Zeitraum</label>

                                                 <div class="input-group">

                                                     <div class="input-group-prepend">
                                                         <span class="input-group-text"><span class="fe fe-16 fe-calendar"></span></span>
                                                     </div>
                                                     <label for="date"></label>
                                                     <input id="date" name="date" type="text" class="form-control drgpicker toggle_date_input2" value="
                                                      <?php
                                                     if(isset($sick_note_details['date'])) {
                                                         echo $sick_note_details['date'];
                                                     } else {
                                                         echo date("d/m/Y");
                                                         echo " - ";
                                                         echo date("d/m/Y");
                                                     }
                                                     ?>
                                                      " aria-describedby="button-addon2">

                                                 </div>
                                             </div>
                                         </div>
                                         <!-- /.col -->

                                     </div>
                                 </div>
                             </div>
                             <!-- / .card -->
                             <div class="row">
                                 <!-- /.col -->

                                 <div class="col-md-12 mb-4">
                                     <div class="card shadow">
                                         <div class="card-header">
                                             <strong class="card-title">Wer ist Krank</strong>
                                         </div>
                                         <div class="card-body">
                                             <div class="form-group">
                                                 <label for="userid"></label>
                                                 <select id="userid" name="userid" class="form-control select" <?php
                                                 if($permission_level < $create_lessons_for_others) {
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
                             </div>
                             <!-- .row -->
                         </div>
                         <div class="col-md-12 mb-4">
                             <br>
                             <button type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zurück</button>
                             <?php
                             if(isset($_GET['id'])) {
                                 echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="update" value="' . $_GET['id'] . '">Aktualisieren</button>';
                                 echo '<button type="button summit" class="btn mb-2 btn-outline-danger" formaction="./?remove=' . $_GET['id'] . '">Krankmeldung löschen</button>';
                             } else {
                                 echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="save" value="1">Erstellen</button>';
                                 echo '<button type="button" class="btn mb-2 btn-outline-secondary" disabled="">Krankmeldung löschen</button>';
                             }
                             ?>
                         </div>
             </form>
            <!-- .container-fluid -->
            <?php include $include_path . "/include/footer.php"; ?>
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
      <script src="<?php echo $relative_path; ?>/js/apps.js?version=<?php echo $version; ?>"></script>
      <script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
      <!-- Custom JS code -->
      <script>
          $(document).ready(function(){
          });
          $('.select').select2(
              {
                  theme: 'bootstrap4',
              });
          $('.drgpicker').daterangepicker(
              {
                  singleDatePicker: false,
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
      </script>

   </body>
</html>
