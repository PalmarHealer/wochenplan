<?php
//----------General: ----------

//Are new users able to use the software directly after register.
//"0" = Admin confirmation needed, "1" = no confirmation needed
//Please only use 0 or 1 otherwise errors can happen.
$default_user_permission_level = 0; //default 0


//Where is the Webroot from this Software
//ex. if its reachable from myhost.com/test that its "/test"
//or if its on a subdomain installed that simply enter "/"
$webroot = "/wochenplan";  //default /wochenplan
$path = $webroot;

//Default website theming - CURRENTLY NOT FUNCTIONAL - 
//"light" for light, "dark" for dark...
//Please only use light or dark otherwise errors can happen.
$theme = "light"; //default light


//Standard Permission to access websites
//0 no permission, 1 default permission, 10 and above for admins
$permission_needed = 1; //default 1



//Default permission level to create lessons
$create_lessons = 5; //default 5

//Default permission level to create lessons for other people
$create_lessons_for_others = 6; //default 6

//Default permission level to manage other users (this also include creating and deleting)
$manage_other_users = 10; //default 10

//---------- MySQL connection Infomation: ----------

//This is where the credentials for the code come in.
//So that it then connects to the database.
//DO NOT USE ROOT OR ADMIN ACCOUNTS FOR THIS
$db_user = "";
$db_password = "";


//If you don't want to use the default port you have to write it in the db_ip variable. For ex.: "myhost.com:3306"
//and if the database is on the same server as the website then you can use "localhost"
//specifiy the database to save everything like lessons and users.
$pdo = new PDO('mysql:host=localhost;dbname=wochenplan', $db_user, $db_password);

$keep_dpo = false;



//---------- DO NOT TOUCH (general software infomation) ----------
//If you do some changes here, some or hole parts of the 
//website are not able to work properly
//
//


$version = "Beta-1.0";
$header = "true";


function redirect($newURL) {
	header("Location: $newURL");
    echo "<script>window.location.href='$newURL';</script>";
	$pdo = null;
	exit();
}

function goPageBack($URLaddition) {
	header("Location: " . $_SERVER['HTTP_REFERER'] . $URLaddition);
    echo "<script>history.back()</script>";
	$pdo = null;
	exit();
}

function checkUrlHasntChanged() {
		
	//Thanks to https://www.javatpoint.com/how-to-get-current-page-url-in-php
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
         $current_url = "https://";
    else  
         $current_url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $current_url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $current_url.= $_SERVER['REQUEST_URI'];    
	
	
	return $current_url;
}
$old_url_array = explode("?", checkUrlHasntChanged());
$new_url_array = explode("?", $_SERVER['HTTP_REFERER']);
$old_url = $old_url_array[0];
$new_url = $new_url_array[0];

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
?>
