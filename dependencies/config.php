<?php
//----------General: ----------

//Are new users able to use the software directly after register.
//"0" = Admin confirmation needed, "1" = no confirmation needed
//Please only use 0 or 1 otherwise errors can happen.
$default_user_permission_level = 0; //default 0


//Where is the Webroot from this Software
//ex. if it's reachable from my-host.com/test that its "/test"
//or if it's on a subdomain installed that simply enter "/"
$webroot = "/";  //default /wochenplan
$relative_path = ""; //Set this to "" when / is the webroot

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
$db_user = "wochenplan";
$db_password = "Og347$@xgi$*";


//If you don't want to use the default port you have to write it in the db_ip variable. For ex.: "my-host.com:3306"
//and if the database is on the same server as the website then you can use "localhost"
//specify the database to save everything like lessons and users.
$pdo = new PDO('mysql:host=localhost;dbname=wochenplan', $db_user, $db_password);

$keep_dpo = false;



//---------- DO NOT TOUCH (general software infomation) ----------
//If you do some changes here, some or hole parts of the 
//website are not able to work properly
//
//


$version = "Beta-0.2";
$header = "true";
