
<?php
	$include_path = __DIR__ . "/include";
	include $include_path . "/config.php";
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
	
    <title>Plan</title>
	
	
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
  </head>
  <body class="full">
	
    <table class="full tg">
        <colgroup>
        <col/>
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
        <tr>
            <th class="color-3">Tag</th>
            <th class="color-1" colspan="4"></th>
            <th class="color-3" colspan="5"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="color-3">Datum</td>
            <td class="color-2">Raum 1</td>
            <td class="color-2">Raum 2<br /></td>
            <td class="color-2">Raum 3<br /></td>
            <td class="color-2">Raum4</td>
            <td class="color-2">Gesprächsraum</td>
            <td class="color-2">SZ/Praxisber.</td>
            <td class="color-2">Sport</td>
            <td class="color-2">Ausflug<br /></td>
            <td class="color-1">Freiarbeit</td>
        </tr>
        <tr>
            <td class="color-1">
                8:00 – 9:00<br />
                Morgenband
            </td>
            <td class="color-2" colspan="8">Ankommen</td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1">
                9:00 - 10:00<br />
                Morgenband
            </td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2"></td>
            <td class="color-2" rowspan="3"></td>
            <td class="color-2" rowspan="3"></td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1">
                10:00 – 10:30<br />
                Morgenkreise<br />
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
            <td class="color-1">
                10:30 – 12:00<br />
                Großes Band
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
            <td class="color-1">
                12:00 – 13:00<br />
                Mittagspause
            </td>
            <td class="color-4" colspan="8">Mittagessen</td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1">
                13:00 – 14:30<br />
                <br />
                Nachmittags-<br />
                band
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
        <tr>
            <td class="color-1" rowspan="2">
                14:30 – 15:00<br />
                Putzen
            </td>
            <td class="color-5" colspan="8"></td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-5" colspan="2"></td>
            <td class="color-5" colspan="3"></td>
            <td class="color-5" colspan="3"></td>
            <td class="color-1"></td>
        </tr>
        <tr>
            <td class="color-1"></td>
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
