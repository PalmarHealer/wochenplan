<?php
global $relative_path, $version, $weekday_names_long, $id, $create_lessons, $room_names, $times, $pdo, $webroot, $vorname, $nachname;
$include_path = __DIR__ . "/..";
require $include_path . "/dependencies/config.php";
$permission_needed = 0;
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
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">
	
    <title>Übersicht</title>

      <!-- Simple bar CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
      <!-- Fonts CSS -->
      <link href="<?php echo $relative_path; ?>/css/overpass.css?version=<?php echo $version; ?>" rel="stylesheet">
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
  </head>
  <body class="vertical  light">
    <div class="wrapper">
      
	  <?php

		$keep_pdo = true;
		include $include_path . "/include/nav.php";

	  ?>
	  
<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <h2 class="h3 mb-4 page-title">Übersicht</h2>
                <div class="unsupported-browser"></div>
			  
			  
			  
			  
              <div class="profilename row mt-5 align-items-center">
			  
			  
                <div class="col-md-3 text-center mb-5">

                </div>
				
				
                <div class="col">
                  <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="name-badge mb-1">Hallo, <?php echo $vorname . " " . $nachname ?></h4>
                    </div>
                  </div>
                  </div>
              </div>
                <div class="row my-4">
                    <?php
                    PrintDays(date("Y-m-d",time()), $weekday_names_long);
                    ?>
                </div>
                <?php
                $tmp = '<div class="full-percentage">
                  <h6 class="mb-3">Deine Angebote</h6>
                  <table class="table table-borderless table-striped table-hover">
                      <thead>
                      <tr>
                          <th></th>
                          <th>Angebot</th>
                          <th>Beschreibung</th>
                          <th>Ort</th>
                          <th>Zeitpunkt</th>
                          <th>Tag</th>
                          <th>Farbe</th>
                          <th>Notizen</th>
                          <th>Aktionen</th>
                      </tr>
                      </thead>
                      <tbody>
                      ';
                      $output = GetAllLessonsFromUserAndPrintThem($id, "4", $room_names, $times, $pdo, $webroot);
                      if ($output == "") {
                          $tmp = "";
                      }
                      else {
                          $tmp .= $output;
                          $tmp .= '</tbody></table></div>';
                      }
                      echo $tmp;
                ?>
            </div> <!-- /.col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php include $include_path. "/include/footer.php"; ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="<?php echo $relative_path; ?>/js/jquery.min.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/popper.min.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/moment.min.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/bootstrap.min.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/simplebar.min.js?version=<?php echo $version; ?>"></script>
    <script src='<?php echo $relative_path; ?>/js/daterangepicker.js?version=<?php echo $version; ?>'></script>
    <script src='<?php echo $relative_path; ?>/js/jquery.stickOnScroll.js?version=<?php echo $version; ?>'></script>
    <script src="<?php echo $relative_path; ?>/js/tinycolor-min.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/config.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/apps.js?version=<?php echo $version; ?>"></script>
    <script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
    <script>
        $(document).ready(function() {
            detectBrowser();
        });
        function detectBrowser() {
            if (navigator.userAgent.indexOf("Firefox") !== -1) {
                // Browser is Firefox
                const html = `<div class="alert alert-warning" role="alert">Der Wochenplan ist leider nicht für Firefox optimiert worden. Bitte überlege einen anderen Browser zu verwenden oder auf eventuelle Probleme zu stoßen. Danke!</div>`;
                $(".unsupported-browser").html(html);
            }

        }
    </script>
  </body>
</html>