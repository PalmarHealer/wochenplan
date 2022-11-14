	<?php
		$include_path = __DIR__ . "/../include";
		include $include_path . "/config.php";
	?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $path; ?>/favicon.ico">
	
    <title>Dashboard</title>
	
	
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/simplebar.css">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/feather.css">
    <link rel="stylesheet" href="<?php echo $path; ?>/css/dataTables.bootstrap4.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="<?php echo $path; ?>/css/app-dark.css" id="darkTheme" disabled>
	<!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/customstyle.css">
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      
	  <?php 
		include $include_path. "/nav.php";
	  ?>
	  
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Welcome!</h2>
                </div>
              </div>
            </div>
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php include ("../include/footer.php"); ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
	
	<script src="<?php echo $path; ?>/js/jquery.min.js"></script>
    <script src="<?php echo $path; ?>/js/popper.min.js"></script>
    <script src="<?php echo $path; ?>/js/moment.min.js"></script>
    <script src="<?php echo $path; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $path; ?>/js/simplebar.min.js"></script>
    <script src="<?php echo $path; ?>/js/daterangepicker.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.stickOnScroll.js"></script>
    <script src="<?php echo $path; ?>/js/tinycolor-min.js"></script>
    <script src="<?php echo $path; ?>/js/config.js"></script>
    <script src="<?php echo $path; ?>/js/d3.min.js"></script>
    <script src="<?php echo $path; ?>/js/topojson.min.js"></script>
    <script src="<?php echo $path; ?>/js/datamaps.all.min.js"></script>
    <script src="<?php echo $path; ?>/js/datamaps-zoomto.js"></script>
    <script src="<?php echo $path; ?>/js/datamaps.custom.js"></script>
    <script src="<?php echo $path; ?>/js/Chart.min.js"></script>
	<script src="<?php echo $path; ?>/js/gauge.min.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.sparkline.min.js"></script>
    <script src="<?php echo $path; ?>/js/apexcharts.min.js"></script>
    <script src="<?php echo $path; ?>/js/apexcharts.custom.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.mask.min.js"></script>
    <script src="<?php echo $path; ?>/js/select2.min.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.steps.min.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo $path; ?>/js/jquery.timepicker.js"></script>
    <script src="<?php echo $path; ?>/js/dropzone.min.js"></script>
    <script src="<?php echo $path; ?>/js/uppy.min.js"></script>
    <script src="<?php echo $path; ?>/js/quill.min.js"></script>
    <script src="<?php echo $path; ?>/js/apps.js"></script>
	  
    
  </body>
</html>