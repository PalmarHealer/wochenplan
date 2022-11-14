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
	
    <title>Settings</title>
	
	
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
		
		if(isset($_GET['save'])) {
			$vorname_neu = $_POST['vorname'];
			$nachname_neu = $_POST['nachname'];
			$email_neu = $_POST['email'];
		
			$rstatement = $pdo->prepare("UPDATE users SET vorname = :vorname_neu, email = :email_neu, nachname = :nachname_neu WHERE id = :id");
			$rstatement->execute(array('id' => $id, 'email_neu' => $email_neu, 'vorname_neu' => $vorname_neu, 'nachname_neu' => $nachname_neu));
			
			header('Location: ./');
		}
		$pdo = null;
	  ?>
	  
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
              <h2 class="h3 mb-4 page-title">Settings</h2>
              <div class="my-4">
                
                <form action="?save=1" method="post">
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
                          <h4 class="name-badge mb-1"><?php if(isset($_GET['save'])) { echo $vorname_neu; } else { echo $vorname; } ?>, <?php if(isset($_GET['save'])) { echo $nachname_neu; } else { echo $nachname; } ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="my-4">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="firstname">Vorname</label>
                      <input name="vorname" type="text" id="firstname" class="form-control" value="<?php if(isset($_GET['save'])) { echo $vorname_neu; } else { echo $vorname; } ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastname">Nachname</label>
                      <input name="nachname" type="text" id="lastname" class="form-control" value="<?php if(isset($_GET['save'])) { echo $nachname_neu; } else { echo $nachname; } ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail4">Email</label>
                    <input name="email" type="email" class="form-control" id="inputEmail4" value="<?php if(isset($_GET['save'])) { echo $email_neu; } else { echo $email; } ?>">
                  </div>
                  
                  <button type="submit" class="btn btn-primary">Save Change</button>
                </form>
              </div> <!-- /.card-body -->
            </div> <!-- /.col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php include ("../../include/footer.php"); ?>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
	<script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/moment.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/simplebar.min.js"></script>
    <script src='../../js/daterangepicker.js'></script>
    <script src='../../js/jquery.stickOnScroll.js'></script>
    <script src="../../js/tinycolor-min.js"></script>
    <script src="../../js/config.js"></script>
    <script src="../../js/apps.js"></script>
  </body>
</html>