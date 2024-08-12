<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $manage_other_users, $permission_level, $permission_level_names, $webroot, $domain, $relative_path, $version, $id, $pdo;

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");

if (isset($_GET['login-to-id'])) {
    if ($permission_level >= 100) {
        $new_userid = $_GET['login-to-id'];
        $_SESSION['asl_userid']= $new_userid;
        Redirect($domain . '/dashboard');
    } else {
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

    <title>Benutzer verwalten</title>


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
                    <h2 class="mb-2 page-title">Benutzer</h2>
                    <!--<p class="card-text">Kleine Seiten Beschreibung</p> -->
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">


                            <div class="card shadow">
                                <div class="card-body">
                                    <!-- table -->
                                    <table class="table table-borderless table-hover" id="dataTable">
                                        <thead>
                                        <tr>
                                            <td>
                                            </td>
                                            <th></th>
                                            <th>Benutzername</th>
                                            <th>E-Mail</th>
                                            <th>Berechtigung</th>
                                            <th>Zuletzt geändert am</th>
                                            <th>Erstellt am</th>
                                            <th>Aktionen</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        GetAllUsersAndPrintThem($pdo, $permission_level_names, $permission_level, $manage_other_users);
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="btn-box w-100 mt-4 mb-1 right">
                                <a href="<?php echo $relative_path; ?>/admin/accounts/edit" type="button" class="btn mb-2 btn-primary right">Benutzer erstellen</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

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
<script src="<?php echo $relative_path; ?>/js/jquery.dataTables.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/dataTables.bootstrap4.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
<script>
    $('#dataTable').DataTable(
        {
            autoWidth: true,
            "lengthMenu": [
                [ 8, 16, 32, 64, -1],
                [ 8, 16, 32, 64, "Alle"]
            ]
        });
</script>
</body>
</html>