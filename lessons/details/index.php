	<?php
		$include_path = __DIR__ . "/../../include";
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
	
    <title>Angebot Verwalten</title>
	
	
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
	<!-- Site Css -->
    <link rel="stylesheet" href="<?php echo $path; ?>/css/select2.css">
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      
	  <?php 
		$keep_pdo = true;
		include $include_path. "/nav.php";
		
		if($new_url = $old_url) {
			
			$date = $_POST['date'];
			$single_date = explode("/", $date);
			$new_date = $single_date[2] . "-" . $single_date[1] . "-" . $single_date[0];			
			
			$new_name = $_POST['name'];
			$new_description = $_POST['description'];
			$new_location = $_POST['location'];
			$new_time =  $_POST['time'];
			$new_notes = $_POST['notes'];
			$new_assigned_user_id = $_POST['creator'];
			
			
			
			
			if (isset($_POST["update_lesson_with_id"])) { //vorname = :vorname_neu,				
				$update_lesson = $pdo->prepare("UPDATE angebot SET date = :date_neu, name = :name_neu, description = :description_neu, location = :location_neu, time = :time_neu, notes = :notes_neu, assigned_user_id = :assigned_user_id_neu WHERE id = :id");
				$update_lesson->execute(array('id' => $_POST["update_lesson_with_id"], 'date_neu' => $new_date, 'name_neu' => $new_name, 'description_neu' => $new_description, 'location_neu' => $new_location, 'time_neu' => $new_time, 'notes_neu' => $new_notes, 'assigned_user_id_neu' => $new_assigned_user_id));
				redirect("../");
				
			} elseif ($_POST['save'] == "1") {
				$save_lesson = $pdo->prepare("INSERT INTO angebot (date, name, description, location, time, notes, assigned_user_id) VALUES (:date, :name, :description, :location, :time, :notes, :assigned_user_id)");
				$save_lesson->execute(array('date' => $new_date, 'name' => $new_name, 'description' => $new_description, 'location' => $new_location, 'time' => $new_time, 'notes' => $new_notes, 'assigned_user_id' => $new_assigned_user_id));
				redirect("../");
			}			
		}
		
		
		
		if (isset($_GET['id'])) {
			$lessons = $pdo->prepare("SELECT * FROM angebot WHERE id = :id");
			$lessons->execute(array('id' => $_GET['id']));   
				
			while($sl = $lessons->fetch()) {
					
				$date1 = $sl['date'];
				$single_date1 = explode("-", $date1);
				$date_fomatted = $single_date1[2] . "/" . $single_date1[1] . "/" . $single_date1[0];	
				
				$creator_id = $pdo->prepare("SELECT * FROM users WHERE id = ?");
				$creator_id->execute(array($sl['assigned_user_id']));
				while ($creator_name = $creator_id->fetch()) {
					$creator_fomatted = $creator_name['vorname'] . " " . $creator_name['nachname'];
				}
				
				$lesson_details = array();
				$lesson_details['id'] = $sl['id'];
				$lesson_details['name'] = $sl['name'];
				$lesson_details['description'] = $sl['description'];
				$lesson_details['location'] = $sl['location'];
				$lesson_details['time'] = $sl['time'];
				$lesson_details['date'] = $date_fomatted;
				$lesson_details['creator'] = $creator_fomatted;
				$lesson_details['notes'] = $sl['notes'];
				
			}
		}
		
	  ?>
	<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
		  
					<form action="./" method="post">
					
					
					
					
					
            <div class="col-12">
              <h2 class="page-title">Angebot erstellen</h2>
              <p class="text-muted"> Hier kannst Du ganz einfach Unterrichtsangebote erstellen. Die Unterrichtsangebote k??nnen ganz einfach an Deine Bed??rfnisse angepasst werden, sodass Du das perfekte Lernangebot anbieten kannst.
              <div class="card shadow mb-4">
                <div class="card-header">
                  <strong class="card-title">Angebot details</strong>
                </div>
				
				
					
				
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
					
					
                      <div class="form-group mb-3">
                        <label for="simpleinput">Name des Angebotes</label>
                        <input name="name" type="text" id="simpleinput" class="form-control" placeholder="Name des Angebotes" maxlength="10" value="<?php if(isset($lesson_details['name'])) { echo $lesson_details['name']; }?>" required>
						</input>
                      </div>
					  
                    </div> <!-- /.col -->
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="example-helping">Weitere Beschreibung</label>
                        <input name="description" type="text" id="helping" class="form-control" placeholder="Wenn du dein Angebot genauer beschreiben m??chtest kannst du das einfach hier machen." maxlength="30" value="<?php if(isset($lesson_details['description'])) { echo $lesson_details['description']; }?>" required>
						</input>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- / .card -->
              <div class="row">
			  
			  
			  
                <div class="col-md-6 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="custom-select">Ort des Angebotes bzw. Art</label>
                        <select name="location" class="form-control" id="type-select">
						  <?php if(isset($lesson_details['location'])) { 
							  $selected_location = array();
							  $selected_location[$lesson_details['location']] = "selected";
						  }?>
                          <option value="1" <?php if(isset($selected_location[1])) { echo $selected_location[1]; }?>>Raum 1</option>
                          <option value="2" <?php if(isset($selected_location[2])) { echo $selected_location[2]; }?>>Raum 2</option>
                          <option value="3" <?php if(isset($selected_location[3])) { echo $selected_location[3]; }?>>Raum 3 (HS)</option>
                          <option value="4" <?php if(isset($selected_location[4])) { echo $selected_location[4]; }?>>Raum 4 (RS)</option>
                          <option value="5" <?php if(isset($selected_location[5])) { echo $selected_location[5]; }?>>Garten</option>
                          <option value="6" <?php if(isset($selected_location[6])) { echo $selected_location[6]; }?>>Sport/Ausflug</option>
                          <option value="7" <?php if(isset($selected_location[7])) { echo $selected_location[7]; }?>>Sonnenzimmer/Sonstiges</option>
                        </select>
                      </div>
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
				
				
				
				
              <div class="col-md-6 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="custom-select">Zeitpunkt des Angebotes</label>
                        <select name="time" class="form-control" id="type-select" required>
						  <?php if(isset($lesson_details['time'])) { 
							  $selected_time = array();
							  $selected_time[$lesson_details['time']] = "selected";
						  }?>
                          <option value="1" <?php if(isset($selected_time[1])) { echo $selected_time[1]; }?>>Zeit 1</option>
                          <option value="2" <?php if(isset($selected_time[2])) { echo $selected_time[2]; }?>>Zeit 2</option>
                          <option value="3" <?php if(isset($selected_time[3])) { echo $selected_time[3]; }?>>Zeit 3</option>
                          <option value="4" <?php if(isset($selected_time[4])) { echo $selected_time[4]; }?>>Zeit 4</option>
                          <option value="5" <?php if(isset($selected_time[5])) { echo $selected_time[5]; }?>>Zeit 5</option>
                          <option value="6" <?php if(isset($selected_time[6])) { echo $selected_time[6]; }?>>Zeit 6</option>
                          <option value="7" <?php if(isset($selected_time[7])) { echo $selected_time[7]; }?>>Zeit 7</option>
                        </select>
                      </div>
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
				
				
				
				
				
				
				
				
				
				
				<div class="col-md-6 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="custom-select">Zeitpunkt des Angebotes</label>
						<div class="input-group">
                            <div class="input-group-append">
                              <div class="input-group-text" id="button-addon-date"><span class="fe fe-calendar fe-16"></span></div>
                            </div>
                            <input name="date" type="text" class="form-control drgpicker" id="date-input1" value="
								<?php
									if(isset($lesson_details['date'])) { 
										echo $lesson_details['date']; 
									} else {
										echo date("d");
										echo "/";
										echo date("m");
										echo "/";
										echo date("Y");
									}
								?>
							" aria-describedby="button-addon2">
							</input>
                          </div>
                      </div>
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
				
				
                <div class="col-md-6 mb-4"> <!-- Hier muss noch die edit funktion gemacht werden und alle user m??ssen gelistet werden... -->
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="custom-select">Wer macht diese Angebot?</label>
                            <select name="creator" class="form-control select2" required
								<?php
									if($permission_level < $create_lessons_for_others) {
										echo "disabled";
									}
								?>
							>
							
                              <option value="<?php echo $id; ?>" selected><?php
								echo $vorname;
								echo " ";
								echo $nachname;
								?> (Du selbst)</option>
							  <?php
								$get_usernames = "SELECT * FROM users ORDER BY permission_level";
								foreach ($pdo->query($get_usernames) as $row) {
									
									if($id == $row['id']) {
										continue;
									}
									
									
									echo "<option value='";
									echo $row['id'];
									echo "'>";
									echo $row['vorname']." ".$row['nachname'];
									echo "</option>";
									
								}
								$pdo = null;
							  ?>
							  
                            </select>
						
						
						
						
                      </div>
                    </div>
                  </div>
                </div>
				
                <div class="col-md-12 mb-4">
                  <div class="card shadow">
                    <div class="card-header">
                      <strong class="card-title">Zus??tsliche Infomationen (Sind nur hier sichtbar und werden nicht auf dem Plan gezeigt)</strong>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <input name="notes" class="form-control form-control-lg" type="text" placeholder="Notizen" maxlength="255" value="<?php if(isset($lesson_details['notes'])) { echo $lesson_details['notes']; }?>">
						</input>
                      </div>
                    </div>
                  </div>
                </div>
				
                <div class="col-md-12 mb-4">
                        <button type="button" onclick="history.back()" class="btn mb-2 btn-outline-primary">Zur??ck</button>
						
						
						
						<?php
						if(!isset($_GET['id'])) {
							echo '<button type="button" class="btn mb-2 btn-outline-secondary" disabled="">Angebot L??schen</button>';
							echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="save" value="1">Erstellen</button>';
						} elseif (isset($_GET['id'])) {
							
							echo '<button type="button summit" class="btn mb-2 btn-outline-danger" formaction="../?remove_lesson_with_id=' . $_GET['id'] . '">Angebot L??schen</button>';
							echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success" name="update_lesson_with_id" value="' . $_GET['id'] . '">Aktualisieren</button>';
						}
						?>
						
						
                </div>
				
				
				
              </div> <!-- end section -->
            </div> <!-- .col-12 -->
			
			</form>
			
          </div> <!-- .row -->
		</div> <!-- .container-fluid -->
		
        <?php include $include_path. "/footer.php"; ?>
		
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
	
	
	
	
	  <!-- Custom JS code -->
    <script>
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