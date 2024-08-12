<?php
//----------General: ----------

//Are new users able to use the software directly after register.
//"0" = Admin confirmation needed, "1" = no confirmation needed
//Please only use 0 or 1 otherwise errors can happen.
$permission_level = 0; //default 0


//Where is the Webroot from this Software
//ex. if it's reachable from my-host.com/test that its "/test"
//or if it's on a subdomain installed that simply enter "/my-folder"
$webroot = "/wochenplan";  //default /wochenplan
//Set this to "" when / is the webroot

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

//Default permission level to create repeating lessons and edit them as well
$create_lessons_plus = 8; //default 8

//Default permission level to manage other users (this also include creating and deleting)
$manage_other_users = 10; //default 10

//---------- MittagessenAPI: ----------
//For simpleness is mte used for Mittagessen

//URL where the request is made
$mte_url = "https://example.com";

//API secret
$mte_secret = "your secret goes here";

//---------- MySQL connection Information: ----------

//This is where the credentials for the code come in.
//So that it then connects to the database.
//DO NOT USE ROOT OR ADMIN ACCOUNTS FOR THIS
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'wochenplan';
$db_user = "test";
$db_password = "password";

//If you don't want to use the default port you have to write it in the db_ip variable. For ex.: "my-host.com:3306"
//and if the database is on the same server as the website then you can use "localhost"
//specify the database to save everything like lessons and users.


//---------- General information: ----------

$domain = "http://localhost" . $webroot;

$analyticsId = "your google analytics key";

//here you can list all allowed domains that emails allowed to register
$allowed_domains = array(
    "test.com",
    "nauren.de",
    "gmail.com",
);

$smtp = array(
    'host' => 'smtp.host',
    'username' => 'smtp.username',
    'password' => 'smtp.password',
    'port' => '25'
);

$sender = array(
    'mail' => 'email sender',
    'name' => 'sender name'
);

//Weekdays in short (Default language is German)
$weekday_names = array(
    1 => 'Mo',
    2 => 'Di',
    3 => 'Mi',
    4 => 'Do',
    5 => 'Fr',
    6 => 'Sa',
    7 => 'So'
);
//Weekday names (Default language is German)
$weekday_names_long = array(
    1 => 'Montag',
    2 => 'Dienstag',
    3 => 'Mittwoch',
    4 => 'Donnerstag',
    5 => 'Freitag',
    6 => 'Samstag',
    7 => 'Sontag'
);


//Permission level names
$permission_level_names = array(
    $permission_needed => 'Benutzer',
    $create_lessons => 'Ersteller',
    $create_lessons_for_others => 'Ersteller (auch für andere)',
    $create_lessons_plus => 'Ersteller +',
    $manage_other_users => 'Administrator',
    99 => 'Guru'
);
//---------- DO NOT TOUCH (general software information) ----------
$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";
$pdo = new PDO($dsn, $db_user, $db_password);
$db = array(
    'host' => $db_host,
    'port' => $db_port,
    'name' => $db_name,
    'user' => $db_user,
    'password' => $db_password
);
$db_host = null;
$db_port = null;
$db_name = null;
$db_user = null;
$db_password = null;


$keep_dpo = false;

$relative_path = $webroot;

$header = "true";

$version = "3.0.2";
