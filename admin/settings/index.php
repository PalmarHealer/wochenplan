<?php
        $include_path = __DIR__ . "/../..";
        require $include_path . "/dependencies/config.php";
        require $include_path . "/dependencies/mysql.php";
        require $include_path . "/dependencies/framework.php";
        global $manage_other_users, $permission_level, $webroot, $pdo, $id, $version, $relative_path;

        CheckPermission($manage_other_users, $permission_level, $webroot . "/dashboard/?message=unauthorized");

        if (isset($_GET['delete-color'])) {
            DeleteSettingWithSuffix("colors", $_GET['delete-color'], $pdo);
            Redirect("./");
        }
        if (isset($_GET['maintenance']) AND $_GET['maintenance'] == 0) {
            UpdateSetting("maintenance", "0", $pdo);
            Redirect("./");
        }
        if (isset($_GET['save']) AND
            $_GET['save'] == "color" AND
            isset($_POST['cname']) AND
            isset($_POST['color'])
        ) {
            SetSettingWithSuffix("colors", $_POST['cname'], $_POST['color'], $pdo);
            Redirect("./");
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
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) == "true") echo "disabled"; ?>>
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) != "true") echo "disabled"; ?>>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">
    <!-- Site CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/select2.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/coloris.min.css?version=<?php echo $version; ?>">
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
                    <h2 class="h3 mb-4 page-title">Einstellung</h2>

                    <?php


                    $maintenance = GetSetting("maintenance", $pdo);
                    if ($maintenance == 1) {
                        echo '
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading"><span class="fe fe-24 fe-alert-triangle"></span> Der Maintenance Modus ist aktiviert. <a type="button" href="./?maintenance=0" class="btn mb-2 btn-outline-danger send right-self">Deaktivieren</a></h4>
                                <p>Der Maintenance Modus wurde Automatisch oder von einem Administrator manuell aktiviert.</p>
                                <hr>
                                <p class="mb-0">Jetzt Aktionen ergreifen.</p>
                            </div>';
                    }

                    // URL to the JSON file containing the versions
                    $file_url = "https://raw.githubusercontent.com/PalmarHealer/wochenplan/main/dependencies/versioner.json";
                    $file_content = file_get_contents($file_url);
                    // Check if file content was fetched successfully
                    if ($file_content === false) {
                        echo '
                          <div class="alert alert-warning" role="alert">
                              <h4 class="alert-heading"><span class="fe fe-24 fe-cloud-off"></span> Der Wochenplan hat kein Zugriff auf das Internet. </h4>
                              <p>Installierte Version: ' . $version . '</p>
                              <hr>
                              <p class="mb-0">Das ist generell nicht schlimm, aber dadurch können keine Aktualisierungen vorgenommen werden.</p>
                          </div>';
                    } else {
                        $json_data = json_decode($file_content, true);
                        $versions = $json_data['versions'];
                        $latest_version_entry = null;

                        // Iterate through the version arrays to find the latest one
                        foreach ($versions as $entry) {
                            if (version_compare($entry['version'], $version, '>=')) {
                                $latest_version_entry = $entry;
                            }
                        }
                        // Check if the latest version entry is found and matches the current version
                        if ($latest_version_entry && $latest_version_entry['version'] == $version) {
                            echo '
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading"><span class="fe fe-24 fe-check-circle"></span> Die neueste Version ist bereits installiert. </h4>
                                    <p>Installierte Version: ' . $version . '</p>
                                    <hr>
                                    <p class="mb-0">Es sind keine weiteren Aktionen notwendig.</p>
                                </div>';
                        } elseif ($latest_version_entry) {
                            echo '
                                <div class="alert alert-warning" role="alert">
                                    <a type="button" class="btn mb-2 btn-outline-warning right" href="../../dependencies/updater/">Aktualisieren</a>
                                    <h4 class="alert-heading"><span class="fe fe-24 fe-alert-circle"></span> Es ist eine neue Version verfügbar. </h4>
                                    <p>Installierte Version: <b>' . $version . '</b></p>
                                    <p>Neuste Version: <b>' . $latest_version_entry['version'] . '</b></p>
                                    <hr>
                                    <p class="mb-0">Das ist generell nicht schlimm, aber durch Aktualisierungen können Fehler behoben und neue Funktionen hinzukommen. </p>
                                </div>';
                        } else {
                            echo '
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading"><span class="fe fe-24 fe-cloud-off"></span> Der Wochenplan hat kein zugriff auf das Internet. </h4>
                                    <p>Installierte Version: ' . $version . '</p>
                                    <hr>
                                    <p class="mb-0">Das ist generell nicht schlimm, aber dadurch können keine Aktualisierungen vorgenommen werden.</p>
                                </div>';
                        }
                    }

                    ?>

                    <div class="my-4">

                        <strong class="mb-0">Plan Templates</strong>

                        <p>Alle Templates so wie welches gerade aktiv ist werden hier aufgelistet.</p>

                        <label id="APLabel" for="active-template">Aktives Template</label>
                        <select onchange="sendActivePlanData()" class="form-control" id="active-template">
                            <?php
                            $array = GetSetting("plan", $pdo);
                            $activeTemplate = GetSettingWithSuffix("plan-template", "active", $pdo);
                            if (is_array($array)) {
                                ksort($array);
                                $displayed = array();
                                foreach ($array as $key => $value) {
                                    if(in_array($key, $displayed)) {
                                        continue;
                                    }
                                    if ($activeTemplate == $key) {
                                        echo "<option selected>" . $key . "</option>";
                                    } else {
                                        echo "<option>" . $key . "</option>";
                                    }
                                    $displayed[] = $key;
                                }
                            } else {
                                echo "<option>" . $array . "</option>";
                            }
                            ?>
                        </select>
                        <hr>
                        <p>Alle Templates die Eingespeichert sind.</p>
                        <table class=" mb-5 shadow table border bg-white">
                            <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $array = GetSetting("plan", $pdo);
                            if (is_array($array)) {
                                ksort($array);
                                $displayed = array();
                                foreach ($array as $key => $value) {
                                    if(in_array($key, $displayed)) {
                                        continue;
                                    }
                                    echo "<tr class='pointer' onclick='window.location=\"" . $webroot  . "/admin/table/?load=" . $key . "\"'>
                                                <td>" . $key . "</td>";
                                    $displayed[] = $key;
                                }
                            } else {
                                echo "<tr><td class='center'>" . $value . "</td><td>" . $value . "</td><td>" . $value . "</td>";
                            }
                            ?>
                            </tbody>
                        </table>

                        <hr class="my-4">
                        <strong class="mb-0">Angebots Farben</strong>
                        <p>Alle Farben die im Wochenplan benutzt werden können.</p>
                        <form action="./?save=color" method="post">
                            <table class=" mb-5 shadow table border bg-white">
                                <thead>
                                <tr>
                                    <th>Farbe</th>
                                    <th>Name</th>
                                    <th>Farbcode</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="center">
                                        <span id="preview" class="dot colordot" style="background-color: black"></span>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cname" required pattern='[^ ]*' title="Leerzeichen sind nicht erlaubt!" class="form-control" id="colorName" type="text" placeholder="Name Der Farbe">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="color" required oninput="updateColor()" class="form-control" id="color" type="text" placeholder="Farbe" data-coloris>
                                        </label>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn mb-2 btn-outline-primary send right-self">Hinzufügen</button>
                                    </td>
                                </tr>
                                <?php
                                $array = GetSetting("colors", $pdo);
                                if (is_array($array)) {
                                    ksort($array);
                                    foreach ($array as $key => $value) {
                                        echo "<tr>
                                                <td class='center'>
                                                  <span class='dot colordot' style=\"background-color: " . $value . "\"></span>
                                                </td>
                                                <td>" . $key . "</td>
                                                <td>" . $value . "</td>
                                                <td>
                                                  <a type='button' href='./?delete-color=" . $key . "' class='btn mb-2 btn-outline-danger send right-self'>Löschen</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                            <td class='center'>
                                              <span class='dot colordot' style=\"background-color: " . $array . "\"></span>
                                            </td>
                                            <td>Name konnte nicht geladen werden</td>
                                            <td>" . $array . "</td>
                                            <td>
                                              <button disabled class='btn mb-2 btn-outline-danger disabled send right-self'>Löschen</button>
                                            </td>
                                          </tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </form>

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
<script src="<?php echo $relative_path; ?>/js/coloris.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
<script>
    function sendActivePlanData() {
        var templateName = $("#active-template").val();
        $.ajax({
            type: 'POST',
            url: '../save/ajax.php',
            data: {
                type: "updateActivePlanTemplate",
                name: templateName
            },
            dataType: 'json',
            success: function(response){
                console.log("successfully changed");
                const btn = $('#APLabel');
                var btnText = btn.text();
                btn.text(btnText + " " + response.message).delay(1000).queue(function(){
                    btn.text(btnText);
                    $(this).dequeue();
                });

            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    }


    function updateColor() {
        $('#preview').css('background-color', $("#color").val());
    }

    $(document).ready(function() {
    Coloris.setInstance('#color', {
        //default, large, polaroid, pill
        theme: 'large',

        themeMode: 'light',

        margin: 5,

        format: 'hex',
        alpha: false,
    });
    });


</script>
</body>
</html>