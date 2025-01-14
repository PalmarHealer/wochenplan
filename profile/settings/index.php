<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $relative_path, $version, $pdo, $id, $vorname_neu, $vorname, $nachname_neu, $nachname;

if (isset($_GET['save']) AND $_SERVER["REQUEST_METHOD"] == "POST") {
    $vorname_neu = $_POST['vorname'];
    $nachname_neu = $_POST['nachname'];
    echo UpdateUsername($id, $vorname_neu, $nachname_neu, $pdo);
    Redirect("./");
}


?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">
	
    <title>Einstellungen</title>
	
	
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
    <!-- Fonts CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/overpass.css?version=<?php echo $version; ?>">
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
  <body class="vertical light">
  <div class="wrapper">
      <?php
      $keep_pdo = true;
      include $include_path. "/include/nav.php";
      ?>
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
              <h2 class="h3 mb-4 page-title">Einstellungen</h2>

              <div class="my-4">

                <form action="?save=1" method="post">
                  <div class="row mt-5">
                      <h4 class="name-badge mb-1 margin-auto"><?php if(isset($_GET['save'])) { echo $vorname_neu; } else { echo $vorname; } echo " "; if(isset($_GET['save'])) { echo $nachname_neu; } else { echo $nachname; } ?></h4>
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
                  <div class="right">
                  <button type="submit" class="btn btn-primary">Name speichern</button>
                  </div>
                </form>




                  <h5 class="mb-0 mt-5">Account Einstellungen</h5>
                  <p>Hier kannst du einstellungen treffen wie sich der Wochenplan verhalten soll.</p>
                  <div class="list-group mb-5 shadow">

                      <div class="list-group-item">
                          <div class="row align-items-center">
                              <div class="col">
                                  <strong class="mb-2">Dark Mode</strong>
                                  <?php
                                  $tmp = GetUserSetting($id, "darkMode", $pdo);
                                  if ($tmp == "" OR $tmp == "false") {
                                      $value = "true";
                                      $text = "Aktivieren";
                                  } else {
                                      $value = "false";
                                      $text = "Deaktivieren";
                                  }
                                  ?>
                                  <span for="darkMode" class="badge badge-pill badge-success hidden">Gespeichert</span>
                                  <p class="text-muted mb-0">Aktiviere den Dark Mode.</p>
                              </div> <!-- .col -->
                              <div class="col-auto">
                                  <button value="<?php echo $value; ?>" id="darkMode" class="settingButton btn btn-primary btn-sm"><?php echo $text; ?></button>
                              </div> <!-- .col -->
                          </div> <!-- .row -->
                      </div> <!-- .list-group-item -->

                      <div class="list-group-item">
                          <div class="row align-items-center">
                              <div class="col">
                                  <strong class="mb-2">Google Analytics</strong>
                                  <?php
                                  $tmp = GetUserSetting($id, "analytics", $pdo);
                                  if ($tmp == "" OR $tmp == "false") {
                                      $value = "true";
                                      $text = "Aktivieren";
                                  } else {
                                      $value = "false";
                                      $text = "Deaktivieren";
                                  }
                                  ?>
                                  <span for="analytics" class="badge badge-pill badge-success hidden">Gespeichert</span>
                                  <p class="text-muted mb-0">Erlaube, dass der Wochenplan anonymisierte Nutzerdaten über dich sammeln darf, um ihn zu verbessern.</p>
                              </div> <!-- .col -->
                              <div class="col-auto">
                                  <button value="<?php echo $value; ?>" id="analytics" class="settingButton btn btn-primary btn-sm"><?php echo $text; ?></button>
                              </div> <!-- .col -->
                          </div> <!-- .row -->
                      </div> <!-- .list-group-item -->

                      <div class="list-group-item">
                          <div class="row align-items-center">
                              <div class="col">
                                  <strong class="mb-2">Experimentelles Seiten laden</strong>
                                  <?php
                                  $tmp = GetUserSetting($id, "experimentalSiteLoading", $pdo);
                                  if ($tmp == "" OR $tmp == "false") {
                                      $value = "true";
                                      $text = "Aktivieren";
                                  } else {
                                      $value = "false";
                                      $text = "Deaktivieren";
                                  }
                                  ?>
                                  <span for="experimentalSiteLoading" class="badge badge-pill badge-success hidden">Gespeichert</span>
                                  <p class="text-muted mb-0">Aktiviere das neue (noch experimentelle) Seitenladen, um die Ladezeiten zu verkürzen.</p>
                              </div> <!-- .col -->
                              <div class="col-auto">
                                  <button value="<?php echo $value; ?>" id="experimentalSiteLoading" class="settingButton btn btn-primary btn-sm"><?php echo $text; ?></button>
                              </div> <!-- .col -->
                          </div> <!-- .row -->
                      </div> <!-- .list-group-item -->

                  </div> <!-- .list-group -->
              </div> <!-- /.card-body -->
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
            $('button.settingButton').click(function() {
                var button = $(this);
                var currentValue = button.attr('value');
                var newValue = (currentValue === 'true') ? 'false' : 'true';

                sendDataToServer($(this).attr("id"), $(this).attr("value"), function() {
                    button.text(newValue === 'true' ? 'Aktivieren' : 'Deaktivieren');
                    button.attr('value', newValue);
                    var analyticsMessage = $('[for="' + button.attr('id') + '"]');
                    analyticsMessage.show().delay(500).fadeOut();
                });
            });
            $('#darkMode').on('click', function() {
                $('body').addClass('transition');

                setTimeout(function() {
                    $('#lightTheme').prop('disabled', function(_, attr) { return !attr });
                    $('#darkTheme').prop('disabled', function(_, attr) { return !attr });

                    setTimeout(function() {
                        $('body').removeClass('transition');
                    }, 1000);
                }, 50);
            });
        });
        function sendDataToServer(setting, value, onSuccess) {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    type: 'setUserSetting',
                    setting: setting,
                    value: value
                },
                success: function(response) {
                    if (typeof onSuccess === 'function') {
                        onSuccess(response);
                    } else {
                        console.log('Response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>
  </body>
</html>