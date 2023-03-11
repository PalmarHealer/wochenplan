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
  </head>
  <body class="full">

  <table class="full tg">
      <colgroup>
          <col class="piece"/>
          <col/>
          <col/>
          <col/>
          <col/>
          <col/>
          <col/>
          <col/>
          <col/>
          <col/>
      </colgroup>
      <thead>
      <tr class="small_piece center">
          <th class="color-3 no_border">
              <b class="white_text modt">
                  <?php
                        $weekday = (new DateTime($current_day))->format('N');
                        echo $weekday_names[$weekday];
                    ?>
                </b>
            </th>
            <th class="color-1" colspan="4"></th>
            <th class="color-3" colspan="5"></th>
        </tr>
    </thead>
    <tbody>
        <tr class="name-badge center">
            <td class="color-3 no_border"><b class="white_text heading"><?php echo date('d.m.Y', strtotime($current_day)); ?></b></td>
            <td class="color-2 no_border"><b class="bold">Raum 1</b></td>
            <td class="color-2 no_border"><b class="bold">Raum 2</b></td>
            <td class="color-2 no_border"><b class="bold">Raum 3 (HS)</b></td>
            <td class="color-2 no_border"><b class="bold">Raum 4 (RS)</b></td>
            <td class="color-2 no_border">Gesprächsraum</td>
            <td class="color-2 no_border">SZ/Praxisber.</td>
            <td class="color-2 no_border">Sport</td>
            <td class="color-2 no_border">Ausflug</td>
            <td class="color-1 no_border">Freiarbeit</td>
        </tr>
        <tr class="small_piece">
            <td class="color-1 no_border">
                8:00 – 9:00<br />
                <b class="bold">Morgenband</b>
            </td>
            <td class="color-2 no_border center" colspan="8"><p>Ankommen</p></td>
            <td class="color-1"></td>
        </tr>
        <tr class="piece">
            <td class="color-1 no_border">
                9:00 - 10:00<br />
                <b class="bold">Morgenband</b>
            </td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 1, $pdo); ?></td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 2, $pdo); ?></td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 3, $pdo); ?></td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 4, $pdo); ?></td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 5, $pdo); ?></td>
            <td class="color-2 db_text"><?php PrintLesson($current_day, 1, 6, $pdo); ?></td>
            <td class="color-2 db_text" rowspan="3"><?php PrintLesson($current_day, 1, 7, $pdo); ?></td>
            <td class="color-2 db_text" rowspan="3"><?php PrintLesson($current_day, 1, 8, $pdo); ?></td>
            <td class="color-1 db_text"></td>
        </tr>
        <tr class="piece">
            <td class="color-1 no_border">
                10:00 – 10:30<br/>
                <b class="bold">Morgenkreise</b>
            </td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1 no_border">
                10:30 – 12:00<br/>
                <b class="bold">Großes Band</b>
            </td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-1"></td>
        </tr>
        <tr class="breakfast">
            <td class="color-1 no_border">
                12:00 – 13:00<br/>
                <b class="bold">Mittagspause</b>
            </td>
            <td class="color-4 no_border" colspan="8"><b class="bold">Mittagessen</b> </td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1 no_border">
                13:00 – 14:30<br/>
                <b class="bold">Nachmittags-<br/>
                    band</b>
            </td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-1"></td>
        </tr>
        <tr class="small_piece">
            <td class="color-1 no_border" rowspan="2">
                14:30 – 15:00<br/>
                <b class="bold">Putzen</b>
            </td>
            <td class="color-5" colspan="8"></td>
            <td class="color-1"></td>
        </tr>
        <tr class="small_piece">
            <td class="color-5" colspan="2"></td>
            <td class="color-5" colspan="3"></td>
            <td class="color-5" colspan="3"></td>
            <td class="color-1"></td>
        </tr>
        <tr class="piece">
            <td class="color-1 no_border">
                13:00 – 14:30<br/>
                <b class="bold">Spätes Band</b>
            </td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-1"></td>
        </tr>
        </tbody>
    </table>
	
	
  </body>
</html>
