<?php
require_once __DIR__ . "/config.php";
function UpdateOrInsertLesson(
    $type,
    $pdo,
    $id,
    $parent_lesson_id,
    $date_type,
    $new_date,
    $new_name,
    $new_description,
    $new_location,
    $new_time,
    $new_box_color,
    $new_notes,
    $new_assigned_user_id,
    $user_that_made_the_change,
    $disabled
): bool {

    $identifier = GetSetting("identifier", $pdo);
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
            $stmt = $pdo->prepare(
                   "UPDATE angebot SET
                   parent_lesson_id = NULL,
                   identifier = ?,
                   date_type = ?,
                   $set_date_column = ?,
                   $empty_date_column = NULL,
                   name = ?,
                   description = ?,
                   location = ?,
                   time = ?,
                   box_color = ?,
                   notes = ?,
                   assigned_user_id = ?,
                   last_change_from_userid = ?,
                   disabled = ? 
                   WHERE id = ?"
            );
            $stmt->execute([
                $identifier,
                $date_type,
                $new_date,
                CodeToJson($new_name),
                CodeToJson($new_description),
                $new_location,
                $new_time,
                $new_box_color,
                CodeToJson($new_notes),
                $new_assigned_user_id,
                $user_that_made_the_change,
                $disabled,
                $id
            ]);

        } elseif ($type == "create") {
            $stmt = $pdo->prepare("INSERT INTO angebot (
                parent_lesson_id,
                identifier, 
                date_type, 
                $set_date_column, 
                $empty_date_column,
                name,
                description,
                location,
                time,
                box_color,
                notes,
                assigned_user_id,
                last_change_from_userid,
                disabled
            ) VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $parent_lesson_id,
                $identifier,
                $date_type,
                $new_date,
                CodeToJson($new_name),
                CodeToJson($new_description),
                $new_location,
                $new_time,
                $new_box_color,
                CodeToJson($new_notes),
                $new_assigned_user_id,
                $user_that_made_the_change,
                $disabled
            ]);
        } else {
            return false;
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
        if ($type == "update") {
            $stmt = $pdo->prepare("UPDATE sick SET start = ?, end = ?, userid = ? WHERE id = ?");
            $stmt->execute([$start_date, $end_date, $userid, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO sick (identifier, userid, start, end) VALUES (?, ?, ?, ?)");
            $stmt->execute([GetSetting("identifier", $pdo), $userid, $start_date, $end_date]);
        }
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
function GetLessonInfo($day, $time, $room, $info, $pdo) {
    $identifier = GetSetting("identifier", $pdo);
    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE identifier = ? ORDER BY id DESC");
    $lessons->execute(array($identifier));
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
    $identifier = GetSetting("identifier", $pdo);
    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE id = ? AND identifier = ?");
    $lessons->execute(array($id, $identifier));

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
    if ($info == "parent_lesson_id") {
        return $sl['parent_lesson_id'];
    }
    if ($info == "name") {
        return DecodeFromJson($sl['name']);
    }
    if ($info == "description") {
        return DecodeFromJson($sl['description']);
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
    if ($info == "date_type") {
        return $sl['date_type'];
    }
    if ($info == "box-color") {
        return $sl['box_color'];
    }
    if ($info == "notes") {
        return DecodeFromJson($sl['notes']);
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
    if ($info == "deleted_at") {
        return $sl['deleted_at'];
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
    return Identify($InformationType, $information);

}
function GetUserByEmail($Email, $InformationType, $pdo) {
    $information = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $information->execute(array($Email));
    return Identify($InformationType, $information);

}
function Identify($InformationType, $information) {


    if ($InformationType == "id") {
        return $information->fetch()["id"];
    }
    if ($InformationType == "vorname") {
        $tmp = $information->fetch();
        if (!is_array($tmp)) {
            return("Da ist irgendwo was schiefgelaufen...");
        }
        return DecodeFromJson($tmp["vorname"]);
    }
    if ($InformationType == "nachname") {
        $tmp = $information->fetch();
        if (!is_array($tmp)) {
            return("Da ist irgendwo was schiefgelaufen...");
        }
        return DecodeFromJson($tmp["nachname"]);
    }
    if ($InformationType == "name") {
        $tmp = $information->fetch();
        if (!is_array($tmp)) {
            return("Da ist irgendwo was schiefgelaufen...");
        }
        if ($tmp["nachname"] == "") return DecodeFromJson($tmp["vorname"]);
        return DecodeFromJson($tmp["vorname"] . " " . $tmp["nachname"]);
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
        'vorname' => CodeToJson($vorname),
        'nachname' => CodeToJson($nachname),
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
        'vorname' => CodeToJson($vorname),
        'nachname' => CodeToJson($nachname)
    ));
    return $statement->rowCount();
}
function CreateUser($vorname, $nachname, $passwort_hash, $email, $permission_level, $pdo): void {
    $statement = $pdo->prepare("INSERT INTO users (email, passwort, vorname, nachname, permission_level) VALUES (:email, :passwort, :vorname, :nachname, :permission_level)");
    $statement->execute(array(
        'email' => $email,
        'passwort' => $passwort_hash,
        'vorname' => CodeToJson($vorname),
        'nachname' => CodeToJson($nachname),
        'permission_level' => $permission_level
    ));
}
function EnableLesson($userID, $lessonID, $pdo): void {
    $stmt = $pdo->prepare("UPDATE angebot SET disabled = false, last_change_from_userid = ? WHERE id = ?");
    $stmt->execute([$userID, $lessonID]);
}
function DisableLesson($userID, $lessonID, $pdo): void {
    $stmt = $pdo->prepare("UPDATE angebot SET disabled = true, last_change_from_userid = ? WHERE id = ?");
    $stmt->execute([$userID, $lessonID]);
}
function DeleteLesson($lessonId, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM angebot WHERE id = ?");
        $delete_lesson->execute(array($lessonId));
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
        $delete_lesson = $pdo->prepare("DELETE FROM passwordresettokens WHERE token = ?");
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
function DeleteSickNote($sickID, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM sick WHERE id = ?");
        $delete_lesson->execute(array($sickID));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();
    }
}
function GetAllLessonsFromUserAndPrintThem($userid, $limit, $room_names, $times, $pdo, $webroot): string {
    $identifier = GetSetting("identifier", $pdo);
    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE assigned_user_id = ? AND identifier = ? ORDER BY date ASC");
    $lessons->execute(array($userid, $identifier));
    $counter = 1;

    while($sl = $lessons->fetch()) {

        if (isset($sl['date'])) {
            if (date("Y-m-d",time()) > $sl['date'] OR $counter > $limit) {
                continue;
            }
        }

        $counter += 1;

        if ($sl['date_type'] == "2") {
            $date = $sl['date'];
            $single_date1 = explode("-", $date);
            $date_formatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];
        } else {
            $date_day = $sl['date_repeating'];
            $date = $date_day;
            $date_formatted = NumberOfWeekToText($date_day);
        }

        return '
            <tr>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';"></td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['name']), $date) . '</td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['description']), $date) . '</td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("rooms", $sl['location'], $pdo) . '</td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("times", $sl['time'], $pdo) . '</td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . $date_formatted . '</td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';"><span class="dot dot-lg" style="background-color: ' . $sl['box_color'] .'"></span></td>
		    	<td class="pointer" onClick="window.location=\'' . $webroot  . '/lessons/details/?id=' . $sl['id'] . '\';">' . DecodeFromJson($sl['notes']) . '</td>
		    	<td>
		    	    <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    			<span class="text-muted sr-only">Aktion</span>
		    		</button>
		    		<div class="dropdown-menu dropdown-menu-right">
		    		    <a class="dropdown-item" href="../lessons/details/?id=' . $sl['id'] . '">Bearbeiten</a>
		    			<a class="dropdown-item" href="../lessons/details/?remove_lesson_with_id=' . $sl['id'] . '">Löschen</a>
		    		</div>
		    	</td>
		    </tr>
		';
    }
    return "";
}
function GetAllLessons($permission_level, $permission_level_to_display_all, $userId, $pdo): void {
    $identifier = GetSetting("identifier", $pdo);
    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE identifier = ? ORDER BY created_at DESC");
    $lessons->execute(array($identifier));
    while($sl = $lessons->fetch()) {
        if ($permission_level < $permission_level_to_display_all AND str_contains($sl['assigned_user_id'], $userId)) continue;


        if (str_contains($sl['assigned_user_id'], ':')) {
            $sl['assigned_user_id'] = explode(':', $sl['assigned_user_id']);
        } else {
            $sl['assigned_user_id'] = array($sl['assigned_user_id']);
        }

        if (isset($sl['parent_lesson_id'])) continue;

        if ($sl['date_type'] == "2") {
            $date = $sl['date'];
            $single_date1 = explode("-", $date);
            $date_formatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];
        } else {
            $date_day = $sl['date_repeating'];
            $date = $date_day;
            $date_formatted = NumberOfWeekToText($date_day);
        }

        $creator_formatted = "";
        $total_users = count($sl['assigned_user_id']);
        $current_user = 0;

        foreach ($sl['assigned_user_id'] as $assigned_user_id) {
            $current_user++;
            $creator_formatted .= GetUserByID($assigned_user_id, "name", $pdo);
            if ($current_user < $total_users) {
                $creator_formatted .= ", ";
            }
        }


        echo '
            <tr>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';"></td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['name']), $date) . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['description']), $date) . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("rooms", $sl['location'], $pdo) . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("times", $sl['time'], $pdo) . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $date_formatted . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';"><span class="dot dot-lg" style="background-color: ' . $sl['box_color'] .'"></td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $creator_formatted . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . DecodeFromJson($sl['notes']) . '</td>
			    <td>
			        <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    		<span class="text-muted sr-only">Aktion</span>
			    	</button>
			    	<div class="dropdown-menu dropdown-menu-right">
			    	    <a class="dropdown-item" href="./details/?id=' . $sl['id'] . '">Bearbeiten</a>
			    		<a class="dropdown-item" href="./details/?remove_lesson_with_id=' . $sl['id'] . '">Löschen</a>
			    	</div>
			    </td>
			</tr>
		';

    }


}
function GetAllChildLessons($parendId, $pdo): string {
    $identifier = GetSetting("identifier", $pdo);
    $lessons = $pdo->prepare("SELECT * FROM angebot WHERE identifier = ? AND parent_lesson_id = ? ORDER BY created_at DESC");
    $lessons->execute(array($identifier, $parendId));
    $output = "";
    while($sl = $lessons->fetch()) {

        if ($sl['date_type'] == "2") {
            $date = $sl['date'];
            $single_date1 = explode("-", $date);
            $date_formatted = $single_date1[2] . "." . $single_date1[1] . "." . $single_date1[0];
        } else {
            $date_day = $sl['date_repeating'];
            $date = $date_day;
            $date_formatted = NumberOfWeekToText($date_day);
        }

        $creator_formatted = GetUserByID($sl['assigned_user_id'], "name", $pdo);

        $output .= '
            <tr>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';"></td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['name']), $date) . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . replacePlaceholders(DecodeFromJson($sl['description']), $date) . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("rooms", $sl['location'], $pdo) . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . GetSettingWithSuffix("times", $sl['time'], $pdo) . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . $date_formatted . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';"><span class="dot dot-lg" style="background-color: ' . $sl['box_color'] .'"></td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . $creator_formatted . '</td>
			    <td class="pointer" onClick="window.location=\'./?id=' . $sl['id'] . '\';">' . DecodeFromJson($sl['notes']) . '</td>
			    <td>
			        <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    		<span class="text-muted sr-only">Aktion</span>
			    	</button>
			    	<div class="dropdown-menu dropdown-menu-right">
			    	    <a class="dropdown-item" href="./?id=' . $sl['id'] . '">Bearbeiten</a>
			    		<a class="dropdown-item" href="./?remove_lesson_with_id=' . $sl['id'] . '">Löschen</a>
			    	</div>
			    </td>
			</tr>
		';

    }
    return $output;
}
function GetAllSickNotes($permission_level, $permission_level_to_display_all, $userId, $pdo): void {
    $identifier = GetSetting("identifier", $pdo);
    if ($permission_level >= $permission_level_to_display_all) {
        $sick = $pdo->prepare("SELECT * FROM sick WHERE identifier = ? ORDER BY id DESC");
        $sick->execute(array($identifier));
    } else {
        $sick = $pdo->prepare("SELECT * FROM sick WHERE identifier = ? AND userid = ? ORDER BY id DESC");
        $sick->execute(array($identifier, $userId));
    }

    while($sl = $sick->fetch()) {


        $new_assigned_user_id = ($sl['userid'] ?? '');
        $start_date = ($sl['start'] ?? '');
        $end_date = ($sl['end'] ?? '');
        $start_date2 = date("d.m.Y", strtotime($start_date));
        $end_date2 = date("d.m.Y", strtotime($end_date));

        $username = GetUserByID($new_assigned_user_id, "name", $pdo);

        echo '
            <tr>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $username . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $start_date2 . '</td>
			    <td class="pointer" onClick="window.location=\'./details/?id=' . $sl['id'] . '\';">' . $end_date2 . '</td>
			    <td>
			        <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			            <span class="text-muted sr-only">Aktion</span>
			        </button>
			        <div class="dropdown-menu dropdown-menu-right">
			            <a class="dropdown-item" href="./details/?id=' . $sl['id'] . '">Bearbeiten</a>
			            <a class="dropdown-item" href="./details/?remove=' . $sl['id'] . '">Löschen</a>
			        </div>
			    </td>
			</tr>
		';

    }
}
function GetAllUsersAndPrintThem($pdo, $permission_level_names, $permission_level, $manage_other_users): void {

    $users = $pdo->prepare("SELECT * FROM users");
    $users->execute();

    while($sl = $users->fetch()) {

        $id = $sl["id"];
        if ($id == $_SESSION['asl_userid']) {
            continue;
        }
        $username = DecodeFromJson($sl["vorname"] . " " . $sl["nachname"]);
        $email = $sl["email"];
        $permission_level_name = GetHighestValueBelowValueName($sl["permission_level"], $permission_level_names);

        $created_at = date("d.m.Y H:i", strtotime($sl["created_at"]));
        $updated_at = date("d.m.Y H:i", strtotime($sl["updated_at"]));

        echo '
            <tr>
                <td class="pointer" onclick="window.location=\'./edit/?id='. $id . '\';"></td>
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
                    <p class="mb-0">' . $permission_level_name . '</p>
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
                    ';
        if (CheckPermission($manage_other_users, $permission_level, null)) echo '<a class="dropdown-item" href="./?login-to-id='. $id . '">Imitieren</a>';
        echo '
                        <a class="dropdown-item" href="./edit/?id='. $id . '">Bearbeiten</a>
                        <a class="dropdown-item" href="./edit/?delete='. $id . '">Löschen</a>
                    </div>
                </td>
            </tr>
        ';
    }
}
function NumberOfWeekToText($date_day): string {
    if ($date_day == "1") {
        return "Jeden Montag";
    } elseif ($date_day == "2") {
        return "Jeden Dienstag";
    } elseif ($date_day == "3") {
        return "Jeden Mittwoch";
    } elseif ($date_day == "4") {
        return "Jeden Donnerstag";
    } elseif ($date_day == "5") {
        return "Jeden Freitag";
    } else {
        return "Fehler beim Laden des Datums";
    }
}
function GetAllSickNotesRaw($pdo, $date): array {
    $dateObject = new DateTime($date);
    $formattedDate = $dateObject->format('Y-m-d');

    $sick = $pdo->prepare("SELECT * FROM sick WHERE identifier = ? AND ? >= start AND ? <= end");
    $sick->execute(array(GetSetting("identifier", $pdo), $formattedDate, $formattedDate));
    $SickNotes = array();
    $counter = 1;
    while($sl = $sick->fetch()) {

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
function GetAllUsersAndPrintForSelect($pdo, $OwnId, $OwnPermission, $create_lessons, $IdToSelect): void {

    $get_usernames = "SELECT * FROM users ORDER BY permission_level desc";

    foreach ($pdo->query($get_usernames) as $other_users) {

        if ($other_users['permission_level'] >= 99 AND $OwnPermission <= 99) {
            continue;
        } elseif ($OwnPermission <= $create_lessons AND !in_array($other_users['id'], $IdToSelect)) {
            continue;
        } elseif (in_array($other_users['id'], $IdToSelect)) {
            echo "<option selected value='";
        } else {
            echo "<option value='";
        }
        echo $other_users['id'] . "'>" . DecodeFromJson($other_users['vorname']) . " " . DecodeFromJson($other_users['nachname']);
        if ($other_users['id'] == $OwnId) {
            echo " (Du selbst)";
        }
        echo "</option>";
    }

}
function GetEmailFromToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM registertokens WHERE token = ?");
    $tokens->execute(array($token));
    $sl = $tokens->fetch();
    return $sl['email'] ?? 'false';


}
function GetDateFromRegisterToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM registertokens WHERE token = ?");
    $tokens->execute(array($token));
    $sl = $tokens->fetch();
    return $sl['created'] ?? 'false';
}
function GetDateFromResetToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM passwordresettokens WHERE token = ?");
    $tokens->execute(array($token));
    $sl = $tokens->fetch();
    return $sl['created'] ?? 'false';
}
function GetUserIDFromResetToken($token, $pdo) {

    $tokens = $pdo->prepare("SELECT * FROM passwordresettokens WHERE token = ?");
    $tokens->execute(array($token));
    $sl = $tokens->fetch();
    return $sl['userid'] ?? 'false';

}
function CreateTokenForRegistrationAndSaveThem($email, $pdo): string {
    $token = GenerateRandomString();
    $statement = $pdo->prepare("INSERT INTO registertokens (token, email) VALUES (:token, :email)");
    $statement->execute(array(
        'token' => $token,
        'email' => $email
    ));
    return $token;
}
function CreateTokenForPasswordResetAndSaveThem($userid, $pdo): string {
    $token = GenerateRandomString();
    $statement = $pdo->prepare("INSERT INTO passwordresettokens (token, userid) VALUES (:token, :userid)");
    $statement->execute(array(
        'token' => $token,
        'userid' => $userid
    ));
    return $token;
}
function GetLunchData($date, $pdo) {
    $information = $pdo->prepare("SELECT * FROM lunchdata WHERE date = ? ORDER BY id DESC");
    $information->execute(array($date));
    return $information->fetch()["data"];

}
function SetLunchData($date, $value, $pdo): bool|string {
    try {
        $statement = $pdo->prepare("INSERT INTO lunchdata (date, data) VALUES (:date, :data)");
        $statement->execute(array(
            'date' => $date,
            'data' => $value
        ));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Erstellen: " . $e->getMessage();


    }
}


function GetUserSettings($userid, $pdo) {
    $information = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $information->execute(array($userid));
    return $information->fetch()["settings"];
}

function GetUserSetting($userid, $setting, $pdo) {
    $settings = GetUserSettings($userid, $pdo);
    if (isset($settings)) {
        $settingsArray = json_decode($settings, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($settingsArray[$setting])) {
            return $settingsArray[$setting];
        }
    }
    return null;
}

function UpdateUserSetting($userid, $setting, $value, $pdo): bool|string {
    try {
        // Aktuelle Einstellungen abrufen
        $currentSettings = GetUserSettings($userid, $pdo);
        $settingsArray = [];

        if (isset($currentSettings)) {
            $settingsArray = json_decode($currentSettings, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Fehler beim Dekodieren der aktuellen Einstellungen.");
            }
        }

        // Einstellung aktualisieren oder hinzufügen
        $settingsArray[$setting] = $value;
        $newSettingsJson = json_encode($settingsArray);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Fehler beim Kodieren der neuen Einstellungen.");
        }

        // Aktualisierte Einstellungen in die Datenbank speichern
        $statement = $pdo->prepare("UPDATE users SET settings = :settings WHERE id = :id");
        $statement->execute(array(
            'id' => $userid,
            'settings' => $newSettingsJson
        ));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Aktualisieren: " . $e->getMessage();
    } catch (Exception $e) {
        return "Fehler: " . $e->getMessage();
    }
}


function GetSetting($setting, $pdo) {
    $information = $pdo->prepare("SELECT * FROM settings WHERE setting = ?");
    $information->execute(array($setting));
    $settings = $information->fetchAll(PDO::FETCH_ASSOC);

    if (count($settings) == 1) {
        return $settings[0]["value"];
    } else {
        $result = array();
        foreach ($settings as $row) {
            $result[$row["suffix"]] = $row["value"];
        }
        return $result;
    }
}


function GetSettingWithSuffix($setting, $suffix, $pdo) {
    $information = $pdo->prepare("SELECT * FROM settings WHERE setting = ? AND suffix = ?");
    $information->execute(array($setting, $suffix));
    $settings = $information->fetchAll(PDO::FETCH_ASSOC);

    if (count($settings) == 0) return "Value not found";
    if (count($settings) == 1) {
        return $settings[0]["value"];
    } else {
        $result = array();
        foreach ($settings as $row) {
            $result[$row["suffix"]] = $row["value"];
        }
        return $result;
    }


}
function SetSettingWithSuffix($setting, $suffix, $value, $pdo): bool|string {
    try {
        $statement = $pdo->prepare("INSERT INTO settings (setting, suffix, value) VALUES (:setting, :suffix, :value)");
        $statement->execute(array(
            'setting' => $setting,
            'suffix' => $suffix,
            'value' => $value
        ));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Erstellen: " . $e->getMessage();


    }
}
function SetSetting($setting, $value, $pdo): bool|string {
    try {
        $statement = $pdo->prepare("INSERT INTO settings (setting, value) VALUES (:setting, :value)");
        $statement->execute(array(
            'setting' => $setting,
            'value' => $value
        ));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Erstellen: " . $e->getMessage();


    }
}
function UpdateSetting($setting, $value, $pdo): bool|string {
    try {
        $statement = $pdo->prepare("UPDATE settings SET value = :value WHERE setting = :setting");
        $statement->execute(array(
            'setting' => $setting,
            'value' => $value
        ));
        //return $statement->rowCount();
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Erstellen: " . $e->getMessage();


    }
}
function UpdateSettingWithSuffix($setting, $suffix, $value, $pdo): bool|string {
    try {
        $statement = $pdo->prepare("UPDATE settings SET value = :value WHERE setting = :setting AND suffix = :suffix");
        $statement->execute(array(
            'setting' => $setting,
            'suffix' => $suffix,
            'value' => $value
        ));
        //return $statement->rowCount();
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Erstellen: " . $e->getMessage();


    }
}
function DeleteSetting($setting, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM settings WHERE setting = ?");
        $delete_lesson->execute(array($setting));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();


    }
}
function DeleteSettingWithSuffix($setting, $suffix, $pdo): bool|string {
    try {
        $delete_lesson = $pdo->prepare("DELETE FROM settings WHERE setting = ? AND suffix = ?");
        $delete_lesson->execute(array($setting, $suffix));
        return true;
    } catch (PDOException $e) {
        return "Fehler beim Löschen: " . $e->getMessage();


    }
}
