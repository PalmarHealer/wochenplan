	<?php
		$include_path = __DIR__ . "/..";
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
	
    <title>Dashboard</title>


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
              <h2 class="h3 mb-4 page-title">Dashboard</h2>
			  
			  
			  
			  
			  
              <div class="profilename row mt-5 align-items-center">
			  
			  
                <div class="col-md-3 text-center mb-5">
				
		    	<!-- 	<div class="avatar avatar-xl">
						<i class="fe fe-user fe-32"></i>
						<img src="./assets/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
					</div> -->
                </div>
				
				
                <div class="col">
                  <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="name-badge mb-1"><?php echo $vorname . ", " . $nachname ?></h4>
                    </div>
                  </div>
                  </div>
				  
				  
              </div>






                <div class="row my-4">

                    <div class="align-items-center col-md-4 center2">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                          <span class="circle circle-lg bg-light">
                            <i class="fe fe-user fe-24 text-primary"></i>
                          </span>
                                    </div> <!-- .col -->
                                    <div class="col">
                                        <a href="#">
                                            <h3 class="h5 mt-4 mb-1">Personal</h3>
                                        </a>
                                        <p class="text-muted">Schau dir deine Account infomationen nochmal an und ändere gegebenenfalls etwas.</p>
                                        <br>
                                    </div> <!-- .col -->
                                </div> <!-- .row -->
                            </div> <!-- .card-body -->
                            <div class="card-footer">
                                <a href="../profile/settings/" class="d-flex justify-content-between text-muted"><span>Account Settings</span><i class="fe fe-chevron-right"></i></a>
                            </div> <!-- .card-footer -->
                        </div> <!-- .card -->
                    </div> <!-- .col-md-->
                    <div class="align-items-center col-md-4 center2">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                          <span class="circle circle-lg bg-light">
                            <i class="fe fe-calendar fe-24 text-primary"></i>
                          </span>
                                    </div> <!-- .col -->
                                    <div class="col">
                                        <a href="#">
                                            <h3 class="h5 mt-4 mb-1">Angebote</h3>
                                        </a>
                                        <p class="text-muted">Wechsel zu einer übersicht aller Angebote.</p>
                                        <br>
                                    </div> <!-- .col -->
                                </div> <!-- .row -->
                            </div> <!-- .card-body -->
                            <div class="card-footer">
                                <a href="../lessons" class="d-flex justify-content-between text-muted"><span>Lesson overview</span><i class="fe fe-chevron-right"></i></a>
                            </div> <!-- .card-footer -->
                        </div> <!-- .card -->
                    </div> <!-- .col-md-->
                </div>
			  <div class="full">
              <h6 class="mb-3">Quick Lesson overview</h6>
              <table class="table table-borderless table-striped table-hover">
                <thead>
                          <tr>
                            <th></th>
                            <th>Angebot</th>
                            <th>Beschreibung</th>
                            <th>Ort</th>
                            <th>Zeitpunkt</th>
                            <th>Tag</th>
                            <th>Notizen</th>
                            <th>Aktionen</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php
								GetAllLessonsFromUserAndPrintThem($id, "4", $room_names, $times, $pdo);
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