<?php
$include_path = __DIR__ . "/..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $relative_path, $version, $id, $pdo;

if (!isset($_GET["date"])) {
  $_GET["date"] = date("Y-m-d", time());
  if (isset($_GET["skip"])) {
    Redirect("./?skip=1&date=" . date("Y-m-d", time()));
  } else {
    Redirect("./?date=" . date("Y-m-d", time()));
  }
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

      <title>Plan - <?php echo date('d.m.Y', strtotime($_GET["date"])); ?></title>

      <!-- Simple bar CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
      <!-- Fonts CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/abel.css?version=<?php echo $version; ?>">
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

  <body>
  <div class="full">
      <div class="progress mb-3" style="height: 30px; width: 50vw; top: 50%; position: relative; left: 25%;">
          <div class="progress-bar bg-primary" role="progressbar" style="width: 0;"></div>
      </div>
  </div>
  </body>


  <script src="<?php echo $relative_path; ?>/js/jquery.min.js?version=<?php echo $version; ?>"></script>
  <script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
  <script src="<?php echo $relative_path; ?>/js/planDisplay.js?version=<?php echo $version; ?>"></script>
  <script>
      <?php
      if (!isset($_GET['debug'])) echo "setInterval(fetchData, 6000);";
      if (!isset($_GET['load'])) echo "fetchData(true);";
      ?>

      function fetchData(fullReload = false, dateParam) {
          const urlParams = new URLSearchParams(window.location.search);
          const dateValue = dateParam || urlParams.get('date');
          let modeData = urlParams.get('mode') || 'normal';
          let deferred = $.Deferred();

          $.ajax({
              url: `./reload<?php echo ($_GET['version'] ?? '3') ?>.php`,
              type: "POST",
              data: {
                  date: dateValue,
                  mode: modeData
              },
              cache: false,
              success: function(data) {
                  if (fullReload || isUpdating) {
                      console.log("Data loaded");
                      updateBar = false;
                      $('.progress-bar').css('width', '100%');
                      setTimeout(function() {
                          $(".full").fadeOut(250, function() {
                              $(this).html(data).fadeIn(250);
                              deferred.resolve(true);
                          });
                      }, 250);
                  } else {
                      updateBar = false;
                      console.log("Data reloaded");
                      $('.full').html(data);
                      deferred.resolve(true);
                  }
              },
              error: function() {
                  console.error("Failed to load data");
                  deferred.reject(false);
              }
          });

          return deferred.promise();
      }
  </script>
</html>
