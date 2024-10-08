<?php
$include_path = __DIR__ . "/../../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $manage_other_users, $permission_level_names, $permission_level, $relative_path, $webroot, $version, $id, $pdo;

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");
?>
<!doctype html>
<html lang="de">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">


      <title>Benutzer bearbeiten</title>


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
         require $include_path. "/include/nav.php";


         if (isset($_GET["delete"])) {
             $user_to_delete = $_GET["delete"];
             if ($permission_level >= $manage_other_users) {
                 DeleteUser($user_to_delete, $pdo);
             }
             Redirect("../");
         }

     if (isset($_GET['id'])) {
         $userid = $_GET['id'];
         $lesson_details['vorname'] = GetUserByID($userid, "vorname", $pdo);
         $lesson_details['nachname'] = GetUserByID($userid, "nachname", $pdo);
         $lesson_details['email'] = GetUserByID($userid, "email", $pdo);
         $lesson_details['permission_level'] = GetUserByID($userid, "permission_level", $pdo);
     }
         if(UserStayedOnSite() AND ($_POST['save'] ?? 0) == "1" AND $_SERVER["REQUEST_METHOD"] == "POST") {
             $error = false;
             $new_vorname = ($_POST['vorname'] ?? '');
             $new_nachname = ($_POST['nachname'] ?? '');
             $new_email = ($_POST['email'] ?? '');
             $new_permission_level = ($_POST['permission_level'] ?? '');
             $passwort = ($_POST['password'] ?? '');


             //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
             $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
             $result = $statement->execute(array('email' => $new_email));
             $user = $statement->fetch();

             if($user !== false) {
                 $mailalredyused = "Diese E-Mail-Adresse ist bereits vergeben";
                 $error = true;
             }
             //Keine Fehler, wir können den Nutzer registrieren
             if(!$error) {
                 $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
                 CreateUser($new_vorname, $new_nachname, $passwort_hash, $new_email, $new_permission_level, $pdo);
                 Redirect("../");
             }




         } elseif (UserStayedOnSite() AND isset($_POST['update']) AND $_SERVER["REQUEST_METHOD"] == "POST") {

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
                                             <input type="email" name="email" id="helping" class="form-control" placeholder="E-Mail" maxlength="50" value="<?php if(isset($lesson_details['email'])) { echo $lesson_details['email']; }?>" required>
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
                                                     if ($value >= 99 AND !$permission_level >= 99) {
                                                         if (isset($lesson_details['permission_level'])) {
                                                             if ($lesson_details['permission_level'] < 99) continue;
                                                         } else continue;
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
                                     echo '<button style="float:right;" type="summit" class="btn mb-2 btn-outline-success" formaction="./?update=' . $_GET['id'] . '" name="update" value="' . $_GET['id'] . '">Aktualisieren</button>';
                                     echo '<button type="summit" class="btn mb-2 btn-outline-danger" formaction="./?delete=' . $_GET['id'] . '">Benutzer Löschen</button>';
                                 } else {
                                     echo '<button style="float:right;" type="summit" class="btn mb-2 btn-outline-success" name="save" value="1">Benutzer Erstellen</button>';
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
   </body>
</html>
