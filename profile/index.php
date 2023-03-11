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
	
    <title>Profile</title>
	
	
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
  <body class="vertical light">
    <div class="wrapper">
      
	  <?php 
		include $include_path. "/include/nav.php";
	  ?>
	  
	  
	  
	  
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <h2 class="h3 mb-4 page-title">Profile</h2>
			  
			  
			  
			  
			  
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
                      <h4 class="name-badge mb-1"><?php echo $vorname ?>, <?php echo $nachname ?></h4>
                    </div>
                  </div>
                  </div>
				  
				  
              </div>
			  
			  
			  
			  
			  
			  
              <div class="row my-4">
			  
                <div class="center col-md-4">
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
                      <a href="./settings/" class="d-flex justify-content-between text-muted"><span>Account Settings</span><i class="fe fe-chevron-right"></i></a>
                    </div> <!-- .card-footer -->
                  </div> <!-- .card -->
                </div> <!-- .col-md-->
				
				
				<div class="center col-md-4">
                  <div class="card mb-4 shadow">
                    <div class="card-body my-n3">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-lg bg-light">
                            <i class="fe fe-smartphone fe-24 text-primary"></i>
                          </span>
                        </div> <!-- .col -->
                        <div class="col">
                          <a href="#">
                            <h3 class="h5 mt-4 mb-1">Phone(s)</h3>
                          </a>
                          <p class="text-muted">Wechsel zu einer übersicht aller Telefone die du gerade verwalten kannst.</p>
						  <br>
                        </div> <!-- .col -->
                      </div> <!-- .row -->
                    </div> <!-- .card-body -->
                    <div class="card-footer">
                      <a href="../phone" class="d-flex justify-content-between text-muted"><span>Phone overview</span><i class="fe fe-chevron-right"></i></a>
                    </div> <!-- .card-footer -->
                  </div> <!-- .card -->
                </div> <!-- .col-md-->
                
                
              </div> <!-- .row-->
              
			  
			  
			  <div class="full">
              <h6 class="mb-3">Quick Phone overview</h6>
              <table class="table table-borderless table-striped">
                <thead>
                  <tr role="row">
                    <th>ID</th>
                    <th>Phone Name</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
				  
                    <th scope="col">9568</th> <!-- ID -->
                    <td>Test</td> <!-- Name -->
                    <td><span class="dot dot-lg bg-warning mr-2"></span>Currently not Responsing</td> <!-- Status -->
                    
                  </tr>
				  
                  <tr>
                    <th scope="col">1156</th>
                    <td>Phone2</td>
                    <td><span class="dot dot-lg bg-danger mr-2"></span>Offline</td>
                    
                  </tr>
				  
                  <tr>
                    <th scope="col">1038</th>
                    <td>Phone3</td>
                    <td><span class="dot dot-lg bg-success mr-2"></span>Online</td>
                    
                  </tr>
                  
                </tbody>
              </table>
			  </div>
            </div> <!-- /.col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php include ("../include/footer.php"); ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/moment.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/simplebar.min.js"></script>
    <script src='../js/daterangepicker.js'></script>
    <script src='../js/jquery.stickOnScroll.js'></script>
    <script src="../js/tinycolor-min.js"></script>
    <script src="../js/config.js"></script>
    <script src="../js/apps.js"></script>
  </body>
</html>
