<?php
    $include_path = __DIR__ . "/..";
    require $include_path . "/dependencies/config.php";
    require $include_path . "/dependencies/mysql.php";
    require $include_path . "/dependencies/framework.php";
    global $relative_path, $pdo, $permission_level, $manage_other_users, $version, $id;

    $maintenance = GetSetting("maintenance", $pdo);
    if ((!$maintenance) OR $permission_level >= $manage_other_users) {
        Redirect("../");
    }

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../favicon.ico">
    <title>Maintenance</title>
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) == "true") echo "disabled"; ?>>
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) != "true") echo "disabled"; ?>>
</head>
<body class="light ">
<div class="wrapper vh-100">
    <div class="align-items-center h-100 d-flex mx-auto">
        <div class="mx-auto text-center">

            <h1 class="display-1 m-0 font-weight-bolder text-muted" style="font-size:80px;">Serverarbeiten</h1>

            <img src="<?php echo $relative_path; ?>/img/maintenance.svg"  alt="GitHub" class="logo-footer">
            <hr>
            <a class="lesson-details-btn btn mb-2 btn-outline-secondary" href="<?php echo $relative_path; ?>/login">Zum Login (nur für Admins)</a>
        </div>
    </div>
</div>
</body>
</html>
</body>