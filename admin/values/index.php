<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $manage_other_users, $permission_level, $webroot, $pdo, $relative_path, $version, $id;

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");



if (isset($_GET['delete-room'])) {
    DeleteSettingWithSuffix("rooms", $_GET['delete-room'], $pdo);
    Redirect("./");
}
if (isset($_GET['delete-time'])) {
    DeleteSettingWithSuffix("times", $_GET['delete-time'], $pdo);
    Redirect("./");
}
if (isset($_GET['save'])) {
    if ($_GET['save'] == "room" AND
        isset($_POST['name']) AND
        isset($_POST['id'])) {
        SetSettingWithSuffix("rooms", $_POST['id'], $_POST['name'], $pdo);
        Redirect("./");
    } elseif ($_GET['save'] == "time" AND
        isset($_POST['name']) AND
        isset($_POST['id'])) {
        SetSettingWithSuffix("times", $_POST['id'], $_POST['name'], $pdo);
        Redirect("./");
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

    <title>Variablen</title>


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
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) == "true") echo "disabled"; ?>>
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) != "true") echo "disabled"; ?>>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">

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
                    <h2 class="page-title">Variablen</h2>
                    <p> System ID's, damit der Wochenplan weiß wo die Angebote stattfinden können </p>
                    <div class="row">

                        <div class="col-md-6 my-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title">Räume</h5>
                                    <p class="card-text">Alle Räume die eingespeichert sind.</p>

                                    <form action="./?save=room" method="post">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th>System ID</th>
                                                <th>Name</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input name="id" required pattern='^[0-9]+$' title="Nur Zahlen sind erlaubt" class="form-control" type="text" placeholder="ID">
                                                </td>
                                                <td>
                                                    <input name="name" required class="form-control" type="text" placeholder="Name Des Raums">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn mb-2 btn-outline-primary send right-self">Hinzufügen</button>
                                                </td>
                                            </tr>
                                            <?php

                                            $array = GetSetting("rooms", $pdo);
                                            if (is_array($array)) {
                                                foreach ($array as $key => $value) {
                                                    echo "<tr><td>" . $key . "</td><td>" . $value . "</td><td>
<a type='button' href='./?delete-room=" . $key . "' class='btn btn-outline-danger send right-self'>Löschen</a>
</td></tr>";
                                                }
                                            } else {
                                                echo "<tr><td>". $array . "</td><td>" . $array . "</td></tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 my-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title">Zeiten</h5>
                                    <p class="card-text">Alle Zeiten die eingespeichert sind.</p>
                                    <form action="./?save=time" method="post">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th>System ID</th>
                                                <th>Name</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td>
                                                    <input name="id" required pattern='^[0-9]+$' title="Nur Zahlen sind erlaubt" class="form-control" type="text" placeholder="ID">
                                                </td>
                                                <td>
                                                    <input name="name" required class="form-control" type="text" placeholder="Name Der Zeit">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn mb-2 btn-outline-primary send right-self">Hinzufügen</button>
                                                </td>
                                            </tr>
                                            <?php

                                            $array = GetSetting("times", $pdo);
                                            if (is_array($array)) {
                                                foreach ($array as $key => $value) {
                                                    echo "<tr><td>" . $key . "</td><td>" . $value . "</td><td>
<a type='button' href='./?delete-time=" . $key . "' class='btn btn-outline-danger send right-self'>Löschen</a>
</td></tr>";
                                                }
                                            } else {
                                                echo "<tr><td>". $array . "</td><td>" . $array . "</td></tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end section -->
                </div>
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
<script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
</body>
</html>