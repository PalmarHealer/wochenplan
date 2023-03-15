<?php
//Please set after __DIR__ the right relation to the files
$include_path = __DIR__ . "../../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico">

    <title>Benutzer verwalten</title>


    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
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
<body class="vertical  light  ">
<div class="wrapper">
    <?php
    $keep_pdo = true;
    include $include_path . "/include/nav.php";
    ?>
    <main role="main" class="main-content">
        <div class="container-fluid">

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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        GetAllUsersAndPrintThem($pdo, $permission_level_names);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn-box w-100 mt-4 mb-1 right">
                <a href="<?php echo $relative_path; ?>/admin/accounts/edit" type="button" class="btn mb-2 btn-primary">Benutzer Erstellen</a>
            </div>



        </div>
        <?php include $include_path . "/include/footer.php"; ?>
    </main> <!-- main -->
</div> <!-- .wrapper -->
<script src="<?php echo $relative_path; ?>/js/jquery.min.js"></script>
<script src="<?php echo $relative_path; ?>/js/popper.min.js"></script>
<script src="<?php echo $relative_path; ?>/js/moment.min.js"></script>
<script src="<?php echo $relative_path; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo $relative_path; ?>/js/simplebar.min.js"></script>
<script src='<?php echo $relative_path; ?>/js/daterangepicker.js'></script>
<script src='<?php echo $relative_path; ?>/js/jquery.stickOnScroll.js'></script>
<script src="<?php echo $relative_path; ?>/js/tinycolor-min.js"></script>
<script src="<?php echo $relative_path; ?>/js/config.js"></script>
<script src="<?php echo $relative_path; ?>/js/apps.js"></script>
<script src="<?php echo $relative_path; ?>/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $relative_path; ?>/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#dataTable').DataTable(
        {
            autoWidth: true,
            "lengthMenu": [
                [ -1, 4, 8, 16, 32, 64],
                ["All", 4, 8, 16, 32, 64]
            ]
        });
</script>
</body>
</html>