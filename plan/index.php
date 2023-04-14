<?php
$include_path = __DIR__ . "/..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";



if (!isset($_GET["date"])) {
    $current_day = date("Y-m-d",time());
} else {
    $current_day = $_GET["date"];
}
?>
<!-- Custom CSS (this has to be here, otherwise it will do randomly things) -->
<link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css">
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico">

      <title>Plan - <?php echo date('d.m.Y', strtotime($current_day)); ?></title>

      <!-- Simple bar CSS -->
      <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css">
      <!-- Fonts CSS -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Abel">
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

  <body class="full">

  <h1>
      Loading
      <div class="dots">
          <span class="dot z"></span><span class="dot f"></span><span class="dot s"></span><span class="dot t"><span class="dot l"></span></span>
      </div>
  </h1>
  <style>
      @import url(https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap);
      body,
      h1 {
          box-sizing: border-box;
      }
      .dot .l,
      .dot.z {
          position: absolute;
      }
      body {
          display: grid;
          place-content: center;
          min-height: 100vh;
          margin: 0;
          padding: 2px;
          overflow: hidden;
      }
      h1 {
          font-family: "Open Sans", -apple-system, "Segoe UI", sans-serif;
          font-size: 50px;
          font-weight: 700;
          color: #212121;
      }
      .dots {
          display: inline-flex;
      }
      .dots--animate .dot.z {
          -webkit-animation: 0.8s 0.2s forwards scale;
          animation: 0.8s 0.2s forwards scale;
      }
      .dots--animate .dot.f,
      .dots--animate .dot.s {
          -webkit-animation: 0.5s forwards right;
          animation: 0.5s forwards right;
      }
      .dots--animate .dot.l {
          -webkit-animation: 0.4s linear 0.1s forwards rightDown, 2s linear 0.4s forwards drop;
          animation: 0.4s linear 0.1s forwards rightDown, 2s linear 0.4s forwards drop;
      }
      .dot {
          display: inline-block;
          width: 10px;
          height: 10px;
          background: #212121;
          border-radius: 10px;
          position: relative;
          margin-left: 6px;
      }
      .dot.z {
          transform: scale(0);
      }
      @-webkit-keyframes scale {
          100% {
              transform: scale(1);
          }
      }
      @keyframes scale {
          100% {
              transform: scale(1);
          }
      }
      .dot.f,
      .dot.s {
          transform: translateX(0);
      }
      @-webkit-keyframes right {
          100% {
              transform: translateX(16px);
          }
      }
      @keyframes right {
          100% {
              transform: translateX(16px);
          }
      }
      .dot.t {
          background: 0 0;
      }
      .dot .l {
          margin-left: 0;
          top: 0;
          left: 0;
      }
      @-webkit-keyframes rightDown {
          50% {
              top: 4px;
              left: 16px;
          }
          100% {
              top: 12px;
              left: 24px;
          }
      }
      @keyframes rightDown {
          50% {
              top: 4px;
              left: 16px;
          }
          100% {
              top: 12px;
              left: 24px;
          }
      }
      @-webkit-keyframes drop {
          100% {
              transform: translate(70px, calc(35px + (100vh / 2)));
          }
      }
      @keyframes drop {
          100% {
              transform: translate(70px, calc(35px + (100vh / 2)));
          }
      }
  </style>

  </body>




  <script src="<?php echo $relative_path; ?>/js/jquery.min.js"></script>
  <script>
      <?php
          if (isset($_GET['skip'])) {
              echo "reloadData();";
              echo "setInterval(reloadData, 6000);";
          }
          else {
              echo '$(document).ready(function() {
                        let $ = (t) => document.querySelector(t),
                        dots = $(".dots");
                        animate(dots, "dots--animate");
                         setInterval(reloadData, 6000);
                    });';
          }
      ?>

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

      function updateDateInUrl(daysToAddOrSubtract) {
          // Holen Sie das Datum aus der GET-Variable "date" im Format "YYYY-MM-DD"
          const urlParams = new URLSearchParams(window.location.search);
          const dateString = urlParams.get('date');

          // Wenn die Variable nicht existiert, verwenden Sie das aktuelle Datum
          const date = dateString ? new Date(dateString) : new Date();

          // Fügen Sie die angegebene Anzahl von Tagen zum Datum hinzu oder ziehen Sie sie ab
          date.setDate(date.getDate() + daysToAddOrSubtract);

          // Konvertieren Sie das Datum in das erforderliche Format "YYYY-MM-DD"
          const formattedDate = date.toISOString().slice(0, 10);

          // Erstellen Sie die neue URL mit dem aktualisierten Datum und dem Parameter "skip"
          const newUrl = window.location.origin + window.location.pathname + '?date=' + formattedDate + '&skip=1';

          // Aktualisieren Sie die URL der aktuellen Seite
          window.history.replaceState({}, document.title, newUrl);

          // Laden Sie die Seite neu
          window.location.reload();
      }



      function reloadData() {
          $.ajax({
              url: "./reload<?php echo ($_GET['version'] ?? '2') ?>.php",
              type: "POST",
              data: { date: "<?php echo $current_day; ?>" },
              cache: false,
              success: function(data) {
                  console.log("Data reloaded");
                  $(".full").html(data);

              }
          });
      }

      function animate(t, a) {
          t.classList.add(a),
              setTimeout(() => {
                  t.classList.remove(a),
                      setTimeout(() => {
                          animate(t, a);
                      }, 500);
              }, 2500);
      }

  </script>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-YL7H2T9DF4"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-YL7H2T9DF4');
  </script>


</html>
