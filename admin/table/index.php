<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");


if (isset($_GET['load']) and !($_GET['load'] == '')) {
    $plan = GetSettingWithSuffix("plan", $_GET['load'], $pdo);
} else {
    $plan = GetSettingWithSuffix("plan", "template", $pdo);
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
    <link href="<?php echo $relative_path; ?>/css/overpass.css?version=<?php echo $version; ?>" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/feather.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/dataTables.bootstrap4.css?version=<?php echo $version; ?>">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/daterangepicker.css?version=<?php echo $version; ?>">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" disabled>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">
    <!-- Custom Site CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customTableStyle.css?version=<?php echo $version; ?>">

</head>

<body class="vertical  light  ">
<div class="wrapper">
    <?php
    $keep_pdo = true;
    include $include_path . "/include/nav.php";
    ?>
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="h3 mb-4 page-title">Tabellen Struktur</h2>
                    <div id="tableContainer">
                        <div class="card-header display-contents">
                            <form class="display-flex">
                                <div class="form-group margin-10">
                                    <label class="col-form-label" for="label">Text:</label>
                                    <div class="input-group mb-3">

                                        <input placeholder="N/A" type="text" id="label" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text fe fe-24 fe-file-text"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-10">
                                    <label class="col-form-label" for="time">Zeit ID:</label>
                                    <div class="input-group mb-3">

                                        <input placeholder="N/A" type="text" id="time" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text fe fe-24 fe-clock"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-10">
                                    <label class="col-form-label" for="room">Raum ID:</label>
                                    <div class="input-group mb-3">

                                        <input placeholder="N/A" type="text" id="room" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text fe fe-24 fe-home"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-10">
                                    <label class="col-form-label" for="color">Farbe:</label>
                                    <div class="input-group mb-3">
                                        <select id="color" name="color" class="form-control" onchange="dyeCells()">
                                            <option disabled selected value="1">Farbe auswählen</option>
                                            <?php
                                            $array = GetSetting("colors", $pdo);
                                            if (is_array($array)) {
                                                ksort($array);
                                                foreach ($array as $key => $value) {
                                                    echo "<option value='" . $value . "'>" . $key . "</option>";
                                                }
                                            } else {
                                                echo "<option value='". $array . "'>" . $array . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text fe fe-24 fe-droplet"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin-10">
                                    <label class="col-form-label" for="name">Name der Plan Version:</label>
                                    <div class="input-group mb-3">

                                        <input placeholder="N/A" type="text" id="name" class="form-control name" value="<?php if (isset($_GET['load']) and !($_GET['load'] == '')) echo $_GET['load']; ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text fe fe-24 fe-type"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class=" buttons">

                                <button class="btn mb-2 btn-primary" onclick="addRow()">Zeile hinzufügen</button>
                                <button class="btn mb-2 btn-primary" onclick="addColumn()">Splate hinzufügen</button>
                                <button class="btn mb-2 btn-primary" onclick="deleteCells()">Zellen löschen</button>
                                <button class="btn mb-2 btn-primary" onclick="mergeCells()">Zellen zusammenführen</button>
                                <button class="btn mb-2 btn-primary" onclick="splitCells()">Zellen teilen</button>
                                <button class="btn mb-2 btn-primary" onclick="toggleCenterText()">Toggle Text Center</button>
                                <button class="btn mb-2 btn-primary send" onclick="sendData()">Speichern</button>
                            </div>
                        </div>
                        <table class="card-body" id="editableTable">
                            <?php
                            if (is_array($plan)) {
                                $plan2 = str_replace('"', '(quotes)', reset($plan));
                            } else {
                                $plan2 = str_replace('"', '(quotes)', $plan);
                            }
                            $plan2 = DecodeFromJson($plan2);
                            $plan2 = str_replace('(quotes)', '"', $plan2);
                            echo $plan2;
                            ?>
                        </table>
                    </div>
                </div> <!-- /.col-12 -->
            </div> <!-- .row -->
        </div>
        <?php include $include_path . "/include/footer.php"; ?>
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
<script src="<?php echo $relative_path; ?>/js/table.custom.php?version=<?php echo $version; ?>"></script>
</body>
</html>