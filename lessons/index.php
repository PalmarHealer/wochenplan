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
	
    <title>Angebote</title>
	
	
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
		include $include_path. "/include/nav.php";

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
								
								
								
								//update_lesson_with_id
								
								if (isset($_GET["remove_lesson_with_id"])) {
								$delete_lesson = $pdo->prepare("DELETE FROM angebot WHERE id = ?");
								$delete_lesson->execute(array($_GET["remove_lesson_with_id"])); 
								redirect("./");	
								}
								
								
								
								
								
								
								
								$lessons = $pdo->prepare("SELECT * FROM angebot");
								$lessons->execute();   
								
								
								while($sl = $lessons->fetch()) {
															
									if ($sl['date_type'] == "2") {
										$date1 = $sl['date'];
										$single_date1 = explode("-", $date1);
										$date_fomatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];	
									} else {
										$date_day = $sl['date_repeating'];
										if ($date_day == "1") {
										$date_fomatted = "Jeden Montag";
										} elseif ($date_day == "2") {
										$date_fomatted = "Jeden Dienstag";
										} elseif ($date_day == "3") {
										$date_fomatted = "Jeden Mittwoch";
										} elseif ($date_day == "4") {
										$date_fomatted = "Jeden Donnerstag";
										} elseif ($date_day == "5") {
										$date_fomatted = "Jeden Freitag";
										} else {
										$date_fomatted = "Fehler beim Laden des Datums";
										}
									}
									
									
									
									$creator_id = $pdo->prepare("SELECT * FROM users WHERE id = ?");
									$creator_id->execute(array($sl['assigned_user_id']));
									while ($creator_name = $creator_id->fetch()) {
										$creator_fomatted = $creator_name['vorname'] . " " . $creator_name['nachname'];
									}
									echo '<tr>
										  <td>
										  </td>
										  <td>' . $sl['name'] . '</td>
										  <td>' . $sl['description'] . '</td>
										  <td>' . $room_names[$sl['location']] . '</td>
										  <td>' . $times[$sl['time']] . '</td>
										  <td>' . $date_fomatted . '</td>
										  <td>' . $creator_fomatted . '</td>
										  <td>' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Action</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="./details/?id=' . $sl['id'] . '">Edit</a>
												<a class="dropdown-item" href="./?remove_lesson_with_id=' . $sl['id'] . '">Remove</a>
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
		<div class="btn-box w-100 mt-4 mb-1">
			<a href="<?php echo $relative_path; ?>/lessons/details" type="button" class="right btn mb-2 btn-primary">Angebot Erstellen</a>
        </div>
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
    <script src="<?php echo $relative_path; ?>/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $relative_path; ?>/js/dataTables.bootstrap4.min.js"></script>
	<script>
      $('#dataTable-1').DataTable(
      {
        autoWidth: true,
        "lengthMenu": [
          [4, 8, 16, 32, 64, -1],
          [4, 8, 16, 32, 64, "All"]
        ]
      });
    </script>
  </body>
</html>