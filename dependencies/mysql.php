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
    $repeating_day = date('N', strtotime($day));

    while($sl = $lessons->fetch()) {

        if (!isset($sl)) {
            continue;
        }
        if ($day == $sl['date'] OR $repeating_day == $sl['date_repeating']) {
            if ($time == $sl['time'] AND $room == $sl['location']) {
                if ($info == "name") {
                    return $sl['name'];
                }
                if ($info == "description") {
                    return $sl['description'];
                }
                if ($info == "userid") {
                    return $sl['assigned_user_id'];
                }
                if ($info == "available") {
                    return true;
                }
            }
        }

    }
    if ($info == "available") {
        return false;
    } else {
        return "Error loading data";
    }
}


function GetInfomationOfUser($UserID, $InfomationType, $pdo) {
    if (!is_numeric($UserID)) {
        return "Error loading user information";
    }
    $lessons = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $lessons->execute(array(1));
    if ($InfomationType == "vorname") {
        return $lessons->fetch()["vorname"];
    }
    if ($InfomationType == "nachname") {
        return $lessons->fetch()["nachname"];
    }
    if ($InfomationType == "email") {
        return $lessons->fetch()["email"];
    }
    if ($InfomationType == "permission_level") {
        return $lessons->fetch()["permission_level"];
    } else {
        return "Error loading user information. You probably set the wrong information type (there is: vorname, nachname, email and permission_level";
    }

}

function updateUser($id, $vorname, $nachname, $email, $permission_level, $pdo) {
    $statement = $pdo->prepare("UPDATE users SET vorname = :vorname, nachname = :nachname, email = :email, permission_level = :permission_level WHERE id = :id");
    $statement->execute(array(
        'id' => $id,
        'vorname' => $vorname,
        'nachname' => $nachname,
        'email' => $email,
        'permission_level' => $permission_level
    ));
    return $statement->rowCount(); // gibt zurück, wie viele Zeilen aktualisiert wurden, danke ChatGPT
}

