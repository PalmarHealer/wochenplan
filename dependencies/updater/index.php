<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $relative_path, $version, $pdo, $webroot, $id, $manage_other_users, $permission_level;

CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");
if (isset($_GET['installVersion'])) {
    $installVersion = $_GET['installVersion'];
    $json_url = 'https://raw.githubusercontent.com/PalmarHealer/wochenplan/main/dependencies/versioner.json';
    $json_data = file_get_contents($json_url);
    $versions = json_decode($json_data, true);

    $downloadLink = null;
    foreach ($versions['versions'] as $Onlineversion) {
        if ($Onlineversion['version'] === $installVersion) {
            $downloadLink = $Onlineversion['download_link'];
            break;
        }
    }

    if ($downloadLink) {
        $installDir = __DIR__ . "/tmp"; // Using $include_path defined at the top of the script

        if (!is_dir($installDir)) {
            mkdir($installDir, 0755, true);
        }

        $zipFile = $installDir . '/update.zip';

        // Download the file
        file_put_contents($zipFile, file_get_contents($downloadLink));

        // Unzip the file
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $rootFolder = $zip->getNameIndex(0); // Get the first item, assuming it's the root folder
            $rootFolder = rtrim($rootFolder, '/'); // Ensure there's no trailing slash

            $createdDirectories = []; // Track directories actually used for storing files
            $targetPath = $include_path; // Define this to wherever you need the contents to go

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileInfo = pathinfo($filename);
                // Skip unwanted files
                if ($filename === $rootFolder . '/dependencies/config.php' || $filename === $rootFolder . '/dependencies/updater/index.php') {
                    continue;
                }
                if (!array_key_exists('extension', $fileInfo)) {
                    // Skip directories
                    continue;
                }
                // Build the source and target paths
                $sourcePath = "zip://" . $zipFile . "#" . $filename;
                $relativePath = substr($filename, strlen($rootFolder) + 1);
                $finalPath = $targetPath . '/' . $relativePath;
                $directoryPath = dirname($finalPath);
                if (!in_array($directoryPath, $createdDirectories)) {
                    if (!is_dir($directoryPath)) {
                        mkdir($directoryPath, 0755, true);
                    }
                    $createdDirectories[] = $directoryPath;
                }
                // Copy file from zip to the target location
                copy($sourcePath, $finalPath);
            }
            $zip->close();
            unlink($zipFile);

            SetSetting("version", $Onlineversion['version'], $pdo);
            deleteUpdateFolders(__DIR__);
            Redirect("./?message=update_success");
        } else {
            Redirect("./?message=update_failed");
        }
    } else {
        Redirect("./?message=wrong_version");
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

    <title>Wochenplan Updater</title>

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
<body class="vertical  light">
<div class="wrapper">

    <?php

    $keep_pdo = true;
    include $include_path . "/include/nav.php";
    ?>


    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="h3 mb-4 page-title">Updater</h2>
                    <div class="alert card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Version</th>
                                <th>Release Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $json_url = 'https://raw.githubusercontent.com/PalmarHealer/wochenplan/main/dependencies/versioner.json';
                            $json_data = file_get_contents($json_url);
                            $versions = json_decode($json_data, true);
                            foreach ($versions['versions'] as $Onlineversion) {
                                $is_installed = ($Onlineversion['version'] === $version) ? 'installiert' : 'verfügbar';
                                $is_installed_color = ($Onlineversion['version'] === $version) ? 'badge-success' : 'badge-primary';
                                echo '<tr>
                                                <td>' . $Onlineversion['version'] . '</td>
                                                <td>' . $Onlineversion['release_date'] . '</td>
                                                <td><span class="badge badge-pill ' . $is_installed_color . '">'  . $is_installed . '</span></td>';
                                if ($Onlineversion['version'] == $version) echo '<td><a type="button" class="btn mb-2 btn-primary disabled">Installed</a></td>';
                                else echo '<td><a type="button" class="btn mb-2 btn-primary" href="./?installVersion=' . $Onlineversion['version'] .'">Install</a></td>';

                                echo'</tr>';
                            }
                            echo '</ul>';
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div> <!-- /.col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <?php //include $include_path. "/include/footer.php"; ?>
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