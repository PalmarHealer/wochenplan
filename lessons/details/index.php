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
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      
	  <?php 
		include $include_path. "/nav.php";
		
		
	  ?>
	<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
		  
					<form action="../" method="post">
					
            <div class="col-12">
              <h2 class="page-title">Angebot erstellen</h2>
              <p class="text-muted"> Hier kannst Du ganz einfach Unterrichtsangebote erstellen. Die Unterrichtsangebote können ganz einfach an Deine Bedürfnisse angepasst werden, sodass Du das perfekte Lernangebot anbieten kannst.
              <div class="card shadow mb-4">
                <div class="card-header">
                  <strong class="card-title">Angebot details</strong>
                </div>
				
				
					
				
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
					
					
                      <div class="form-group mb-3">
                        <label for="simpleinput">Name des Angebotes</label>
                        <input name="name" type="text" id="simpleinput" class="form-control" placeholder="Name des Angebotes" maxlength="10">
						</input>
                      </div>
					  
                    </div> <!-- /.col -->
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="example-helping">Weitere Beschreibung</label>
                        <input name="description" type="text" id="helping" class="form-control" placeholder="Wenn du dein Angebot genauer beschreiben möchtest kannst du das einfach hier machen." maxlength="30">
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
                          <option value="1">Raum 1</option>
                          <option value="2">Raum 2</option>
                          <option value="3">Raum 3 (HS)</option>
                          <option value="4">Raum 4 (RS)</option>
                          <option value="5">Garten</option>
                          <option value="6">Sport/Ausflug</option>
                          <option value="7">Sonnenzimmer/Sonstiges</option>
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
                        <select name="time" class="form-control" id="type-select">
                          <option value="1">Zeit 1</option>
                          <option value="2">Zeit 2</option>
                          <option value="3">Zeit 3</option>
                          <option value="4">Zeit 4</option>
                          <option value="5">Zeit 5</option>
                          <option value="6">Zeit 6</option>
                          <option value="7">Zeit 7</option>
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
									echo date("d");
									echo "/";
									echo date("m");
									echo "/";
									echo date("Y");
								?>
							" aria-describedby="button-addon2">
							</input>
                          </div>
                      </div>
                    </div> <!-- /.card-body -->
                  </div> <!-- /.card -->
                </div> <!-- /.col -->
				
				
                <div class="col-md-6 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="custom-select">Wer macht diese Angebot?</label>
                        <select name="creator" class="form-control" id="custom-select">
                          <option value="<?php echo $id; ?>" selected><?php 
								echo $vorname;
								echo " ";
								echo $nachname;
							?> (Du selbst)</option>
                          <option value="1">Andere Person 1</option>
                          <option value="2">Andere Person 2</option>
                          <option value="3">Andere Person 3</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
				
				
                <div class="col-md-12 mb-4">
                  <div class="card shadow">
                    <div class="card-header">
                      <strong class="card-title">Zusätsliche Infomationen (Sind nur hier sichtbar und werden nicht auf dem Plan gezeigt)</strong>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <input name="notes" class="form-control form-control-lg" type="text" placeholder="Notizen" maxlength="255">
                      </div>
                    </div>
                  </div>
                </div>
				
                <div class="col-md-12 mb-4">
                        <button type="button" class="btn mb-2 btn-outline-primary">Zurück</button>
						
						
						
						<?php
						if($_GET['type'] == "new") {
							echo '<button type="button" class="btn mb-2 btn-outline-secondary" disabled="">Angebot Löschen</button>';
							echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success">Speichern</button>';
						} elseif (!$_GET['type'] == "new") {
							
							echo '<button type="button" class="btn mb-2 btn-outline-danger">Angebot Löschen</button>';
							echo '<button style="float:right;" type="button summit" class="btn mb-2 btn-outline-success">Aktualisieren</button>';
						}
						?>
						
						
                </div>
				
				
				
              </div> <!-- end section -->
            </div> <!-- .col-12 -->
			
			</form>
			
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
	
	
	
	
	  <!-- Custom JS code -->
	  
	  <script>
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