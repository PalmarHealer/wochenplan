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
	
    <title>Angebote</title>
	
	
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
		$keep_pdo = true;
		include $include_path. "/nav.php";
		
		
		
		
		if(isset($_POST['date'])) {
			
			
			
			$date = $_POST['date'];
			$single_date = explode("/", $date);
			$new_date = $single_date[2] . "-" . $single_date[1] . "-" . $single_date[0];			
			
			$new_name = $_POST['name'];
			$new_description = $_POST['description'];
			$new_location = $_POST['location'];
			$new_time =  $_POST['time'];
			$new_notes = $_POST['notes'];
			$new_assigned_user_id = $_POST['creator'];
			

			
			$save_lesson = $pdo->prepare("INSERT INTO angebot (date, name, description, location, time, notes, assigned_user_id) VALUES (:date, :name, :description, :location, :time, :notes, :assigned_user_id)");
			$save_lesson->execute(array('date' => $new_date, 'name' => $new_name, 'description' => $new_description, 'location' => $new_location, 'time' => $new_time, 'notes' => $new_notes, 'assigned_user_id' => $new_assigned_user_id));

		}
	  ?>
	  
	  
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <h2 class="mb-2 page-title">Angebote</h2>
              <p class="card-text">Kleine Seiten Beschreibung</p>
              <div class="row my-4">
                <!-- Small table -->
                <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" id="dataTable-1">
                        <thead>
                          <tr>
                            <th></th>
                            <th>#</th>
                            <th>Angebot</th>
                            <th>Beschreibung</th>
                            <th>Ort</th>
                            <th>Zeitpunkt</th>
                            <th>Tag</th>
                            <th>Person</th>
                            <th>Notizen</th>
                            <th>Aktionen</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php
								
								
								$lessons = $pdo->prepare("SELECT * FROM angebot");
								$lessons->execute();   
								
								
								while($sl = $lessons->fetch()) {
															
									$date1 = $sl['date'];
									$single_date1 = explode("-", $date1);
									$date_fomatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];	
									
									
									
									
									$creator_id = $pdo->prepare("SELECT * FROM users WHERE id = ?");
									$creator_id->execute(array($sl['assigned_user_id']));
									while ($creator_name = $creator_id->fetch()) {
										$creator_fomatted = $creator_name['vorname'] . " " . $creator_name['nachname'];
									}
									echo '<tr>
										  <td>
										  </td>
										  <td>' . $sl['id'] . '</td>
										  <td>' . $sl['name'] . '</td>
										  <td>' . $sl['description'] . '</td>
										  <td>' . $sl['location'] . '</td>
										  <td>' . $sl['time'] . '</td>
										  <td>' . $date_fomatted . '</td>
										  <td>' . $creator_fomatted . '</td>
										  <td>' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Action</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="#">Edit</a>
												<a class="dropdown-item" href="#">Remove</a>
											  </div>
										  </td>
										  </tr>';
									
								}
								$pdo = null;
							?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div> <!-- simple table -->
              </div> <!-- end section -->
            </div> <!-- .col-12 -->
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
    <script src='<?php echo $path; ?>/js/daterangepicker.js'></script>
    <script src='<?php echo $path; ?>/js/jquery.stickOnScroll.js'></script>
    <script src="<?php echo $path; ?>/js/tinycolor-min.js"></script>
    <script src="<?php echo $path; ?>/js/config.js"></script>
    <script src="<?php echo $path; ?>/js/apps.js"></script>
  </body>
</html>