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
    if (str_contains($day, "-")) {
        $repeating_day = date('N', strtotime($day));
    } else {
        $repeating_day = $day;
    }

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
                    return false;
                }
            }
        }

    }
    if ($info == "available") {
        return true;
    } else {
        return "Error loading data";
    }
}

function GetLessonByID($id, $info, $pdo) {


    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE id = ?");
    $lessons->execute(array($id));

    while($sl = $lessons->fetch()) {

        if (!isset($sl)) {
            continue;
        }
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
            return false;
        }

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

function UpdateUser($id, $vorname, $nachname, $email, $permission_level, $pdo) {
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

function DeleteLesson($lessonid, $pdo) {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM angebot WHERE id = ?");
        $delete_lesson->execute(array($lessonid));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}

function GetAllLessonsFromUserAndPrintThem($userid, $limit, $room_names, $times, $pdo) {

    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE assigned_user_id = ? ORDER BY date ASC");
    $lessons->execute(array($userid));
    $counter = 1;
    $pdo = null;


    while($sl = $lessons->fetch()) {

        if (isset($sl['date'])) {
            if (date("Y-m-d",time()) > $sl['date'] OR $counter > $limit) {
                continue;
            }
        }

        $counter += 1;

        if ($sl['date_type'] == "2") {
            $date1 = $sl['date'];
            $single_date1 = explode("-", $date1);
            $date_fomatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];
        } else {
            $date_day = $sl['date_repeating'];
            if ($date_day == "1") {
                $date_fomatted = "Jeden Montag";
            } elseif ($date_day == "2") {
                $date_fomatted = "Jeden Dienstag";
            } elseif ($date_day == "3") {
                $date_fomatted = "Jeden Mittwoch";
            } elseif ($date_day == "4") {
                $date_fomatted = "Jeden Donnerstag";
            } elseif ($date_day == "5") {
                $date_fomatted = "Jeden Freitag";
            } else {
                $date_fomatted = "Fehler beim Laden des Datums";
            }
        }

        echo '<tr>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">
										  </td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['name'] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['description'] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $room_names[$sl['location']] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $times[$sl['time']] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $date_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Action</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="../lessons/details/?id=' . $sl['id'] . '">Edit</a>
												<a class="dropdown-item" href="../lessons/details/?remove_lesson_with_id=' . $sl['id'] . '">Remove</a>
											  </div>
										  </td>
										  </tr>';
    }

}

function GetAllLessons($room_names, $times, $pdo) {
    $lessons = $pdo->prepare("SELECT * FROM angebot");
    $lessons->execute();
    while($sl = $lessons->fetch()) {

        if ($sl['date_type'] == "2") {
            $date1 = $sl['date'];
            $single_date1 = explode("-", $date1);
            $date_fomatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];
        } else {
            $date_day = $sl['date_repeating'];
            if ($date_day == "1") {
                $date_fomatted = "Jeden Montag";
            } elseif ($date_day == "2") {
                $date_fomatted = "Jeden Dienstag";
            } elseif ($date_day == "3") {
                $date_fomatted = "Jeden Mittwoch";
            } elseif ($date_day == "4") {
                $date_fomatted = "Jeden Donnerstag";
            } elseif ($date_day == "5") {
                $date_fomatted = "Jeden Freitag";
            } else {
                $date_fomatted = "Fehler beim Laden des Datums";
            }
        }



        $creator_id = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $creator_id->execute(array($sl['assigned_user_id']));
        while ($creator_name = $creator_id->fetch()) {
            $creator_fomatted = $creator_name['vorname'] . " " . $creator_name['nachname'];
        }
        echo '<tr>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">
										  </td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['name'] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['description'] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $room_names[$sl['location']] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $times[$sl['time']] . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $date_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $creator_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'./../lessons/details/?id=' . $sl['id'] . '\';">' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Action</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="./details/?id=' . $sl['id'] . '">Edit</a>
												<a class="dropdown-item" href="./details/?remove_lesson_with_id=' . $sl['id'] . '">Remove</a>
											  </div>
										  </td>
										  </tr>';

    }


}