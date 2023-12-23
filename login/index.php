<?php
$include_path = __DIR__ . "/..";
$page = "external";
require $include_path . "/dependencies/config.php";
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";

session_start();

$redirect = $_GET['return_to'] ?? $webroot . '/dashboard/';


//Überprüfe ob Nutzer vielleicht schon eingeloggt ist. 
//Überprüfe auf den 'Angemeldet bleiben'-Cookie
if(!isset($_SESSION['asl_userid']) && isset($_COOKIE['asl_identifier']) && isset($_COOKIE['asl_securitytoken'])) {
   $identifier = $_COOKIE['asl_identifier'];
   $securitytoken = $_COOKIE['asl_securitytoken'];
   
   $statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
   $result = $statement->execute(array($identifier));
   $securitytoken_row = $statement->fetch();
   
   if(sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
       Redirect($domain . '/error/cookie/');
       die('Ein vermutlich gestohlener Security Token wurde identifiziert');
   } else { //Token war korrekt         
      //Setze neuen Token
      $neuer_securitytoken = random_string();            
      $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
      $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
      setcookie("asl_identifier",$identifier,time()+(3600*24*365)); //1 Jahr Gültigkeit
      setcookie("asl_securitytoken",$neuer_securitytoken,time()+(3600*24*365)); //1 Jahr Gültigkeit
      
      //Logge den Benutzer ein
      $_SESSION['asl_userid'] = $securitytoken_row['user_id'];
   }
}
 
if(!isset($_SESSION['asl_userid'])) {
	
	if(isset($_GET['login'])) {
		$email = $_POST['email'];
        $password = $_POST['password'];

   
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$user = $statement->fetch();
      
		//Überprüfung des Passworts
		if ($user !== false && password_verify($password, $user['passwort'])) {
			$_SESSION['asl_userid'] = $user['id'];
      
			//Möchte der Nutzer angemeldet beleiben?
			if(isset($_POST['angemeldet_bleiben'])) {
				$identifier = random_string();
				$securitytoken = random_string();
         
				$insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
				$insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
				setcookie("asl_identifier",$identifier,time()+(3600*24*365)); //1 Jahr Gültigkeit
				setcookie("asl_securitytoken",$securitytoken,time()+(3600*24*365)); //1 Jahr Gültigkeit
			}
            Redirect($redirect);
			die();
		} else {
			$errorMessage = "E-Mail oder Passwort war(en) ungültig<br>";
            $tip = '<h2 class="h6 mb-3">Hast du dein Passwort vergessen? Dann <a href="' . $relative_path . '/reset-password">setzte es zurück</a>.</h2>';
		}
	}
} else {
	header('Location: ' . $webroot . '/dashboard');
	exit;
}
if (isset($_GET["message"])) {
    $message = $_GET["message"];

    if ($_GET["message"] == "register-success") {
        $getMessage = "Du wurdest erfolgreich Registriert. Bitte melde dich jetzt mit den gerade eingegebenen Zugangsdaten an.";
    }
    if ($_GET["message"] == "please-login") {
        $getMessage = "Du musst dich anmelden um diese Seite sehen zu können.";
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



      <title>Login</title>



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
  <body class="light ">
    <div class="wrapper vh-100 noflow">
      <div class="row align-items-center h-100">
        <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" action="?login=1 <?php
        if (isset($_GET['return_to'])) {
            echo "&return_to=" . $_GET['return_to'];
        }
        ?>" method="post">
          <a class="navbar-brand mx-auto mt-2 flex-fill text-center">
              <img src="<?php echo $relative_path; ?>/img/logo.svg" alt="Logo" class="logo">

          </a>
          <h1 class="h6 mb-3">Sign in</h1>
		  <h2 class="h6 mb-3"><?php if(isset($errorMessage)) { echo $errorMessage; }?></h2>
		  <h2 class="h6 mb-3"><?php if(isset($getMessage)) { echo $getMessage; }?></h2>
          <div class="form-group">
            <label for="inputEmail" class="sr-only">E-Mail-Adresse</label>
            <input type="email" id="inputEmail" class="form-control form-control-lg" placeholder="E-Mail-Adresse" required="" maxlength="250" name="email" value="<?php if (isset($email)) { echo $email; } ?>">
          </div>
          <div class="form-group">
            <label for="inputPassword" class="sr-only">Passwort</label>
            <input type="password" id="inputPassword" class="form-control form-control-lg" placeholder="Passwort" required="" maxlength="250" name="password">
          </div>
          <div class="checkbox mb-3">
            <label>
              <input type="checkbox" name="angemeldet_bleiben" value="1"> Angemeldet bleiben (dafür werden Cookies genutzt)</label>
          </div>
            <h2 class="h6 mb-3">Hast du noch kein Account? Dann <a href="<?php echo $relative_path; ?>/register">frag hier einen an</a>.</h2>
            <?php echo ($tip ?? ''); ?>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
          <p class="mt-5 mb-3 text-muted">© 2023</p>
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

  </body>
</html>