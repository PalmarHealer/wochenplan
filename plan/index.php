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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
      <script>

          $(document).ready(function() {
              // Lade Daten 2 Sekunden nach dem Laden der Seite
              setTimeout(reloadData, 2000);

              var intval = <?php echo ($_GET['interval'] ?? '1'); ?>;
          });

          function reloadData() {
              $.ajax({
                  url: "./reload.php",
                  type: "POST",
                  data: { date: "<?php echo $current_day; ?>" },
                  cache: false,
                  success: function(data) {
                      console.log("Data reloaded");
                      $(".full").html(data);

                      var intval = <?php echo ($_GET['interval'] ?? '1'); ?>;
                      setInterval(reloadData, intval * 1000);
                  }
              });
          }


      </script>
  </head>
  <body class="full">

<h1>Loading <div class="dots"><span class="dot z"></span><span class="dot f"></span><span class="dot s"></span><span class="dot t"><span class="dot l"></span></span></div></h1>
<style>@import url(https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap);body,h1{box-sizing:border-box}.dot .l,.dot.z{position:absolute}body{display:grid;place-content:center;min-height:100vh;margin:0;padding:2px;overflow:hidden}h1{font-family:"Open Sans",-apple-system,"Segoe UI",sans-serif;font-size:50px;font-weight:700;color:#212121}.dots{display:inline-flex}.dots--animate .dot.z{-webkit-animation:.8s .2s forwards scale;animation:.8s .2s forwards scale}.dots--animate .dot.f,.dots--animate .dot.s{-webkit-animation:.5s forwards right;animation:.5s forwards right}.dots--animate .dot.l{-webkit-animation:.4s linear .1s forwards rightDown,2s linear .4s forwards drop;animation:.4s linear .1s forwards rightDown,2s linear .4s forwards drop}.dot{display:inline-block;width:10px;height:10px;background:#212121;border-radius:10px;position:relative;margin-left:6px}.dot.z{transform:scale(0)}@-webkit-keyframes scale{100%{transform:scale(1)}}@keyframes scale{100%{transform:scale(1)}}.dot.f,.dot.s{transform:translateX(0)}@-webkit-keyframes right{100%{transform:translateX(16px)}}@keyframes right{100%{transform:translateX(16px)}}.dot.t{background:0 0}.dot .l{margin-left:0;top:0;left:0}@-webkit-keyframes rightDown{50%{top:4px;left:16px}100%{top:12px;left:24px}}@keyframes rightDown{50%{top:4px;left:16px}100%{top:12px;left:24px}}@-webkit-keyframes drop{100%{transform:translate(70px,calc(35px + (100vh/2)))}}@keyframes drop{100%{transform:translate(70px,calc(35px + (100vh/2)))}}</style>
<script>let $=t=>document.querySelector(t),dots=$(".dots");function animate(t,a){t.classList.add(a),setTimeout(()=>{t.classList.remove(a),setTimeout(()=>{animate(t,a)},500)},2500)}animate(dots,"dots--animate");</script>


  </body>


</html>
