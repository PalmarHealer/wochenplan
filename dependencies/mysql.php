<?php

function UpdateOrInsertLesson($type, $pdo, $id, $date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_box_color, $new_notes, $new_assigned_user_id, $user_that_made_the_change): bool {

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
            $stmt = $pdo->prepare("UPDATE angebot SET date_type = ?, $set_date_column = ?, $empty_date_column = NULL, name = ?, description = ?, location = ?, time = ?, box_color = ?, notes = ?, assigned_user_id = ?, last_change_from_userid = ? WHERE id = ?");
            $stmt->execute([$date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_box_color, $new_notes, $new_assigned_user_id, $user_that_made_the_change, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO angebot (date_type, $set_date_column, $empty_date_column, name, description, location, time, box_color, notes, assigned_user_id, last_change_from_userid) VALUES (?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$date_type, $new_date, $new_name, $new_description, $new_location, $new_time, $new_box_color, $new_notes, $new_assigned_user_id, $user_that_made_the_change]);
        }
        return true;
    } catch(PDOException $e) {
        // Handle errors here
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function UpdateOrInsertSickNote($type, $pdo, $id, $userid, $start_date, $end_date): bool {

    try {
        // Prepare and execute the SQL query
        if ($type == "update") {
            $stmt = $pdo->prepare("UPDATE sick SET start = ?, end = ?, userid = ? WHERE id = ?");
            $stmt->execute([$start_date, $end_date, $userid, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO sick (userid, start, end) VALUES (?, ?, ?)");
            $stmt->execute([$userid, $start_date, $end_date]);
        }
        return true;
    } catch(PDOException $e) {
        // Handle errors here
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function GetLessonInfo($day, $time, $room, $info, $pdo) {


    $lessons = $pdo->prepare("SELECT * FROM angebot ORDER BY id DESC");
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
        if ($day == $sl['date']) {
            if ($time == $sl['time'] AND $room == $sl['location']) {
                return ProcessInformation($sl, $info);
            }
        }
        elseif ($repeating_day == $sl['date_repeating']) {
            if ($time == $sl['time'] AND $room == $sl['location']) {
                return ProcessInformation($sl, $info);
            }
        }
    }
    if ($info == "available") {
        return false;
    } else {
        return "Error loading data";
    }
}

function GetLessonInfoByID($id, $info, $pdo) {


    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE id = ?");
    $lessons->execute(array($id));

    while($sl = $lessons->fetch()) {

        if (!isset($sl)) {
            continue;
        }
        return ProcessInformation($sl, $info);
    }
    if ($info == "available") {
        return false;
    } else {
        return "Error loading data";
    }
}
function ProcessInformation($sl, $info) {
    if ($info == "id") {
        return $sl['id'];
    }
    if ($info == "name") {
        return $sl['name'];
    }
    if ($info == "description") {
        return $sl['description'];
    }
    if ($info == "date") {
        if ($sl['date_type'] == 1) {
            return $sl['date_repeating'];
        } elseif ($sl['date_type'] == 2) {
            return $sl['date'];
        } else {
            return "Error loading date";
        }
    }
    if ($info == "location") {
        return $sl['location'];
    }
    if ($info == "time") {
        return $sl['time'];
    }
    if ($info == "box-color") {
        return $sl['box_color'];
    }
    if ($info == "notes") {
        return $sl['notes'];
    }
    if ($info == "userid") {
        return $sl['assigned_user_id'];
    }
    if ($info == "last_change") {
        return $sl['last_change_from_userid'];
    }
    if ($info == "created_at") {
        return $sl['created_at'];
    }
    if ($info == "updated_at") {
        return $sl['updated_at'];
    }
    if ($info == "disabled") {
        return $sl['disabled'];
    }
    if ($info == "available") {
        return true;
    }
    return "no further information";
}

function GetSickNoteByID($id, $info, $pdo) {


    $sick = $pdo->prepare("SELECT * FROM sick WHERE id = ?");
    $sick->execute(array($id));

    while($sl = $sick->fetch()) {

        if (!isset($sl)) {
            continue;
        }
        if ($info == "userid") {
            return $sl['userid'];
        }
        if ($info == "start") {
            return $sl['start'];
        }
        if ($info == "end") {
            return $sl['end'];
        }
        if ($info == "available") {
            return true;
        }
    }
    if ($info == "available") {
        return false;
    } else {
        return "Error loading data";
    }
}

function GetUserByID($UserID, $InformationType, $pdo) {
    if (!is_numeric($UserID)) {
        return "niemanden";
    }
    $information = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $information->execute(array($UserID));
    return Loop($InformationType, $information);

}
function GetUserByEmail($Email, $InformationType, $pdo) {
    $information = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $information->execute(array($Email));
    return Loop($InformationType, $information);

}


function Loop($InformationType, $information) {

    if ($InformationType == "id") {
        return $information->fetch()["id"];
    }
    if ($InformationType == "vorname") {
        return $information->fetch()["vorname"];
    }
    if ($InformationType == "nachname") {
        return $information->fetch()["nachname"];
    }
    if ($InformationType == "name") {
        $tmp = $information->fetch();
        return $tmp["vorname"] . " " . $tmp["nachname"];
    }
    if ($InformationType == "email") {
        return $information->fetch()["email"];
    }
    if ($InformationType == "permission_level") {
        return $information->fetch()["permission_level"];
    }
    if ($InformationType == "created") {
        return $information->fetch()["created_at"];
    }
    if ($InformationType == "updated") {
        return $information->fetch()["updated_at"];
    }
    if ($InformationType == "available") {
        return $information->rowCount() > 0;
    } else {
        return "Error loading user information. You probably set the wrong information type (there is: id, vorname, nachname, email, permission_level, created, updated and available";
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
    return $statement->rowCount();
}

function ChangeUserPassword($password, $id, $pdo) {
    $new_password = password_hash($password, PASSWORD_DEFAULT);
    $statement = $pdo->prepare("UPDATE users SET passwort = :passwort WHERE id = :id");
    $statement->execute(array(
        'id' => $id,
        'passwort' => $new_password,
    ));
    return $statement->rowCount();
}
function UpdateUsername($id, $vorname, $nachname, $pdo) {
    $statement = $pdo->prepare("UPDATE users SET vorname = :vorname, nachname = :nachname WHERE id = :id");
    $statement->execute(array(
        'id' => $id,
        'vorname' => $vorname,
        'nachname' => $nachname
    ));
    return $statement->rowCount();
}

function CreateUser($vorname, $nachname, $passwort_hash, $email, $permission_level, $pdo): void {
    $statement = $pdo->prepare("INSERT INTO users (email, passwort, vorname, nachname, permission_level) VALUES (:email, :passwort, :vorname, :nachname, :permission_level)");
    $result = $statement->execute(array(
        'email' => $email,
        'passwort' => $passwort_hash,
        'vorname' => $vorname,
        'nachname' => $nachname,
        'permission_level' => $permission_level
    ));
}

function GetAllUsersAndPrintThem($pdo, $permission_level_names): void {

    $users = $pdo->prepare("SELECT * FROM users");
    $users->execute();

    while($sl = $users->fetch()) {

        $id = $sl["id"];
        if ($id == $_SESSION['asl_userid']) {
            continue;
        }
        $username = $sl["vorname"] . " " . $sl["nachname"];
        $email = $sl["email"];
        //$permission_level = $sl["permission_level"];
        $permission_level = GetHighestValueBelowValueName($sl["permission_level"], $permission_level_names);

        $created_at = date("d.m.Y H:i", strtotime($sl["created_at"]));
        $updated_at = date("d.m.Y H:i", strtotime($sl["updated_at"]));

        echo '<tr>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';">
                </td>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';">
                    <div class="avatar avatar-md">
                        <span class="fe fe-user fe-32"></span>
                    </div>
                </td>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';">
                    <p class="mb-0 text-muted"><strong>' . $username . '</strong></p>
                </td>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';">
                    <p class="mb-0">' . $email . '</p>
                </td>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';">
                    <p class="mb-0">' . $permission_level . '</p>
                </td>
                <td onclick="window.location=\'./edit/?id='. $id . '\';" class="text-muted pointer">' . $updated_at . '
                </td>
                <td onclick="window.location=\'./edit/?id='. $id . '\';" class="text-muted pointer">' . $created_at . '
                </td>
                <td>
                    <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="text-muted sr-only">Aktion</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="./?login-to-id='. $id . '">Anmelden als dieser</a>
                        <a class="dropdown-item" href="./edit/?id='. $id . '">Bearbeiten</a>
                        <a class="dropdown-item" href="./edit/?delete='. $id . '">Löschen</a>
                    </div>
                </td>
              </tr>';
    }
}

function EnableLesson($userID, $lessonID, $pdo) {
    $stmt = $pdo->prepare("UPDATE angebot SET disabled = false, last_change_from_userid = ? WHERE id = ?");
    $stmt->execute([$userID, $lessonID]);
}

function DisableLesson($userID, $lessonID, $pdo) {
    $stmt = $pdo->prepare("UPDATE angebot SET disabled = true, last_change_from_userid = ? WHERE id = ?");
    $stmt->execute([$userID, $lessonID]);
}

function DeleteLesson($lessonid, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM angebot WHERE id = ?");
        $delete_lesson->execute(array($lessonid));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}

function DeleteRegisterToken($token, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM registertokens WHERE token = ?");
        $delete_lesson->execute(array($token));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}
function DeleteResetToken($token, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM psswdresettokens WHERE token = ?");
        $delete_lesson->execute(array($token));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}
function DeleteUser($userid, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $delete_lesson->execute(array($userid));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}
function DeleteSickNote($sickid, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM sick WHERE id = ?");
        $delete_lesson->execute(array($sickid));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}

function GetAllLessonsFromUserAndPrintThem($userid, $limit, $room_names, $times, $pdo, $webroot): void {

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
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">
										  </td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . replacePlaceholders($sl['name']) . '</td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . replacePlaceholders($sl['description']) . '</td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . $room_names[$sl['location']] . '</td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . $times[$sl['time']] . '</td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . $date_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';"><span class="dot dot-lg" style="background-color: ' . $sl['box_color'] .'"></span></td>
										  <td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Aktion</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="../lessons/details/?id=' . $sl['id'] . '">Bearbeiten</a>
												<a class="dropdown-item" href="../lessons/details/?remove_lesson_with_id=' . $sl['id'] . '">Löschen</a>
											  </div>
										  </td>
										  </tr>';
    }

}

function GetAllLessons($room_names, $times, $pdo): void {
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

        $creator_fomatted = GetUserByID($sl['assigned_user_id'], "name", $pdo);

        echo '<tr>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">
										  </td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . replacePlaceholders($sl['name']) . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . replacePlaceholders($sl['description']) . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $room_names[$sl['location']] . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $times[$sl['time']] . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $date_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';"><span class="dot dot-lg" style="background-color: ' . $sl['box_color'] .'"></td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $creator_fomatted . '</td>
										  <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $sl['notes'] . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Aktion</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="./details/?id=' . $sl['id'] . '">Bearbeiten</a>
												<a class="dropdown-item" href="./details/?remove_lesson_with_id=' . $sl['id'] . '">Löschen</a>
											  </div>
										  </td>
										  </tr>';

    }


}

function GetAllSickNotes($pdo): void {
    $lessons = $pdo->prepare("SELECT * FROM sick");
    $lessons->execute();
    while($sl = $lessons->fetch()) {


        $new_assigned_user_id = ($sl['userid'] ?? '');
        $start_date = ($sl['start'] ?? '');
        $end_date = ($sl['end'] ?? '');
        $start_date2 = date("d.m.Y", strtotime($start_date));
        $end_date2 = date("d.m.Y", strtotime($end_date));

        $username = GetUserByID($new_assigned_user_id, "name", $pdo);

        echo '<tr>
										  <td class="pointer" onClick="window.location=\'./edit/?id=' . $sl['id'] . '\';">
										  </td>
										  <td class="pointer" onClick="window.location=\'./edit/?id=' . $sl['id'] . '\';">' . $username . '</td>
										  <td class="pointer" onClick="window.location=\'./edit/?id=' . $sl['id'] . '\';">' . $start_date2 . '</td>
										  <td class="pointer" onClick="window.location=\'./edit/?id=' . $sl['id'] . '\';">' . $end_date2 . '</td>
										
										  <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="text-muted sr-only">Aktion</span>
											  </button>
											  <div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="./edit/?id=' . $sl['id'] . '">Bearbeiten</a>
												<a class="dropdown-item" href="./edit/?remove=' . $sl['id'] . '">Löschen</a>
											  </div>
										  </td>
										  </tr>';

    }


}

function GetAllSickNotesRaw($pdo): array {
    $lessons = $pdo->prepare("SELECT * FROM sick");
    $lessons->execute();
    $SickNotes = array();
    $counter = 1;
    while($sl = $lessons->fetch()) {

        if (!isset($sl)) {
            continue;
        }
        $SickNotes[$counter] = array();
        $SickNotes[$counter]['userid'] = ($sl['userid'] ?? '');
        $start_date = ($sl['start'] ?? '');
        $end_date = ($sl['end'] ?? '');
        $SickNotes[$counter]['start_date'] = date("d.m.Y", strtotime($start_date));
        $SickNotes[$counter]['end_date'] = date("d.m.Y", strtotime($end_date));
        $SickNotes[$counter]['vorname'] = GetUserByID($sl['userid'], "vorname", $pdo);
        $SickNotes[$counter]['username'] = GetUserByID($sl['userid'], "name", $pdo);
        $counter ++;
    }
    return $SickNotes;


}
function GetAllUsersAndPrintForSelect($pdo, $OwnId, $IdToSelect): void {

    $get_usernames = "SELECT * FROM users ORDER BY permission_level desc";
    foreach ($pdo->query($get_usernames) as $other_users) {

        if ($other_users['id'] == $IdToSelect) {
            echo "<option selected value='";
        } else {
            echo "<option value='";
        }
        echo $other_users['id'] . "'>" . $other_users['vorname'] . " " . $other_users['nachname'];
        if ($other_users['id'] == $OwnId) {
            echo " (Du selbst)";
        }
        echo "</option>";
    }

}

function GetEmailFromToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM registertokens WHERE token = ?");
    $tokens->execute(array($token));


    while($sl = $tokens->fetch()) {

        return $sl['email'] ?? 'false';
    }

}

function GetDateFromRegisterToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM registertokens WHERE token = ?");
    $tokens->execute(array($token));
    while($sl = $tokens->fetch()) {
        return $sl['created'] ?? 'false';
    }
}

function GetDateFromResetToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM psswdresettokens WHERE token = ?");
    $tokens->execute(array($token));
    while($sl = $tokens->fetch()) {
        return $sl['created'] ?? 'false';
    }
}
function GetUserIDFromResetToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM psswdresettokens WHERE token = ?");
    $tokens->execute(array($token));
    while($sl = $tokens->fetch()) {
        return $sl['userid'] ?? 'false';
    }
}
function CreateTokenForRegistrationAndSaveThem($email, $pdo): string {
    $token = GenerateRandomString();
    $statement = $pdo->prepare("INSERT INTO registertokens (token, email) VALUES (:token, :email)");
    $result = $statement->execute(array(
        'token' => $token,
        'email' => $email
    ));
    return $token;
}

function CreateTokenForPasswordResetAndSaveThem($userid, $pdo): string {
    $token = GenerateRandomString();
    $statement = $pdo->prepare("INSERT INTO psswdresettokens (token, userid) VALUES (:token, :userid)");
    $result = $statement->execute(array(
        'token' => $token,
        'userid' => $userid
    ));
    return $token;
}