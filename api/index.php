<?php
global $pdo, $domain;
$include_path = __DIR__ . "/..";
$page = "external";
require $include_path . "/dependencies/config.php";
$permission_needed = 0;
require $include_path . "/dependencies/mysql.php";
require $include_path . "/dependencies/framework.php";
$keep_pdo = true;
$error = false;
$error_message = "";
$date = "";

// Check if using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check API key
    if (!isset($_POST['secret']) OR $_POST['secret'] != "api secret") {
        $error = true;
        $error_message = "Wrong API key.";
    }

    // Get Action type
    if (!isset($_POST['action'])) {
        $error = true;
        $error_message = "Please provide an action.";
        $action = null;
    } else $action = $_POST['action'];

    // Check if 'date' is set in POST and validate the format and convertibility
    if (!isset($_POST['date'])) {
        $error = true;
        $error_message = "Please provide a date.";
    } else {
        $date = $_POST['date'];
        $date_parts = explode('-', $date);

        try {
            $dateObject = new DateTime($date);
            $newDate = $dateObject->format("Y-m-d");
        } catch (Exception $e) {
            $error = true;
            $error_message("Please provide a date in YYYY-MM-DD format or a convertable one.");
        }
    }
    if (!$error AND $action == "update-meal") {
        $response = DeleteLunchData($newDate, $pdo);
        if ($response) {
            $response = array(
                'date' => $newDate,
                'status' => 'success',
                'reason' => false
            );
        } else {
            $response = array(
                'date' => $newDate,
                'status' => 'failed',
                'reason' => $response
            );
        }
    } else {

        $response = array(
            'error' => $error_message
        );

    }
    // set to json
    header('Content-Type: application/json');

    // return the json
    echo json_encode($response);
    die();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Dokumentation - Wochenplan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px 40vw 20px 20px;
        }
        header, section {
            margin-bottom: 20px;
        }
        h1, h2 {
            color: #333;
        }
        code, pre {
            background-color: #f4f4f4;
            padding: 2px 5px;
            border-radius: 5px;
        }
        pre {
            white-space: pre-wrap;       /* Since CSS 2.1 */
            white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
            white-space: -pre-wrap;      /* Opera 4-6 */
            white-space: -o-pre-wrap;    /* Opera 7 */
            word-wrap: break-word;       /* Internet Explorer 5.5+ */
        }
    </style>
</head>
<body>
<header>
    <h1>API Dokumentation - Wochenplan</h1>
</header>
<section>
    <h2>Übersicht</h2>
    <p>Dieser API-Endpunkt ermöglicht es autorisierten Clients, mit einem Mahlzeitenplanungssystem zu interagieren, um Mahldaten an einem bestimmten Datum zu aktualisieren. Für die Nutzung ist eine Authentifizierung durch einen API-Schlüssel erforderlich. Die API ist privat und nur für berechtigte Benutzer zugänglich.</p>
</section>
<section>
    <h2>Basis-URL</h2>
    <p><code><?php echo $domain; ?>/api</code></p>
</section>
<section>
    <h2>Authentifizierung</h2>
    <p>Anfragen müssen einen API-Schlüssel enthalten, der über den POST-Parameter <code>secret</code> übermittelt wird. Der korrekte Schlüssel ist für eine erfolgreiche Authentifizierung erforderlich.</p>
</section>
<section>
    <h2>Anforderungsparameter</h2>
    <ul>
        <li><code>secret</code> (Zeichenkette): API-Schlüssel zur Authentifizierung.</li>
        <li><code>action</code> (Zeichenkette): Gibt die Art der durchzuführenden Aktion an. Unterstützt derzeit <code>update-meal</code>.</li>
        <li><code>date</code> (Zeichenkette): Das Datum, für das das Mahlupdate angewendet werden soll, formatiert als <code>YYYY-MM-DD</code>.</li>
    </ul>
</section>
<section>
    <h2>Antwort-Schemata</h2>
    <p>Je nach Anfrage und Verarbeitung kann die API unterschiedliche Antworten zurückgeben:</p>
    <ul>
        <li><strong>Erfolgreiche Antwort:</strong>
            <pre>{
    "date": "2024-08-20",
    "status": "success",
    "reason": false
}</pre>
        </li>
        <li><strong>Fehlerhafte Antwort:</strong>
            <pre>{
    "date": "2024-08-20",
    "status": "failed",
    "reason": "Datenbankfehler: [Fehlerdetails]"
}</pre>
        </li>
        <li><strong>Fehler bei der Validierung:</strong>
            <pre>{
    "error": "Wrong API key."
}</pre>
        </li>
    </ul>
</section>
<section>
    <h2>Beispielanforderung</h2>
    <p>Hier ist ein Beispiel, wie man <code>curl</code> verwendet, um eine Anfrage an diesen API-Endpunkt zu stellen:</p>
    <code>curl -X POST '<?php echo $domain; ?>/api' \
        -H 'Content-Type: application/x-www-form-urlencoded' \
        -d 'secret=DeinGeheimerSchlüssel&action=update-meal&date=2024-08-20'</code>
</section>
<section>
    <h2>Sicherheitshinweise</h2>
    <p>Stellen Sie sicher, dass der API-Schlüssel vertraulich behandelt und bei Kompromittierung neu generiert wird. Verwenden Sie HTTPS, um API-Anfragen und Antworten zu verschlüsseln und zu sichern.</p>
</section>
</body>
</html>
