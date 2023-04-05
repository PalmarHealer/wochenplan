<?php


// Include PHPMailer Autoload
require __DIR__ . '/php-mailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * @throws Exception
 */
function SendMail($smtp, $sender, $recipient, $subject, $body): string {
// Create a new PHPMailer instance
    $mail = new PHPMailer(true);

// SMTP configuration
    $mail->isSMTP();
    $mail->Host = $smtp['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtp['username'];
    $mail->Password = $smtp['password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $smtp['port'];

// Sender and recipient settings
    $mail->setFrom($sender['mail'], $sender['name']);
    $mail->addAddress($recipient['mail']);

// Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;


// Attachments (optional)
//$mail->addAttachment('/path/to/file.pdf');

// Send the email
    try {
        $mail->send();
        return 'Email message has been sent';
    } catch (Exception $e) {
        return 'Error occurred while sending email: ' . $mail->ErrorInfo;
    }
}

function GenerateEmail($link): string {

    return '<body id="iz3f" style="box-sizing: border-box; margin: 0;">
  <table class="main-body" style="box-sizing: border-box; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; height: 100%; background-color: rgb(234, 236, 237);" width="100%" height="100%" bgcolor="rgb(234, 236, 237)">
    <tbody style="box-sizing: border-box;">
      <tr class="row" style="box-sizing: border-box; vertical-align: top;" valign="top">
        <td class="main-body-cell" id="iwdo" style="box-sizing: border-box;">
          <table class="container" style="box-sizing: border-box; font-family: Helvetica, serif; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-top: auto; margin-right: auto; margin-bottom: auto; margin-left: auto; height: 0px; width: 90%; max-width: 550px;" width="90%" height="0">
            <tbody style="box-sizing: border-box;">
              <tr style="box-sizing: border-box;">
                <td class="container-cell" id="i53i" style="box-sizing: border-box; vertical-align: top; font-size: medium; padding-bottom: 50px;" valign="top">
                  <table class="c1766" style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: 0px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; min-height: 30px;" width="100%">
                    <tbody style="box-sizing: border-box;">
                      <tr style="box-sizing: border-box;">
                        <td class="cell c1776" style="box-sizing: border-box; width: 70%; vertical-align: middle;" width="70%" valign="middle">
                          <div class="c1144" id="iuk6b" style="box-sizing: border-box; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; font-size: 17px; font-weight: 300;">Wochenplan E-Mail Verifizierung
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table class="card" style="box-sizing: border-box; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-bottom: 20px; height: 0px;" height="0">
                    <tbody style="box-sizing: border-box;">
                      <tr style="box-sizing: border-box;">
                        <td class="card-cell" style="box-sizing: border-box; background-color: rgb(255, 255, 255); overflow-x: hidden; overflow-y: hidden; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; text-align: center;" bgcolor="rgb(255, 255, 255)" align="center">
                          <table class="table100 c1357" style="box-sizing: border-box; width: 100%; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; border-collapse: collapse;" width="100%" height="0">
                            <tbody style="box-sizing: border-box;">
                              <tr style="box-sizing: border-box;">
                                <td class="card-content" id="ixxoj" style="box-sizing: border-box; font-size: 13px; line-height: 20px; color: rgb(111, 119, 125); padding-top: 10px; padding-right: 20px; padding-bottom: 0px; padding-left: 20px; vertical-align: top; padding: 10px 10px 0px 10px;" valign="top">
                                  <h1 class="card-title" id="i4jz1" style="box-sizing: border-box; font-size: 25px; font-weight: 300; color: rgb(68, 68, 68);">
                                  </h1>
                                  <p class="card-text" id="iggya" style="box-sizing: border-box;">Um deine Registrierung abzuschlie&szlig;en, klicke bitte innerhalb der n&auml;chsten 10 Minuten auf den Button unten. Falls dieser nicht angezeigt wird, ist das kein Problem. Am Ende dieser E-Mail steht noch ein Link, der genau dasselbe bewirkt. Falls du dich gerade nicht registrieren m&ouml;chtest, kannst du diese E-Mail einfach ignorieren.
                                  </p>
                                  <table class="c1542" style="box-sizing: border-box; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%;" width="100%">
                                    <tbody style="box-sizing: border-box;">
                                      <tr style="box-sizing: border-box;">
                                        <td id="c1545" class="card-footer" style="box-sizing: border-box; padding-top: 20px; padding-right: 0px; padding-bottom: 20px; padding-left: 0px; text-align: center;" align="center">
                                          <a href="' . $link . '" class="button" id="it95o" style="box-sizing: border-box; font-size: 12px; padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px; text-align: center; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; font-weight: 300; color: rgb(255, 255, 255); background-color: #7d74d1;">Registrierung Abschlie&szligen</a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <a id="iwv5e" style="box-sizing: border-box; font-size: 12px; text-align: left;" href="' . $link . '" >' . $link . '</a>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>';
}


/**
 * @throws Exception
 */
function SendVerificationMail($smtp, $sender, $recipient, $link): string {
    return SendMail($smtp, $sender, $recipient, "Wochenplan Verifizierung", GenerateEmail($link));
}