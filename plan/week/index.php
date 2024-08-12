<?php
$include_path = __DIR__ . "/../..";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
global $relative_path, $version, $id, $pdo;


if (!isset($_GET["date"])) {
    $_GET["date"] = date("Y-m-d", time());
    Redirect("./?date=" . date("Y-m-d", time()));
}

?>
<link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">

    <title>Plan - <?php echo date('d.m.Y', strtotime($_GET["date"])); ?></title>

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/simplebar.css?version=<?php echo $version; ?>">
    <!-- Fonts CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/abel.css?version=<?php echo $version; ?>">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/feather.css?version=<?php echo $version; ?>">
    <link rel="stylesheet"
          href="<?php echo $relative_path; ?>/css/dataTables.bootstrap4.css?version=<?php echo $version; ?>">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/daterangepicker.css?version=<?php echo $version; ?>">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-light.css?version=<?php echo $version; ?>" id="lightTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) == "true") echo "disabled"; ?>>
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/app-dark.css?version=<?php echo $version; ?>" id="darkTheme" <?php if (GetUserSetting($id, "darkMode", $pdo) != "true") echo "disabled"; ?>>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $relative_path; ?>/css/customstyle.css?version=<?php echo $version; ?>">

</head>

<body>

<?php

$dates = GetDaysOfWeek(date("Y-m-d", time()));
$counter = 0;
foreach ($dates as $date) {
    $counter++;
    if ($counter > 5) {
        continue;
    }
    //echo '<iframe src="../index.php?date=' . $date . '&mode=week" style="border:2px solid #d7d7d7;height:100%;width:100vw;" scrolling="no"></iframe>';
    echo '<div class="full-week ' . $date . '" date="' . $date . '"></div>';
}

?>


</body>


<script src="<?php echo $relative_path; ?>/js/jquery.min.js?version=<?php echo $version; ?>"></script>
<script src="<?php echo $relative_path; ?>/js/customjavascript.js?version=<?php echo $version; ?>"></script>
<script>
    <?php
    if (isset($_GET['debug'])) {
        echo "setTimeout(hide_btn, 6000);";
        echo "reloadData();";
    } else {
        echo '$(document).ready(function() {
                        setTimeout(reloadData2, 2500);
                        setTimeout(hide_btn, 6000);
                        setInterval(reloadData2, 6000);
                    });';
    }
    ?>

    const elem = document.documentElement;


    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
        $(".open_fullscreen").hide();
        $(".close_fullscreen").show();
    }

    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
        $(".open_fullscreen").show();
        $(".close_fullscreen").hide();
    }


    function hide_btn() {
        $(".close_fullscreen").hide();
    }

    function reloadData2() {
        $("body div").each(function (index, element) {
            const div = $(element);
            setTimeout(function () {
                const dateValue = div.attr("date");
                if (typeof dateValue === 'undefined') {
                    return false;
                }
                $.ajax({
                    url: "../reload<?php echo($_GET['version'] ?? '2') ?>.php",
                    type: "POST",
                    data: {
                        date: dateValue,
                        mode: 'week'
                    },
                    cache: false,
                    success: function (data) {
                        console.log("Day: " + dateValue + " reloaded");
                        $(div).html(data);
                    }
                });
            }, index * 500);
        });
    }

    function reloadData(dateParam) {
        const urlParams = new URLSearchParams(window.location.search);
        const dateValue = dateParam || urlParams.get('date');
        $.ajax({
            url: "../reload<?php echo($_GET['version'] ?? '2') ?>.php",
            type: "POST",
            data: {
                date: dateValue,
                mode: 'week'
            },
            cache: false,
            success: function (data) {
                console.log("Data reloaded");
                $(".full-week").html(data);
            }
        });
    }
</script>

</html>
