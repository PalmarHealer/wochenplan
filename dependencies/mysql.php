<?php


function update_or_insert_lesson($type, $pdo, $id, $date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_notes, $new_assigned_user_id) {

    try {
        // Determine the column names based on the $date_type value
        if ($date_type == 1) {
            $set_date_column = "date_repeating";
            $empty_date_column = "date";
        } else {
            $set_date_column = "date";
            $empty_date_column = "date_repeating";
        }


        // Prepare and execute the SQL query
        if ($type == "update") {
            $stmt = $pdo->prepare("UPDATE angebot SET date_type = ?, $set_date_column = ?, $empty_date_column = NULL, name = ?, description = ?, location = ?, time = ?, notes = ?, assigned_user_id = ? WHERE id = ?");
            $stmt->execute([$date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_notes, $new_assigned_user_id, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO angebot (date_type, $set_date_column, $empty_date_column, name, description, location, time, notes, assigned_user_id) VALUES (?, ?, NULL, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_notes, $new_assigned_user_id]);
        }
        return true;
    } catch(PDOException $e) {
        // Handle errors here
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function GetLesson($day, $time, $room, $info, $pdo) {


    $lessons = $pdo->prepare("SELECT * FROM angebot ORDER BY id ASC");
    $lessons->execute();

    while($sl = $lessons->fetch()) {

        if (!isset($sl)) {
            continue;
        }
        if ($day == $sl['date'] AND $time == $sl['location'] AND $room ==$sl['location'] AND $info == "name") {
            return $sl['name'];
        } elseif ($day == $sl['date'] AND $time == $sl['location'] AND $room ==$sl['location'] AND $info == "description") {
            return $sl['description'];
        } elseif ($day == $sl['date'] AND $time == $sl['location'] AND $room ==$sl['location'] AND $info == "userid") {
            return $sl['assigned_user_id'];
        } else {
            return "Error loading data";
        }

    }

}

function IsThereALesson($day, $time, $room, $pdo) {


    $lessons = $pdo->prepare("SELECT * FROM angebot ORDER BY id ASC");
    $lessons->execute();

    while($sl = $lessons->fetch()) {
        if (!isset($sl)) {
            continue;
        }
        if ($day == $sl['date'] AND $time == $sl['location'] AND $room ==$sl['location']) {
            return true;
            exit();
        }  else {
            return false;
        }
    }
}

function GetNameOfUser($userid, $pdo) {
    if (!is_numeric($userid)) {
        return "Error loading username";
    }
    $lessons = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $lessons->execute(array($userid));
    return $lessons->fetch()["vorname"];

}