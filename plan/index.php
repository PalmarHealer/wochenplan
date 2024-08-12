<?php
global $relative_path, $version;
$include_path = __DIR__ . "/..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";



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

  <body class="full">
  <div class="progress mb-3" style="height: 30px; width: 50vw; top: 50%; position: relative; left: 25%;">
      <div class="progress-bar bg-primary" role="progressbar" style="width: 0;"></div>
  </div>
  </body>


  <script src="<?php echo $relative_path; ?>/js/jquery.min.js?version=<?php echo $version; ?>"></script>
  <script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
  <script>
      let isUpdating = false;
      <?php
      if (!isset($_GET['debug'])) echo "setInterval(fetchData, 6000);";
      if (!isset($_GET['load'])) echo "fetchData(true);";
      ?>

      $(document).ready(function() {
          updateProgressBar();
      });

      let updateBar = true;
      function updateProgressBar() {
          const progressBar = $('.progress-bar');
          const currentValue = progressBar.width() / progressBar.parent().width() * 100;
          if (currentValue < 100 && updateBar) {
              progressBar.css('width', currentValue + 5 + '%');
              setTimeout(updateProgressBar, 100);
          }
      }

      function customPrint() {
        if (navigator.userAgent.indexOf("Firefox") === -1) {
          // Browser is not Firefox
          window.print();
        } else {
          // Browser is Firefox
          const html = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Nicht Unterstützt</strong> das Drucken in Firefox wird leider nicht unterstützt.<button onclick="closePrintAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>`;
          $(".alert-message").html(html);
            }

      }
      function closePrintAlert() {
        $(".alert-dismissible").fadeOut();
      }

      const elem = document.documentElement;
      function openFullscreen() {
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
          elem.msRequestFullscreen();
        }
          $(".open_fullscreen").hide();
          $(".close_fullscreen").show();
      }

      function closeFullscreen() {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
          document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
          document.msExitFullscreen();
        }
          $(".open_fullscreen").show();
          $(".close_fullscreen").hide();
      }

      function fetchData(fullReload = false, dateParam) {
          const urlParams = new URLSearchParams(window.location.search);
          const dateValue = dateParam || urlParams.get('date');
          let modeData = urlParams.get('mode') || 'normal';
          let deferred = $.Deferred();  // Create a Deferred object

          $.ajax({
              url: `./reload<?php echo ($_GET['version'] ?? '3') ?>.php`,
              type: "POST",
              data: {
                  date: dateValue,
                  mode: modeData
              },
              cache: false,
              success: function(data) {
                  if (fullReload) {
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
                      console.log("Data reloaded");
                      $('.content').html(data);
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


      function updateDateInUrl(daysToAddOrSubtract, element) {
          if (isUpdating) {
              console.log("Update is already in progress.");
              return $.Deferred().reject().promise();
          }
          isUpdating = true;

          const urlParams = new URLSearchParams(window.location.search);
          const dateString = urlParams.get('date');
          const date = dateString ? new Date(dateString) : new Date();

          // Initially add or subtract days
          date.setDate(date.getDate() + daysToAddOrSubtract);

          // Skip weekends: Adjust date depending on whether moving forwards or backwards
          if (daysToAddOrSubtract > 0) {
              // Moving forward
              while (date.getDay() === 0 || date.getDay() === 6) {
                  if (date.getDay() === 6) {
                      date.setDate(date.getDate() + 2); // If Saturday, jump to Monday
                  } else {
                      date.setDate(date.getDate() + 1); // If Sunday, just move to Monday
                  }
              }
          } else {
              // Moving backward
              while (date.getDay() === 0 || date.getDay() === 6) {
                  if (date.getDay() === 0) {
                      date.setDate(date.getDate() - 2); // If Sunday, jump to Friday
                  } else {
                      date.setDate(date.getDate() - 1); // If Saturday, move to Friday
                  }
              }
          }

          const year = date.getFullYear();
          const month = date.getMonth() + 1;
          const day = date.getDate();
          const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
          const newUrl = window.location.origin + window.location.pathname + '?date=' + formattedDate;
          window.history.replaceState({}, document.title, newUrl);

          document.title = "Plan - " + formattedDate.split('-').reverse().join('.');

          $(element).removeClass().addClass('spinner-border spinner-border-sm btn-spinner disabled_cursor');

          return fetchData(true, formattedDate);
      }

  </script>
</html>
