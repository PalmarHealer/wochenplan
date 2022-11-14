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


//Standart Permission to access websites
//0 no permission, 1 default permission, 10 and above for admins
$permission_needed = 1; //default 1




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

$version = "Beta-0.1";
$header = "true";
?>