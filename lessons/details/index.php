<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
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
      <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
   </head>
   <body class="vertical light">
      <div class="wrapper">
         <?php




         $keep_pdo = true;
         require $include_path. "/include/nav.php";

         if($permission_level < $create_lessons) {
             GoPageBack("?message=unauthorized");
             die();
         }

         if (isset($_GET["remove_lesson_with_id"])) {
             $lesson_to_delete = $_GET["remove_lesson_with_id"];
             if ($permission_level > $create_lessons_for_others) {
                 DeleteLesson($lesson_to_delete, $pdo);
                 GoPageBack("");
             } elseif ($id == GetLessonByID($lesson_to_delete, "userid", $pdo)) {
                 DeleteLesson($lesson_to_delete, $pdo);
                 GoPageBack("");
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
             $new_notes = ($_POST['notes'] ?? '');
             $new_assigned_user_id = ($_POST['creator'] ?? '');


             if($new_assigned_user_id == "" OR $permission_level < $create_lessons_for_others) {
                 $new_assigned_user_id = $id;
             }

             // Update Lesson
             if (isset($_POST["update_lesson_with_id"])) {

                 update_or_insert_lesson("update", $pdo, $_POST["update_lesson_with_id"],
                     $date_type,
                     $new_date,
                     $new_name,
                     $new_description,
                     $new_location,
                     $new_time,
                     $new_notes,
                     $new_assigned_user_id
                 );

                 Redirect("../");

                 // Create Lesson
             } elseif (($_POST['save'] ?? 0) == "1") {

                 update_or_insert_lesson("create", $pdo, "",
                     $date_type,
                     $new_date,
                     $new_name,
                     $new_description,
                     $new_location,
                     $new_time,
                     $new_notes,
                     $new_assigned_user_id
                 );
                 Redirect("../");
             }

         }

         //Get lesson
         if (isset($_GET['id'])) {
             $lesson_id = $_GET['id'];



             if (GetLessonByID($lesson_id, "available", $pdo)) {

                 $lesson_details['name'] = GetLessonByID($lesson_id, "name", $pdo);
                 $lesson_details['description'] = GetLessonByID($lesson_id, "description", $pdo);
                 $lesson_details['location'] = GetLessonByID($lesson_id, "location", $pdo);
                 $lesson_details['time'] = GetLessonByID($lesson_id, "time", $pdo);
                 $lesson_details['notes'] = GetLessonByID($lesson_id, "notes", $pdo);

                 $lesson_details['creator'] = GetInfomationOfUser(GetLessonByID($lesson_id, "userid", $pdo), "name", $pdo);


                 $lesson_details['date-raw'] = GetLessonByID($lesson_id, "date", $pdo);

                 if (str_contains($lesson_details['date-raw'], "-")) {
                     $lesson_details['date'] = date("d-m-Y", strtotime($lesson_details['date-raw']));
                 } else {
                     $lesson_details['date'] = $lesson_details['date-raw'];
                 }
             } else {
                 Redirect("../");
             }

         }
?>

         <main role="main" class="main-content">
            <div class="container-fluid">
               <div class="row justify-content-center">
                   <?php
                   if (!isset($_GET['id'])) {
                       echo '<div id="availability" class="center2 availability">';
                       echo '<div class="alert alert-success center" role="alert">';
                       echo '<span class="fe fe-alert-octagon fe-16 mr-2"></span>Dein Angebot kann dort stattfinden.';
                       echo '</div>';
                       echo '</div>';
                   }
                   ?>
                  <form action="./" method="post">
                     <div class="col-12">
                         <h2 class="page-title">
                             <?php
                             if(isset($_GET['id'])) {
                                 echo "Angebot bearbeiten";
                             } else {
                                 echo "Angebot erstellen";
                             }
                             ?>
                         </h2>
                         <p class="text-muted"> Hier kannst Du ganz einfach Unterrichtsangebote erstellen. Die Unterrichtsangebote können ganz einfach an Deine Bedürfnisse angepasst werden, sodass Du das perfekte Lernangebot anbieten kannst.</p>
                         <div class="card shadow mb-4">
                           <div class="card-header">
                              <strong class="card-title">Angebot details</strong>
                           </div>
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="simpleinput">Name des Angebotes</label>
                                       <input name="name" type="text" id="simpleinput" class="form-control" placeholder="Name des Angebotes" maxlength="20" value="<?php if(isset($lesson_details['name'])) { echo $lesson_details['name']; }?>" required>
                                       </input>
                                    </div>
                                 </div>
                                 <!-- /.col -->
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="helping">Weitere Beschreibung</label>
                                       <input name="description" type="text" id="helping" class="form-control" placeholder="Wenn du dein Angebot genauer beschreiben möchtest kannst du das einfach hier machen." maxlength="30" value="<?php if(isset($lesson_details['description'])) { echo $lesson_details['description']; }?>" required>
                                    </div>
                                 </div>
                              </div>
                           </div>
                         </div>
                         <!-- / .card -->
                         <div class="row">
                             <div class="col-md-6 mb-4">
                                 <div class="card shadow">
                                     <div class="card-body">
                                         <div class="form-group mb-3">
                                             <label for="custom-select">Ort des Angebotes bzw. Art</label>
                                             <select name="location" class="form-control dropdown" id="location" <?php if (!isset($_GET['id'])) { echo 'onchange="updateAvailability()"';} ?>>
                                                 <?php
                                                 $selected_location = array();
                                                 if(isset($lesson_details['location'])) {
                                                     $selected_location[$lesson_details['location']] = "selected";
                                                 }
                                                 $count = 0;
                                                 foreach ($room_names as $value => $i) {
                                                     $count++;
                                                     echo '<option value="' . $value . '" ' . ($selected_location[$count] ?? '') . '>' . $i . '</option>';
                                                 }
                                                 ?>

                                             </select>
                                         </div>
                                     </div>
                                     <!-- /.card-body -->
                                 </div>
                                 <!-- /.card -->
                             </div>
                           <!-- /.col -->
                           <div class="col-md-6 mb-4">
                              <div class="card shadow">
                                 <div class="card-body">
                                    <div class="form-group mb-3">
                                       <label for="custom-select">Zeitpunkt des Angebotes</label>
                                       <select name="time" class="form-control" id="time" <?php if (!isset($_GET['id'])) { echo 'onchange="updateAvailability()"';} ?>>
                                          <?php
                                          $selected_time = array();
                                          if(isset($lesson_details['time'])) {
                                              $selected_time[$lesson_details['time']] = "selected";
                                          }
                                          $count = 0;
                                          foreach ($times as $value => $i) {
                                              $count++;
                                              echo '<option value="' . $value . '" ' . ($selected_time[$count] ?? '') . '>' . $i . '</option>';
                                          }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <!-- /.card-body -->
                              </div>
                              <!-- /.card -->
                           </div>
                           <!-- /.col -->
						   
						   
						   
                           <div class="col-md-6 mb-4">
                              <div class="card shadow">
                                 <div class="d-flex flex-row tab-icon">
                                    <div class="nav flex-column nav-pills" aria-orientation="vertical">
									
										<?php 
										    $date_type = array();
                                            if(isset($lesson_details['date-type'])) {
											    $date_type[$lesson_details['date-type']] = "active";
										    } else {
										    	$date_type[1] = "active";
										    }
										?>
										
										<a class="date_selector1 nav-link py-3 <?php if(isset($date_type[1])) { echo $date_type[1]; }?>" data-toggle="pill" aria-selected="true"><span class="fe fe-16 fe-repeat"></span></a>
										<a class="date_selector2 nav-link py-3 <?php if(isset($date_type[2])) { echo $date_type[2]; }?>" data-toggle="pill" aria-selected="false"><span class="fe fe-16 fe-calendar"></span></a>
                                    </div>
									
                                          <div class="form-group mb-3 full">
                                             <div class="card-body">

                                                <label for="custom-select">Zeitpunkt des Angebotes</label>
												
												<div class="repeating" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") echo "style='display: none;'"; ?>>
                                                <div class="input-group">
                                                   <div class="input-group-append">
                                                      <div class="input-group-text" id="button-addon-date"><span class="fe fe-repeat fe-16"></span></div>
                                                   </div>
                                                   <select id="day" <?php if (!isset($_GET['id'])) { echo 'onchange="updateAvailability()"';} ?> name="date-repeat" class="form-control toggle_date_input1 dropdown" <?php if($lesson_details['date-type'] == "2") { echo "disabled"; } ?> id="type-select">
                                                      <?php if($lesson_details['date-type'] == "1") {
                                                         $selected_date = array();
                                                         $selected_date[$lesson_details['date']] = "selected";
                                                         }?>
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
                                                   <input id="day2" <?php if (!isset($_GET['id'])) { echo 'onchange="updateAvailability2()"';} ?> name="date" type="text" class="form-control drgpicker toggle_date_input2" <?php if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "1" OR !isset($lesson_details['date-type'])) { echo "disabled"; } ?> id="date-input1" value="
                                                      <?php
                                                         if(isset($lesson_details['date-type']) AND $lesson_details['date-type'] == "2") {
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
                                       <label for="custom-select">Wer macht diese Angebot?</label>
                                       <select name="creator" class="form-control select2" <?php
                                          if($permission_level < $create_lessons_for_others) {
                                          	echo "disabled";
                                          }?>>
                                          <option value="<?php echo $id; ?>" selected><?php
                                             echo $vorname;
                                             echo " ";
                                             echo $nachname;
                                             ?> (Du selbst)</option>
                                           <?php

                                           if ($permission_level >= $create_lessons_for_others) {
                                               $get_usernames = "SELECT * FROM users ORDER BY permission_level";
                                               foreach ($pdo->query($get_usernames) as $other_users) {
                                                   if ($id != $other_users['id']) {
                                                       echo "<option value='";
                                                       echo $other_users['id'];
                                                       echo "'>";
                                                       echo $other_users['vorname'] . " " . $other_users['nachname'];
                                                       echo "</option>";
                                                   }
                                               }
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
                                        <strong class="card-title">Zusätsliche Infomationen (Sind nur hier sichtbar und werden nicht auf dem Plan gezeigt)</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input name="notes" class="form-control form-control-lg" type="text" placeholder="Notizen" maxlength="255" value="<?php if(isset($lesson_details['notes'])) { echo $lesson_details['notes']; }?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-12 mb-4">
                              <button type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zurück</button>
                              <?php
                                 if(isset($_GET['id'])) {
                                     echo '<button type="button summit" class="btn mb-2 btn-outline-danger" formaction="./?remove_lesson_with_id=' . $_GET['id'] . '">Angebot Löschen</button>';
                                     echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Aktualisieren</button>';
                                 } else {
                                     echo '<button type="button" class="btn mb-2 btn-outline-secondary" disabled="">Angebot Löschen</button>';
                                     echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="save" value="1">Erstellen</button>';
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
      <!-- Custom JS code -->
      <script>
          function updateAvailability() {
              var date = $('#day').val();
              var time = $('#time').val();
              var location = $('#location').val();
              $.ajax({
                  url: './check.php',
                  type: 'GET',
                  data: {date: date, time: time, location: location},
                  success: function(result) {
                      $('#availability').html(result);
                  },
                  error: function(xhr, status, error) {
                      console.log("Error: " + error);
                  }
              });
          }
          function updateAvailability2() {
              var date = $('#day2').val();
              var time = $('#time').val();
              var location = $('#location').val();

              console.log("Date: " + date);
              console.log("Time: " + time);
              console.log("Location: " + location);
              $.ajax({
                  url: './check.php',
                  type: 'GET',
                  data: {date: date, time: time, location: location},
                  success: function(result) {
                      $('#availability').html(result);
                  },
                  error: function(xhr, status, error) {
                      console.log("Error: " + error);
                  }
              });
          }
          $(document).ready(function(){
              updateAvailability();
              $(".date_selector1").click(function(){
                  $(".repeating").show();
                  $(".toggle_date_input1").removeAttr("disabled", "");
                  $(".toggle_date_input2").attr("disabled", "");
                  $(".once").hide();
              });

              $(".date_selector2").click(function(){
                  $(".once").show();
                  $(".toggle_date_input2").removeAttr("disabled", "");
                  $(".toggle_date_input1").attr("disabled", "");
                  $(".repeating").hide();
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
                  locale:
                      {
                          format: 'DD/MM/YYYY'
                      }
              });
      </script>
   </body>
</html>
