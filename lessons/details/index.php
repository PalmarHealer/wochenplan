<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

CheckPermission($create_lessons, $permission_level, "../?message=unauthorized");
?>
<!doctype html>
<html lang="de">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico">


      <title>Angebot Verwalten</title>


      <!-- Simple bar CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css">
      <!-- Fonts CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/abel.css">
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/overpass.css">
      <!-- Icons CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/feather.css">
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/dataTables.bootstrap4.css">
      <!-- Date Range Picker CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/daterangepicker.css">
      <!-- App CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css" id="lightTheme">
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css" id="darkTheme" disabled>
      <!-- Custom CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css">
      <!-- Site Css -->
       <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/select2.css">
       <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/coloris.min.css">
   </head>
   <body class="vertical light">
      <div class="wrapper">
         <?php




         $keep_pdo = true;
         $lesson_disabled = false;
         $disable_enable_text = "deaktivieren";
         require $include_path. "/include/nav.php";

         $return_to = ($_POST['return_to'] ?? null);

         if (isset($_GET["remove_lesson_with_id"])) {
             $lesson_to_delete = $_GET["remove_lesson_with_id"];
             $user_permission_level = GetUserByID($_SESSION['asl_userid'], "permission_level", $pdo);
             if ($user_permission_level >= $create_lessons_for_others) {
                 DeleteLesson($lesson_to_delete, $pdo);
                 Redirect($return_to);
             } elseif ($_SESSION['asl_userid'] == GetLessonInfoByID($lesson_to_delete, "userid", $pdo) AND $user_permission_level >= $create_lessons) {
                 DeleteLesson($lesson_to_delete, $pdo);
                 Redirect($return_to);
             } else {
                 GoPageBack("");
             }
         }
         if(isset($old_url) AND $new_url = $old_url) {


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

             $new_name = ($_POST['name'] ?? '');
             $new_description = ($_POST['description'] ?? '');
             $new_location = ($_POST['location'] ?? '');
             $new_time = ($_POST['time'] ?? '');
             $new_box_color = ($_POST['box-color'] ?? '#f6e9e6');
             $new_notes = ($_POST['notes'] ?? '');
             $new_assigned_user_id = ($_POST['creator'] ?? '');


             $dub_id = ($_GET['dub_id'] ?? null);
             $tmp_plan_date = ($_POST['plan_date'] ?? '');
             $plan_date = date("Y-m-d", strtotime($tmp_plan_date));

             if($new_assigned_user_id == "" OR $permission_level < $create_lessons_for_others) {
                 $new_assigned_user_id = $id;
             }

             if (isset($dub_id)) {

                 UpdateOrInsertLesson("create", $pdo, "",
                     "2",
                     $plan_date,
                     $new_name,
                     $new_description,
                     $new_location,
                     $new_time,
                     $new_box_color,
                     $new_notes,
                     $new_assigned_user_id,
                     $_SESSION['asl_userid']
                 );
                 Redirect($return_to);
             }
             // Update Lesson
             if (isset($_POST["update_lesson_with_id"])) {

                 UpdateOrInsertLesson("update", $pdo, $_POST["update_lesson_with_id"],
                     $date_type,
                     $new_date,
                     $new_name,
                     $new_description,
                     $new_location,
                     $new_time,
                     $new_box_color,
                     $new_notes,
                     $new_assigned_user_id,
                     $_SESSION['asl_userid']
                 );

                 Redirect($return_to);

                 // Create Lesson
             } elseif (($_POST['save'] ?? 0) == "1") {

                 UpdateOrInsertLesson("create", $pdo, "",
                     $date_type,
                     $new_date,
                     $new_name,
                     $new_description,
                     $new_location,
                     $new_time,
                     $new_box_color,
                     $new_notes,
                     $new_assigned_user_id,
                     $_SESSION['asl_userid']
                 );
                 Redirect($return_to);
             }

         }

         if (isset($_GET['disable_enable_lesson'])) {

             if (GetLessonInfoByID($_GET['disable_enable_lesson'], "disabled", $pdo)) {

                 EnableLesson($_SESSION['asl_userid'], $_GET['disable_enable_lesson'], $pdo);
             } else {

                 DisableLesson($_SESSION['asl_userid'], $_GET['disable_enable_lesson'], $pdo);
             }

             Redirect($return_to);

         }

         //Get lesson
         if (isset($_GET['id'])) {
             $lesson_id = $_GET['id'];

             if($permission_level < $create_lessons_for_others AND ($_SESSION['asl_userid'] ?? '') != GetLessonInfoByID($lesson_id, "userid", $pdo)) {
                 Redirect("../?message=unauthorized");
             }

             if (GetLessonInfoByID($lesson_id, "available", $pdo)) {

                 $lesson_details['name'] = GetLessonInfoByID($lesson_id, "name", $pdo);
                 $lesson_details['description'] = GetLessonInfoByID($lesson_id, "description", $pdo);
                 $lesson_details['location'] = GetLessonInfoByID($lesson_id, "location", $pdo);
                 $lesson_details['time'] = GetLessonInfoByID($lesson_id, "time", $pdo);

                 $lesson_details['notes'] = GetLessonInfoByID($lesson_id, "notes", $pdo);

                 $lesson_details['box-color'] = GetLessonInfoByID($lesson_id, "box-color", $pdo);


                 $lesson_details['userid'] = GetLessonInfoByID($lesson_id, "userid", $pdo);
                 $lesson_details['creator'] = GetUserByID($lesson_details['userid'], "name", $pdo);


                 $lesson_details['date-raw'] = GetLessonInfoByID($lesson_id, "date", $pdo);

                 if (GetLessonInfoByID($lesson_id, "disabled", $pdo)) {
                     $disable_enable_text = "aktivieren";
                     $lesson_disabled = true;
                 }

                 if (str_contains($lesson_details['date-raw'], "-")) {
                     $lesson_details['date-type'] = 2;
                     $lesson_details['date'] = date("d/m/Y", strtotime($lesson_details['date-raw']));
                 } else {
                     $lesson_details['date-type'] = 1;
                     $lesson_details['date'] = $lesson_details['date-raw'];
                 }
             } else {
                 Redirect($return_to);
             }

         }
?>

         <main role="main" class="main-content">
            <div class="container-fluid">
               <div class="row justify-content-center">
                   <div id="availability" class="center2 availability sticky-using-fixed">
                       <div class="alert alert-secondary center" role="alert">
                           <span class="fe fe-alert-octagon fe-16 mr-2"></span>Lade Slot info...
                       </div>
                   </div>
                  <form action="./" method="post">
                      <label>
                          <input hidden type="text" name="return_to" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                      </label>
                      <label>
                          <input hidden type="text" name="plan_date" value="<?php echo ($_GET['date'] ?? null); ?>">
                      </label>
                      <div class="col-12">
                             <?php
                             if(isset($_GET['id'])) {
                                 echo "<h2 class='page-title'>Angebot bearbeiten</h2>";
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
                                       <input name="name" type="text" id="name" class="form-control" placeholder="Name des Angebotes" maxlength="30" value="<?php echo ($lesson_details['name'] ?? '');?>" required>
                                       </input>
                                    </div>
                                 </div>
                                 <!-- /.col -->
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="helping">Weitere Beschreibung</label>
                                       <input name="description" type="text" id="helping" class="form-control" placeholder="Wenn du dein Angebot genauer beschreiben möchtest, kannst du das einfach hier machen." maxlength="60" value="<?php echo ($lesson_details['description'] ?? '');?>">
                                    </div>
                                 </div>
                              </div>
                           </div>
                         </div>
                         <!-- / .card -->
                         <div class="row">


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

                             <div class="col-md-12 mb-4">
                                 <div class="card shadow">
                                     <div class="card-header">
                                         <table class="full tg-small-preview">
                                             <colgroup>
                                                 <col class="piece"/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                                 <col/>
                                             </colgroup>
                                             <thead>
                                             <tr class="center">
                                                 <th class="color-3 no_border">
                                                     <br>
                                                     <br>
                                                 </th>
                                                 <th class="color-1 preview-hover" colspan="4" time="13" room="10"></th>
                                                 <th class="color-3 white_text modt text-left" colspan="5">

                                                 </th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                             <tr class="name-badge center">
                                                 <td class="color-6 db_text rooms"><b class="bold">Zeiten l/ll</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Raum 1</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Freiarbeit</b></td>
                                                 <td class="color-6 db_text rooms"><b class="bold">Zeiten ll-lV</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Raum 2</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Raum 3 (HS)</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Raum 4 (RS)</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Gesprächsraum</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">SZ/Praxisber.</b></td>
                                                 <td class="color-1 db_text rooms"><b class="bold">Sport</b></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     8:00 – 9:00<br/>
                                                     <b class="bold">Morgenband</b>
                                                 </td>
                                                 <td class="color-2 db_text" colspan="2"></td>

                                                 <td class="color-6 no_border">
                                                     8:00 – 9:00<br/>
                                                     <b class="bold">Morgenband</b>
                                                 </td>

                                                 <td class="color-2 preview-hover center" time="1" room="10" colspan="6"></td>
                                             </tr>
                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     9:00 – 9:30<br/>
                                                     <b class="bold">Morgenkreise</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="2" room="1"></td>
                                                 <td class="color-1 preview-hover" time="2" room="9"></td>

                                                 <td class="color-6 no_border" rowspan="2">
                                                     9:00 - 10:00<br/>
                                                     <b class="bold">Offene Räume</b>
                                                 </td>
                                                 <td class="color-2 preview-hover" time="6" room="2" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="6" room="3" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="6" room="4" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="6" room="5" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="13" room="6" rowspan="5"></td>
                                                 <td class="color-2 preview-hover" time="13" room="7" rowspan="5"></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border" rowspan="2">
                                                     9:30 – 10:30<br/>
                                                     <b class="bold">Angebot 1</b>
                                                 </td>
                                                 <td class="color-2 preview-hover" time="3" room="1" rowspan="2"></td>
                                                 <td class="color-1 preview-hover" time="3" room="9" rowspan="2"></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     10:00 – 10:30<br/>
                                                     <b class="bold">Morgenkreise</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="7" room="2"></td>
                                                 <td class="color-2 preview-hover" time="7" room="3"></td>
                                                 <td class="color-2 preview-hover" time="7" room="4"></td>
                                                 <td class="color-2 preview-hover" time="7" room="5"></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     <b class="bold">Räum-Pause </b>
                                                 </td>

                                                 <td class="color-2"></td>
                                                 <td class="color-1"></td>

                                                 <td class="color-6 no_border" rowspan="2">
                                                     10:30 – 12:00<br/>
                                                     <b class="bold">Großes Band</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="8" room="2" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="8" room="3" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="8" room="4" rowspan="2"></td>
                                                 <td class="color-2 preview-hover" time="8" room="5" rowspan="2"></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     10:45 – 11:45<br/>
                                                     <b class="bold">Angebot 2</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="4" room="1"></td>
                                                 <td class="color-1 preview-hover" time="4" room="9"></td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     12:00 – 13:00<br/>
                                                     <b class="bold">Mittagspause</b>
                                                 </td>

                                                 <td class="color-4 preview-hover no_border center2" colspan="9" time="14" room="10"><b class="bold">Mittagessen</b>
                                                 </td>
                                             </tr>


                                             <tr class="">

                                                 <td class="color-6 no_border">
                                                     13:00 – 14:30<br/>
                                                     <b class="bold">Nachmittagsband</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="5" room="1"></td>
                                                 <td class="color-1 preview-hover" time="5" room="9"></td>

                                                 <td class="color-6 no_border">
                                                     13:00 – 14:30<br/>
                                                     <b class="bold">Nachmittagsband</b>
                                                 </td>

                                                 <td class="color-2 preview-hover" time="9" room="2"></td>
                                                 <td class="color-2 preview-hover" time="9" room="3"></td>
                                                 <td class="color-2 preview-hover" time="9" room="4"></td>
                                                 <td class="color-2 preview-hover" time="9" room="5"></td>
                                                 <td class="color-2 preview-hover" time="9" room="6"></td>
                                                 <td class="color-2 preview-hover" time="9" room="7"></td>
                                             </tr>


                                             <tr class="">
                                                 <td class="white-col" rowspan="3"></td>
                                                 <td class="white-col" rowspan="3"></td>
                                                 <td class="white-col" rowspan="3"></td>

                                                 <td class="color-6 no_border" rowspan="2">
                                                     14:30 – 15:00<br/>
                                                     <b class="bold">Putzen</b>
                                                 </td>
                                                 <td class="color-5 preview-lighthover" time="12" room="10" colspan="8"></td>
                                             </tr>

                                             <tr class="">
                                                 <td class="color-5 preview-lighthover" time="12" room="11" colspan="2"></td>
                                                 <td class="color-5 preview-lighthover" time="12" room="12" colspan="2"></td>
                                                 <td class="color-5 preview-lighthover" time="12" room="13" colspan="2"></td>
                                             </tr>


                                             <tr class="">
                                                 <td class="color-6 no_border">
                                                     15:00 – 16:00<br/>
                                                     <b class="bold">Spätes Band</b>
                                                 </td>
                                                 <td class="color-2 preview-hover" time="10" room="2"></td>
                                                 <td class="color-2 preview-hover" time="10" room="3"></td>
                                                 <td class="color-2 preview-hover" time="10" room="4"></td>
                                                 <td class="color-2 preview-hover" time="10" room="5"></td>
                                                 <td class="color-2 preview-hover" time="10" room="6"></td>
                                                 <td class="color-2 preview-hover" time="10" room="7"></td>
                                             </tr>
                                             </tbody>
                                         </table>

                                     </div>
                                 </div>
                             </div>



                             <div class="col-md-12">
                                 <div class="card shadow mb-4">
                                     <div class="card-body">
                                         <div class="form-group mb-3">
                                             <label for="color-picker">Farbe</label>
                                             <input id="color-picker" class="color_picker form-control" type="text" name="box-color" value="<?php echo ($lesson_details['box-color']?? '#f6e9e6'); ?>" data-coloris>
                                         </div>
                                     </div> <!-- /.card-body -->
                                 </div> <!-- /.card -->
                             </div>

                             <?php
                             $date_type = array();

                             if (isset($_GET['date'])) {
                                 $lesson_details['date-type'] = 2;
                             }
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
									
                                          <div class="form-group mb-3 full">
                                             <div class="card-body">

                                                <label for="day">Tag des Angebotes</label>
												
												<div class="repeating" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") echo "style='display: none;'"; ?>>
                                                <div class="input-group">
                                                   <div class="input-group-append">
                                                      <div class="input-group-text" id="button-addon-date"><span class="fe fe-repeat fe-16"></span></div>
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
                                                   <div class="input-group-append">
                                                      <div class="input-group-text" id="button-addon-date"><span class="fe fe-calendar fe-16"></span></div>
                                                   </div>
                                                   <input id="day2" onchange="updateAvailability()" name="date" type="text" class="form-control drgpicker toggle_date_input2" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "1" OR !isset($lesson_details['date-type'])) { echo "disabled"; } ?> id="date-input1" value="
                                                      <?php
                                                         if(isset($_GET['date']) OR isset($_POST['date'])) {
                                                             $date_tmp = ($_GET['date'] ?? $_POST['date']);
                                                             echo date("d/m/Y", strtotime($date_tmp));
                                                         } elseif (isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") {
                                                         	echo $lesson_details['date'];
                                                         } else {
                                                             echo date("d/m/Y");

                                                         }
                                                         ?>
                                                      " aria-describedby="button-addon2">
                                                   </input>
                                                </div>
                                             </div>
                                             </div>
                                          </div>
                                 </div>
                              </div>
							  <!-- /.card -->
                           </div>
						   <!-- /.col -->

						   
                           <div class="col-md-6 mb-4">
                              <div class="card shadow">
                                 <div class="card-body">
                                    <div class="form-group mb-3">
                                       <label for="creator">Wer macht dieses Angebot?</label>
                                       <select id="creator" name="creator" class="form-control select2" <?php
                                          if($permission_level < $create_lessons_for_others) {
                                          	echo "disabled";
                                          }
                                          echo ">";

                                           if ($permission_level >= $create_lessons) {
                                                   GetAllUsersAndPrintForSelect($pdo, $id,  ($lesson_details['userid'] ?? $id));

                                               $pdo = null;
                                           }
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
                                            <input name="notes" class="form-control form-control-lg" type="text" placeholder="Notizen" maxlength="255" value="<?php echo ($lesson_details['notes'] ?? '');?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-12 mb-4">
                              <button type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zurück</button>
                              <?php
                                 if(isset($_GET['id'])) {

                                     if (isset($_GET['date']) AND $lesson_details['date-type'] == "1") {
                                         echo '<button style="float:right;" type="button summit" class="lesson-details-btn btn mb-2 btn-outline-success" name="date"'; if (isset($_GET['date'])) { echo " value=" . $_GET['date'];} echo ' formaction="./?dub_id=' . $_GET['id'] . '">Aktualisieren</button>';
                                         echo '<button style="float:right;" type="button summit" class="lesson-details-btn btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Angebot für alle Wochen Aktualisieren</button>';

                                     } else {
                                         echo '<button style="float:right;" type="button summit" class="lesson-details-btn btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Aktualisieren</button>';

                                     }

                                     echo '<button type="button summit" class="lesson-details-btn btn mb-2 btn-outline-warning" formaction="./?disable_enable_lesson=' . $_GET['id'] . '">Angebot ' . $disable_enable_text . '</button>';
                                     echo '<button type="button summit" class="lesson-details-btn btn mb-2 btn-outline-danger" formaction="./?remove_lesson_with_id=' . $_GET['id'] . '">Angebot löschen</button>';
                                 } else {
                                     echo '<button style="float:right;" type="button summit" class="lesson-details-btn btn mb-2 btn-outline-success" name="save" value="1">Erstellen</button>';
                                     echo '<button type="button" class="lesson-details-btn btn mb-2 btn-outline-secondary" disabled="">Angebot löschen</button>';
                                 }
                                 ?>
                           </div>
                        </div>
                        <!-- end section -->
                     </div>
                     <!-- .col-12 -->
                  </form>
               </div>
               <!-- .row -->
            </div>
            <!-- .container-fluid -->
            <?php include $include_path. "/include/footer.php"; ?>
         </main>
         <!-- main -->
      </div>
      <!-- .wrapper -->
      <script src="<?php echo $relative_path; ?>/js/jquery.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/popper.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/moment.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/bootstrap.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/simplebar.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/daterangepicker.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.stickOnScroll.js"></script>
      <script src="<?php echo $relative_path; ?>/js/tinycolor-min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/config.js"></script>
      <script src="<?php echo $relative_path; ?>/js/d3.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/topojson.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/datamaps.all.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/datamaps-zoomto.js"></script>
      <script src="<?php echo $relative_path; ?>/js/datamaps.custom.js"></script>
      <script src="<?php echo $relative_path; ?>/js/Chart.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/gauge.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.sparkline.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/apexcharts.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/apexcharts.custom.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.mask.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/select2.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.steps.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.validate.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/jquery.timepicker.js"></script>
      <script src="<?php echo $relative_path; ?>/js/dropzone.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/uppy.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/quill.min.js"></script>
      <script src="<?php echo $relative_path; ?>/js/apps.js"></script>
      <script src="<?php echo $relative_path; ?>/js/coloris.min.js"></script>
      <!-- Custom JS code -->
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

          function enableAndDisableInputs(room, time, ) {

              let description = $("#helping");
              let color_picker = $("#color-picker");
              let name = $("#name");

              name.attr("maxlength", "30");
              description.removeAttr("disabled");
              color_picker.removeAttr("disabled");

              if (room === "10" && time === "13") {
                  description.attr("disabled", true);
                  color_picker.attr("disabled", true);
              } else if (room === "10" && time === "14") {
                  color_picker.attr("disabled", true);
              } else if (room === "9") {
                  if (time === "2" || time === "3" || time === "4" || time === "5") {
                      description.attr("disabled", true);
                  }
              } else if (time === "12") {
                  color_picker.attr("disabled", true);
                  name.attr("maxlength", "50");
                  if (room === "11" || room === "12" || room === "13") {
                      description.attr("disabled", true);
                  }
              }
          }

          $(document).ready(function(){

              let time = $('#time').val();
              let location = $('#location').val();
              $(`[time="${time}"][room="${location}"]`).addClass("preview-selected");

              enableAndDisableInputs(location, time);

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

              $('.preview-hover, .preview-lighthover').click(function() {
                  const room = $(this).attr('room');
                  const time = $(this).attr('time');
                  $('.preview-hover, .preview-lighthover').removeClass("preview-selected");
                  $(this).addClass("preview-selected");
                  $('#location').val(room).change();
                  $('#time').val(time).change();

                  enableAndDisableInputs(room, time);
              });


              Coloris.setInstance('.color_picker', {
                  //default, large, polaroid, pill
                  theme: 'pill',

                  themeMode: 'light',

                  margin: 5,

                  format: 'hex',
                  alpha: false,
                  swatches: [
                      '#f6e9e6',
                      '#ecd3cd',
                      '#e3bdb4',
                      '#d09182',
                      '#dee5e6',
                      '#e5f4d4',
                      '#cdcdff',
                      '#f8e9be',
                      '#ffca39',
                      '#ff3939',
                  ]


              });
          });

          $('.select2').select2(
              {
                  theme: 'bootstrap4',
              });
          $('.select2-multi').select2(
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

      </script>

   </body>
</html>
