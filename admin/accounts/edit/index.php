<?php
$include_path = __DIR__ . "/../../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");
?>
<!doctype html>
<html lang="de">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico">


      <title>Benutzer bearbeiten</title>


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


         if (isset($_GET["delete"])) {
             $user_to_delete = $_GET["delete"];
             if ($permission_level >= $manage_other_users) {
                 DeleteUser($user_to_delete, $pdo);
                 Redirect("../");
             } else {
                 Redirect("../");
             }
         }

     if (isset($_GET['id'])) {
         $userid = $_GET['id'];
         $lesson_details['vorname'] = GetUserByID($userid, "vorname", $pdo);
         $lesson_details['nachname'] = GetUserByID($userid, "nachname", $pdo);
         $lesson_details['email'] = GetUserByID($userid, "email", $pdo);
         $lesson_details['permission_level'] = GetUserByID($userid, "permission_level", $pdo);
     }
         if(isset($old_url) AND $new_url = $old_url AND ($_POST['save'] ?? 0) == "1") {
             $error = false;
             $new_vorname = ($_POST['vorname'] ?? '');
             $new_nachname = ($_POST['nachname'] ?? '');
             $new_email = ($_POST['email'] ?? '');
             $new_permission_level = ($_POST['permission_level'] ?? '');
             $passwort = ($_POST['password'] ?? '');


             //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
             if(!$error) {
                 $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                 $result = $statement->execute(array('email' => $new_email));
                 $user = $statement->fetch();

                 if($user !== false) {
                     $mailalredyused = "Diese E-Mail-Adresse ist bereits vergeben";
                     $error = true;
                 }
             }
             //Keine Fehler, wir können den Nutzer registrieren
             if(!$error) {
                 $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
                 CreateUser($new_vorname, $new_nachname, $passwort_hash, $new_email, $new_permission_level, $pdo);
                 Redirect("../");
             }




         } elseif (isset($old_url) AND $new_url = $old_url AND isset($_POST['update'])) {

             $new_vorname = ($_POST['vorname'] ?? '');
             $new_nachname = ($_POST['nachname'] ?? '');
             $new_email = ($_POST['email'] ?? '');
             $new_permission_level = ($_POST['permission_level'] ?? '');
             $passwort = ($_POST['password'] ?? '');
             UpdateUser($_POST['update'], $new_vorname, $new_nachname, $new_email, $new_permission_level, $pdo);
             Redirect("../");
         }

?>

         <main role="main" class="main-content">
            <div class="container-fluid">
               <div class="row justify-content-center">
                  <form action="./"save=1 method="post">
                     <div class="col-12">
                        <h2 class="page-title">
                            <?php
                            if(isset($_GET['id'])) {
                                echo "Benutzer bearbeiten";
                            } else {
                                echo "Benutzer erstellen";
                            }
                            ?>
                        </h2>
                        <div class="card shadow mb-4">
                           <div class="card-header">
                              <strong class="card-title">Benutzer details</strong>
                           </div>
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="simpleinput">Vorame</label>
                                       <input name="vorname" type="text" id="simpleinput" class="form-control" placeholder="Vorame" maxlength="20" value="<?php if(isset($lesson_details['vorname'])) { echo $lesson_details['vorname']; }?>" required>
                                    </div>
                                 </div>
                                 <!-- /.col -->
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                       <label for="helping">Nachname</label>
                                       <input name="nachname" type="text" id="helping" class="form-control" placeholder="Nachname" maxlength="30" value="<?php if(isset($lesson_details['nachname'])) { echo $lesson_details['nachname']; }?>">
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
                                             <label for="helping">E-Mail</label>
                                             <?php if(isset($mailalredyused)) { echo '<p class="text-muted">'; echo $mailalredyused; echo '</p>'; }?>
                                             <input type="email" name="email" type="text" id="helping" class="form-control" placeholder="E-Mail" maxlength="50" value="<?php if(isset($lesson_details['email'])) { echo $lesson_details['email']; }?>" required>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-6 mb-4">
                                 <div class="card shadow">
                                     <div class="card-body">
                                         <div class="form-group mb-3">
                                             <label for="custom-select">Berechtigungen</label>
                                             <select name="permission_level" class="form-control dropdown" id="location">
                                                 <?php
                                                 $permission_level_sel = array();
                                                 if(isset($lesson_details['permission_level'])) {

                                                     $tmp = array_search (GetHighestValueBelowValueName($lesson_details['permission_level'], $permission_level_names), $permission_level_names);
                                                     $permission_level_sel[$tmp] = "selected";
                                                 }
                                                 $count = 0;
                                                 foreach ($permission_level_names as $value => $i) {
                                                     if ($value >= 99 AND $lesson_details['permission_level'] <= 99) {
                                                         continue;
                                                     }
                                                     $count++;
                                                     echo '<option value="' . $value . '" ' . ($permission_level_sel[$value] ?? '') . '>' . $i . '</option>';
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
                                        <strong class="card-title">Passwort</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input <?php if(isset($_GET['id'])) { echo " disabled "; } ?> type="password" name="password" class="form-control form-control-lg" type="text" placeholder="Passwort" maxlength="255" value="<?php if(isset($lesson_details['password'])) { echo $lesson_details['password']; }?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                           <div class="col-md-12 mb-4">
                               <button type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zurück</button>
                              <?php
                                 if(isset($_GET['id'])) {
                                     echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" formaction="./?update=' . $_GET['id'] . '" name="update" value="' . $_GET['id'] . '">Aktualisieren</button>';
                                     echo '<button type="button summit" class="btn mb-2 btn-outline-danger" formaction="./?delete=' . $_GET['id'] . '">Benutzer Löschen</button>';
                                 } else {
                                     echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="save" value="1">Benutzer Erstellen</button>';
                                     echo '<button type="button" class="btn mb-2 btn-outline-secondary" disabled="">Benutzer Löschen</button>';
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
      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-YL7H2T9DF4"></script>
      <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-YL7H2T9DF4');
      </script>
      <!-- Custom JS code -->
   </body>
</html>
