<style type="text/css">
    body {
        margin: 0;
    }
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
		border-radius: 0px 0px 0px 0px;
        margin: 0px auto;
        border-color: #ffffff;
        border-style: solid;
        border-width: 0.15vw;
        overflow: hidden;
        padding: 10px 5px;
    }
    .color-1 {
        background-color: #ecd3cd;
    }
    .color-2 {
        background-color: #f6e9e6;
    }
    .color-3 {
        background-color: #d09182;
    }
    .color-4 {
        background-color: #e5f4d4;
    }
    .color-5 {
        background-color: #f8e9be;
    }
    .full {
        width: 100%;
		height: 100%;
    }
    .piece {
        width: 10%;
    }
    .tmp {
        width: 20%;
    }
</style>



	<?php
		$include_path = __DIR__ . "/include";
		include $include_path . "/config.php";
	?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php $pdo = null; echo $path; ?>/favicon.ico">
	
    <title>Dashboard</title>
	
  </head>
  <body class="full">
	
    <table class="full">
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
