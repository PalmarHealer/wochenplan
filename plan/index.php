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


  </body>




  <script src="../js/jquery.min.js"></script>
  <script>

      $(document).ready(function() {
          reloadData();

          //setInterval(reloadData, 6000);

      });

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

  </script>

</html>
