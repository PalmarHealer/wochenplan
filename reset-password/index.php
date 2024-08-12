<?php
$include_path = __DIR__ . "/..";
$page = "external";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
require $include_path . "/dependencies/email.php";
global $pdo, $smtp, $allowed_domains, $domain, $version, $relative_path;

session_start();

$showEmailSend = false;
$showResetFormular = false;
$showEmailValidate = true;
$error = false;

$_SESSION['asl_userid'] = null;


if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $showResetFormular = true;
    $showEmailValidate = false;


    if (!IsDateOlderThat10Minutes(GetDateFromResetToken($token, $pdo))) {
        DeleteResetToken($token, $pdo);
        $_GET['token'] = null;
        Redirect("./?message=token_expired");
    }

}
if (isset($_GET['email-send'])) {
    $showEmailSend = true;
    $showEmailValidate = false;
}


if(isset($_GET['email'])) {
    $email = $_GET['email'];
    $_GET['email'] = null;


    //if (IsMailAllowed($email, $allowed_domains)) {
    //    $mailalredyused = "Diese E-Mail-Adresse darfst du nicht verwenden";
    //    $error = true;
    //}

    if (!GetUserByEmail($email, "available", $pdo)) {
        $mailalredyused = "Diese E-Mail-Adresse ist keinem Benutzer zugeordnet";
        $error = true;
    }

    if(!$error) {
        $token = CreateTokenForPasswordResetAndSaveThem(GetUserByEmail($email, "id", $pdo), $pdo);
        $recipient = array('mail' => $email);
        try {
            SendResetMail($smtp, $recipient, $domain . "/reset-password/?token=" . $token);
            Redirect("./?email-send=" . $email);
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $errorMessage = "Beim senden der E-Mail ist ein Fehler aufgetreten. Wenn das wiederholt passiert, wende dich bitte an einen Administrator.";
        }
    }
}





if(isset($_GET['register'])) {
    if (!isset($_GET['token'])) die();
    $token = $_GET['token'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(strlen($passwort) == 0) {
        $passwd1empty = "Bitte ein Passwort angeben";
        $error = true;
    }
    if(strlen($passwort2) == 0) {
        $passwd2empty = "Bitte ein Passwort angeben";
        $error = true;
    }
    if($passwort != $passwort2) {
        $passwdmatch = "<br>Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    if(!$error) {


        ChangeUserPassword("$passwort", GetUserIDFromResetToken($token, $pdo), $pdo);
        DeleteResetToken($token, $pdo);
        Redirect($domain . '/login?message=reset_success');
    }
}
$pdo = null;
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $relative_path; ?>/favicon.ico?version=<?php echo $version; ?>">



    <title>Reset Passwort</title>



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


</head>
<?php
if($showResetFormular) {
    ?>
    <body class="light ">
    <div class="wrapper vh-100 noflow">
        <div class="row align-items-center h-100">
            <form class="col-lg-6 col-md-8 col-10 mx-auto" action="?register=1<?php if (isset($_GET['token'])) { echo "&token=" . $_GET['token']; } ?>" method="post">
                <div class="mx-auto text-center my-4">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./">
                        <img src="<?php echo $relative_path; ?>/img/logo.svg" alt="Logo" class="logo">

                    </a>
                    <h2 class="my-3">Passwort zurücksetzten</h2>
                    <?php if(isset($errorMessage)) { echo '<h2 align="center" class="h6 mb-3">'; echo $errorMessage; echo '</h2>'; }?>
                </div>

                <hr class="my-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPassword5">Passwort*</label>
                            <?php if(isset($passwd1empty)) { echo '<h2 class="h6 mb-3">'; echo $passwd1empty; echo '</h2>'; }?>

                            <input
                                required
                                type="password"
                                size="40"
                                maxlength="250"
                                name="passwort"
                                class="form-control"
                                id="inputPassword5"
                                minlength="8"
                                value="<?php if(isset($passwort)) { echo $passwort; }?>"
                            >

                        </div>
                        <div class="form-group">
                            <label for="inputPassword6">Passwort bestätigen*</label>
                            <?php if(isset($passwd2empty)) { echo '<h2 class="h6 mb-3">'; echo $passwd2empty; echo '</h2>'; }?>
                            <input
                                required
                                type="password"
                                size="40"
                                maxlength="250"
                                name="passwort2"
                                class="form-control"
                                id="inputPassword6"
                                minlength="8"
                            >
                            <?php if(isset($passwdmatch)) { echo '<h2 class="h6 mb-3">'; echo $passwdmatch; echo '</h2>'; }?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">Passwort Empfehlungen</p>
                        <p class="small text-muted mb-2"> Um ein sicheres Passwort zu erstellen, empfehlen wir folgende Anforderungen zu erfüllen:</p>
                        <ul class="small text-muted pl-4 mb-0">
                            <li> Mindestens 8 Zeichen </li>
                            <li> Mindestens ein Sonderzeichen</li>
                            <li> Mindestens eine Zahl</li>
                        </ul>
                        <p class="small text-muted mb-2"> Es wird empfohlen keine Passwörter doppelt zu benutzen.</p>
                    </div>
                </div>
                <p class="mb-2">*Pflichtfelder</p>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
<?php
}
if ($showEmailValidate) {
    ?>
    <body class="light">
    <div class="wrapper vh-100 noflow">
        <div class="row align-items-center h-100">
            <form class="col-lg-3 col-md-4 col-10 mx-auto text-center">
                <div class="mx-auto text-center my-4">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center">
                        <img src="<?php echo $relative_path; ?>/img/logo.svg" alt="Logo" class="logo">
                    </a>
                    <h2 class="my-3">Passwort vergessen</h2>
                </div>
                <div class="form-group">
                    <?php if(isset($mailempty)) { echo '<h2 class="h6 mb-3">'; echo $mailempty; echo '</h2>'; }?>
                    <?php if(isset($mailalredyused)) { echo '<h2 class="h6 mb-3">'; echo $mailalredyused; echo '</h2>'; }?>
                    <input placeholder="E-Mail-Adresse" id="email" type="email" size="40" maxlength="250" name="email" class="form-control" id="inputEmail4"" required>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Send E-Mail</button>
                <hr>
                <h2 class="h6 mb-3">Hast du bereits ein Account?</h2>
                <a class="btn btn-lg btn-secondary btn-block" href="<?php echo $relative_path; ?>/login">Zurück zum Login</a>
<?php
}
if ($showEmailSend) {
    ?>
    <body class="light">
    <div class="wrapper vh-100 noflow">
        <div class="row align-items-center h-100">
            <form class="col-lg-3 col-md-4 col-10 mx-auto text-center">
                <div class="mx-auto text-center my-4">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.html">
                        <img src="<?php echo $relative_path; ?>/img/logo.svg" alt="Logo" class="logo">
                    </a>
                    <h4 class="my-3">E-Mail wurde verschickt!</h4>
                </div>
                <div class="alert alert-success" role="alert"> Eine E-Mail wurde an <strong><?php echo ($_GET['email-send'] ?? 'placeholder@mail.de') ?></strong> geschickt. Bitte schau in deinem <b>Postfach</b> und im <b>Spamordner</b> nach.</div>
                <a href="<?php echo $relative_path; ?>/login" class="btn btn-lg btn-primary btn-block">Zurück zum Login</a>
<?php
}
?>
                <p class="mt-5 mb-3 text-muted text-center">© 2024</p>
            </form>
        </div>
    </div>
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