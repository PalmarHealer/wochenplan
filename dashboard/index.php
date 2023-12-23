<?php
        global $relative_path, $version, $webroot;
        $include_path = __DIR__ . "/..";
        $mte_needed = true;
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
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme">
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" disabled>
      <!-- Custom CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      
	  <?php

		$keep_pdo = true;
      $permission_needed = 0;
		include $include_path . "/include/nav.php";

	  ?>
	  
<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <h2 class="h3 mb-4 page-title">Übersicht</h2>
			  
			  
			  
			  
			  
              <div class="profilename row mt-5 align-items-center">
			  
			  
                <div class="col-md-3 text-center mb-5">

                </div>
				
				
                <div class="col">
                  <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="name-badge mb-1"><?php echo $vorname . " " . $nachname ?></h4>
                    </div>
                  </div>
                  </div>
              </div>
                <div class="row my-2">

                    <div onclick="window.location='../plan/week'" class="pointer align-items-center col-md-4 center2">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                          <span class="circle circle-lg bg-light">
                            <i class="fe fe-calendar fe-24 text-primary"></i>
                          </span>
                                    </div>
                                    <div class="col">
                                        <a href="#">
                                            <h1 class="h5 mt-4 mb-1">Wochenübersicht</h1>
                                        </a>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="../plan/week" class="d-flex justify-content-between text-muted"><span>Angebote ansehen</span><i
                                            class="fe fe-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row my-4">
                    <?php
                    PrintDays(date("Y-m-d",time()), $weekday_names_long);
                    ?>
                </div>
			  <div class="full">
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
							<?php
								GetAllLessonsFromUserAndPrintThem($id, "4", $room_names, $times, $pdo, $webroot);
							?>
                        </tbody>
              </table>
			  </div>
            </div> <!-- /.col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php include $include_path. "/include/footer.php"; ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="<?php echo $relative_path; ?>/js/jquery.min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/popper.min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/moment.min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/simplebar.min.js"></script>
    <script src='<?php echo $relative_path; ?>/js/daterangepicker.js'></script>
    <script src='<?php echo $relative_path; ?>/js/jquery.stickOnScroll.js'></script>
    <script src="<?php echo $relative_path; ?>/js/tinycolor-min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/config.js"></script>
    <script src="<?php echo $relative_path; ?>/js/apps.js"></script>
  </body>
</html>