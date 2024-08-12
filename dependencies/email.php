<?php
require __DIR__ . '/php-mailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function SendMail($smtp, $recipient, $subject, $body, $altBody): bool|string {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $smtp['host'];
        $mail->SMTPAuth   = $smtp['SMTPAuth'];
        $mail->Username   = $smtp['username'];
        $mail->Password   = $smtp['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPDebug  = $smtp['debug'];
        $mail->Port       = $smtp['port'];

        //Recipients
        $mail->setFrom($smtp['from'], $smtp['displayName']);
        $mail->addAddress($recipient['mail']);
        $mail->addReplyTo($smtp['replyTo'], $smtp['replyToName']);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = convertSpecialCharsToEntities($altBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
function GenerateRegisterEmail($link): string {
    return GetEmailTemplate(
        "Wochenplan E-Mail Verifizierung",
        "Um deine <b>Registrierung abzuschlie&szlig;en</b>, klicke bitte innerhalb der n&auml;chsten <b>10 Minuten</b> auf den Button unten.
         Falls du dich gerade nicht registrieren möchtest, kannst du diese E-Mail einfach ignorieren.",
        "Registrierung Abschließen",
        $link);
}

function GenerateResetEmail($link): string {
    return GetEmailTemplate(
        "Passwort vergessen",
        "Um dein <b>Passwort zur&uuml;ckzusetzen</b>, klicke bitte innerhalb der n&auml;chsten <b>10 Minuten</b> auf den Button unten.
              Falls du dein Passwort nicht zurücksetzten möchtest, kannst du diese E-Mail einfach ignorieren.",
        "Passwort Zurücksetzen",
        $link);
}

function GetEmailTemplate($header, $text, $button, $link): string {
 return '
<body id="ibcp" style="box-sizing: border-box; margin: 0;">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" id="isu4">
  <meta charset="utf-8" id="ikei">
  <meta name="viewport" content="initial-scale=1, shrink-to-fit=no" id="i2pt">
  <div id="i7dh" class="wrapper vh-100 noflow" style="box-sizing: border-box; height: 80vh;">
    <div id="iln1" height="100%" class="row align-items-center h-100" style="box-sizing: border-box; display: flex; flex-wrap: wrap; margin-left: 0; margin-right: 0; flex-direction: column; justify-content: center; align-items: center; min-height: 100%;">
      <form method="get" id="ipa3f" width="25%" align="center" class="col-lg-3 col-md-4 col-10 mx-auto text-center" style="box-sizing: border-box; flex: 0 0 83.33333%; padding-left: 15px; padding-right: 15px; position: relative; width: 25%; min-width: 300px; margin-left: auto; margin-right: auto; text-align: center;">
        <div id="irqi1" align="center" class="mx-auto text-center my-4" style="box-sizing: border-box; margin: 1.5rem auto; text-align: center;">
          <img src="https://wochenplan.aktive-schule-leipzig.de/img/logo.svg" alt="Logo" id="ifqfh" valign="middle" class="logo" style="box-sizing: border-box; border-style: none; vertical-align: middle; width: 25%; height: 25%;">
          <h4 id="i9khv" class="my-3" style="box-sizing: border-box; font-size: 1.3125rem; color: #001a4e; font-weight: 600; line-height: 1.2; margin-bottom: 1rem; margin-top: 1rem;">' . convertSpecialCharsToEntities($header) . '
            <br id="i229a" style="box-sizing: border-box;">
          </h4>
        </div>
        <div role="alert" id="iy6kw" bgcolor="#d8f6ec" width="101%" class="alert alert-success" style="box-sizing: border-box; border: 1px solid transparent; border-radius: 0.25rem; margin-bottom: 1rem; padding: 0.75rem 1.25rem; position: relative; background-color: #d8f6ec; border-color: #c8f2e4; color: #1e6d53; justify-content: center; margin: 0 0 30px; width: 101%;">' . convertSpecialCharsToEntities($text) . '
        </div>
        <a href="' . $link . '" id="ibsd3" bgcolor="#1b68ff" align="center" valign="middle" width="70%" class="btn btn-lg btn-primary btn-block" style="box-sizing: border-box; cursor: pointer; background-color: #1b68ff; color: #fff; text-decoration: none; border: 1px solid transparent; border-radius: 0.3rem; display: block; font-size: 1.09375rem; font-weight: 400; line-height: 1.5; padding: 0.5rem 1rem; text-align: center; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; user-select: none; vertical-align: middle; width: 70%; border-color: #1b68ff; margin: auto;">' . convertSpecialCharsToEntities($button) . '<br id="irt0j" style="box-sizing: border-box;"></a>
        <p id="ihp61" class="mt-5 mb-3 text-muted" style="box-sizing: border-box; margin-bottom: 1rem; margin-top: 3rem; font-weight: 300; color: #adb5bd;">&copy; 2024
        </p>
        <p id="i9m24" width="100vw" class="mt-5 mb-3 text-muted" style="box-sizing: border-box; margin-bottom: 1rem; margin-top: 3rem; font-weight: 300; bottom: 0; left: 0; position: fixed; right: auto; top: auto; width: 100vw; color: #adb5bd;">
        </p>
        <a href="' . $link . '" id="idoag" valign="top" align="center" class="gjs-link" style="box-sizing: border-box; background-color: transparent; color: inherit; text-decoration: none; display: inline-block; max-width: 100%; vertical-align: top; text-transform: none; margin: auto; text-align: center;">' . $link . '</a>
      </form>
    </div>
  </div>
  <div id="automa-palette" style="box-sizing: border-box;">
  </div>
</body>
<style>
  .btn-primary:focus {
    background-color: rgb(0, 82, 244);
    border-top-color: rgb(0, 78, 231);
    border-right-color: rgb(0, 78, 231);
    border-bottom-color: rgb(0, 78, 231);
    border-left-color: rgb(0, 78, 231);
    color: rgb(255, 255, 255);
  }
  .btn-primary:hover {
    background-color: rgb(0, 82, 244);
    border-top-color: rgb(0, 78, 231);
    border-right-color: rgb(0, 78, 231);
    border-bottom-color: rgb(0, 78, 231);
    border-left-color: rgb(0, 78, 231);
    color: rgb(255, 255, 255);
  }
  .btn-primary:not(:disabled):not(.disabled).active {
    background-color: rgb(0, 78, 231);
    border-top-color: rgb(0, 74, 218);
    border-right-color: rgb(0, 74, 218);
    border-bottom-color: rgb(0, 74, 218);
    border-left-color: rgb(0, 74, 218);
    color: rgb(255, 255, 255);
  }
  .btn-primary:not(:disabled):not(.disabled):active {
    background-color: rgb(0, 78, 231);
    border-top-color: rgb(0, 74, 218);
    border-right-color: rgb(0, 74, 218);
    border-bottom-color: rgb(0, 74, 218);
    border-left-color: rgb(0, 74, 218);
    color: rgb(255, 255, 255);
  }
  .btn-success:not(:disabled):not(.disabled).active {
    background-color: rgb(40, 177, 131);
    border-top-color: rgb(38, 166, 123);
    border-right-color: rgb(38, 166, 123);
    border-bottom-color: rgb(38, 166, 123);
    border-left-color: rgb(38, 166, 123);
    color: rgb(255, 255, 255);
  }
  .btn-success:not(:disabled):not(.disabled):active {
    background-color: rgb(40, 177, 131);
    border-top-color: rgb(38, 166, 123);
    border-right-color: rgb(38, 166, 123);
    border-bottom-color: rgb(38, 166, 123);
    border-left-color: rgb(38, 166, 123);
    color: rgb(255, 255, 255);
  }
  .btn:focus {
    outline-color: currentcolor;
    outline-style: none;
    outline-width: 0px;
  }
  .btn:hover {
    text-decoration-line: none;
    text-decoration-style: solid;
    text-decoration-color: currentcolor;
    text-decoration-thickness: auto;
  }
  [tabindex="-1"]:focus:not(:focus-visible) {
    outline-color: currentcolor;
    outline-style: none;
    outline-width: 0px;
  }
  [type="button"]::-moz-focus-inner {
    border-top-style: none;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    padding-top: 0px;
    padding-right: 0px;
    padding-bottom: 0px;
    padding-left: 0px;
  }
  [type="reset"]::-moz-focus-inner {
    border-top-style: none;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    padding-top: 0px;
    padding-right: 0px;
    padding-bottom: 0px;
    padding-left: 0px;
  }
  [type="submit"]::-moz-focus-inner {
    border-top-style: none;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    padding-top: 0px;
    padding-right: 0px;
    padding-bottom: 0px;
    padding-left: 0px;
  }
  button::-moz-focus-inner {
    border-top-style: none;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    padding-top: 0px;
    padding-right: 0px;
    padding-bottom: 0px;
    padding-left: 0px;
  }
  a.text-primary:focus {
    color: rgb(0, 69, 206);
  }
  a.text-primary:hover {
    color: rgb(0, 69, 206);
  }
  a.text-success:focus {
    color: rgb(36, 156, 116);
  }
  a.text-success:hover {
    color: rgb(36, 156, 116);
  }
  a:hover {
    color: rgb(0, 69, 206);
    text-decoration-line: underline;
    text-decoration-style: solid;
    text-decoration-color: currentcolor;
    text-decoration-thickness: auto;
  }
  a:not([href]):hover {
    color: inherit;
    text-decoration-line: none;
    text-decoration-style: solid;
    text-decoration-color: currentcolor;
    text-decoration-thickness: auto;
  }
  button:focus {
    outline-color: currentcolor;
    outline-style: none;
    outline-width: 0px;
  }
</style>';
}

function SendVerificationMail($smtp, $recipient, $link): string {
    return SendMail(
            $smtp,
            $recipient,
            "Wochenplan Verifizierung",
            GetEmailTemplate(
                "Wochenplan E-Mail Verifizierung",
                "Um deine <b>Registrierung abzuschließen</b>,
                      klicke bitte innerhalb der nächsten <b>10 Minuten</b> auf den Button unten.
                      Falls du dich gerade nicht registrieren möchtest, kannst du diese E-Mail einfach ignorieren.",
                "Registrierung Abschließen",
                $link),
            "Die E-Mail um dein Account zu erstellen.");
}

function SendResetMail($smtp, $recipient, $link): string {
    return SendMail(
            $smtp,
            $recipient,
            "Wochenplan",
            GetEmailTemplate(
                "Passwort vergessen",
                "Um dein <b>Passwort zurückzusetzen</b>,
                      klicke bitte innerhalb der nächsten <b>10 Minuten</b> auf den Button unten.
                      Falls du dein Passwort nicht zurücksetzten möchtest, kannst du diese E-Mail einfach ignorieren.",
                "Passwort Zurücksetzen",
                $link),
            "Deine E-Mail um dein Passwort zurückzusetzen.");
}