<?php
//----------General: ----------

//Are new users able to use the software directly after register.
//"0" = Admin confirmation needed, "1" = no confirmation needed
//Please only use 0 or 1 otherwise errors can happen.
$permission_level = 0; //default 0


//Where is the Webroot from this Software
//ex. if it's reachable from my-host.com/test that its "/test"
//or if it's on a subdomain installed that simply enter "/"
$webroot = "/wochenplan";  //default /wochenplan
$relative_path = $webroot; //Set this to "" when / is the webroot

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

//---------- MySQL connection Information: ----------

//This is where the credentials for the code come in.
//So that it then connects to the database.
//DO NOT USE ROOT OR ADMIN ACCOUNTS FOR THIS
$db_user = "test";
$db_password = "password";


//If you don't want to use the default port you have to write it in the db_ip variable. For ex.: "my-host.com:3306"
//and if the database is on the same server as the website then you can use "localhost"
//specify the database to save everything like lessons and users.
$pdo = new PDO('mysql:host=localhost;dbname=wochenplan', $db_user, $db_password);

$keep_dpo = false;


//Weekdays in German
$weekday_names = array(
    1 => 'Mo',
    2 => 'Di',
    3 => 'Mi',
    4 => 'Do',
    5 => 'Fr',
    6 => 'Sa',
    7 => 'So'
);


//Permission level names
$permission_level_names = array(
    $permission_needed => 'Benutzer',
    $create_lessons => 'Ersteller',
    $create_lessons_for_others => 'Ersteller (auch für andere)',
    $manage_other_users => 'Administrator',
    99 => 'Guru'
);


//Available rooms
$room_names = array(
    1 => "Raum 1",
    2 => "Raum 2",
    3 => "Raum 3 (HS)",
    4 => "Raum 4 (RS)",
    5 => "Gesprächsraum",
    6 => "SZ/Praxisber.",
    7 => "Sport</option",
    8 => "Ausflug",
    9 => "Freiarbeit"
);

//Available times
$times = array(
    1 => "Morgenband - 9:00 - 10:00",
    2 => "Morgenkreise - 10:00 – 10:30",
    3 => "Großes Band - 10:30 – 12:00",
    4 => "Großes Band (Noch Nicht Sichtbar)",
    5 => "Nachmittagsband - 13:00 – 14:30",
    6 => "Spätes Band - 13:00 – 14:30"
);



//---------- DO NOT TOUCH (general software information) ----------
//If you do some changes here, some or hole parts of the 
//website are not able to work properly

$header = "true";
